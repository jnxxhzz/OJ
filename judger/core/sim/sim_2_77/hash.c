/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	$Id: hash.c,v 2.23 2014-01-27 20:50:50 Gebruiker Exp $
*/

/*	Text is compared by comparing every substring to all substrings
	to the right of it; this process is in essence quadratic.  However,
	only substrings of length at least 'Min_Run_Size' are of interest,
	which gives us the possibility to speed up this process by using
	a hash table.

	For every position in the text, we construct an index which gives
	the next position in the text at which a run of Min_Run_Size tokens
	starts that has the same hash code, as calculated by hash1().  If
	there is no such run, the index is 0.  These forward references are
	kept in the array forward_reference[].

	To construct this array, we use a hash table last_index[] whose size
	is a prime and which is about 8 times smaller than the text array.
	The hash table last_index[] is set up such that last_index[i] is the
	index of the latest token with hash_code i, or 0 if there is none.
	This results in hash chains of an average length of 8.  See
	Make_Forward_References().

	If there is not enough room for a hash table of the proper size
	(which can be considerable) the hashing is not efficient any more.
	In that case, the forward reference table is scanned a second time,
	eliminating from any chain all references to runs that do not hash to
	the same value under a second hash function, hash2().  For the UNIX
	manuals this reduced the number of matches from 91.9% to 1.9% (of
	which 0.06% was genuine).
*/

#include	<stdio.h>
#include	<stdint.h>

#include	"system.par"
#include	"debug.par"
#include	"sim.h"
#include	"text.h"
#include	"Malloc.h"
#include	"error.h"
#include	"any_int.h"
#include	"token.h"
#include	"language.h"
#include	"token.h"
#include	"tokenarray.h"
#include	"options.h"
#include	"hash.h"

							/* MAIN ENTRIES */
static size_t *forward_reference;		/* to be filled by Malloc() */
static size_t n_forward_references;

static void make_forward_references_hash1(void);
static void make_forward_references_hash2(void);

#ifdef	DB_FORW_REF
static void db_forward_references(const char *);
static void make_forward_references_hash3(void);
#endif

void
Make_Forward_References(void) {
	/*	Constructs the forward references table.
	*/

	n_forward_references = Text_Length();
	forward_reference =
		(size_t *)Calloc(
			n_forward_references, sizeof (size_t)
		);
	make_forward_references_hash1();
	make_forward_references_hash2();
#ifdef	DB_FORW_REF
	make_forward_references_hash3();
#endif
}

size_t
Forward_Reference(size_t i) {
	if (i == 0 || i >= n_forward_references) {
		fatal("internal error, bad forward reference");
	}
	return forward_reference[i];
}

void
Free_Forward_References(void) {
	Free((char *)forward_reference);
}

							/* HASHING */
/*
	We want a hash function whose time cost does not depend on
	Min_Run_Size, which is a problem since the size of the object
	we derive the hash value from IS equal to Min_Run_Size!
	Therefore we base the hash function on a sample of at most
	N_SAMPLES tokens from the input string; this works just
	as well in practice.
*/

#define	N_SAMPLES	24
#define	OPERATION	^

/*	An alternative algorithm; does not seem to make any difference.
#define	N_SAMPLES	23
#define	OPERATION	+
*/

/*	Another algorithm; not yet tested
#define	N_SAMPLES	24
#define	OPERATION	+ 613 *
*/

static size_t *last_index;
static size_t hash_table_size;
/* positions where the N_SAMPLES samples can be found: */
static size_t sample_pos[N_SAMPLES];

/* The prime numbers of the form 4 * i + 3 for some i, all greater
   than twice the previous one and smaller than 2^40 (for now).
*/
static const uint64_t prime[] = {
#if 0
	3,
	7,
	19,
	43,
	103,
	211,
	431,
	863,
	1747,
	3499,
	7019,
#endif
	14051,
	28111,
	56239,
	112507,
	225023,
	450067,
	900139,
	1800311,
	3600659,
	7201351,
	14402743,
	28805519,
	57611039,
	115222091,
	230444239,
	460888499,
	921777067,
	1843554151,
	UINT64_C (3687108307),
	UINT64_C (7374216631),
	UINT64_C (14748433279),
	UINT64_C (29496866579),
	UINT64_C (58993733159),
	UINT64_C (117987466379),
	UINT64_C (235974932759),
	UINT64_C (471949865531),
	UINT64_C (943899731087)
	/* 2^40= 1099511627776 */
};

static void
init_hash_table(void) {
	int n;

	/* find the ideal hash table size */
	n = 0;
	while (prime[n] < Text_Length()) {
		n++;
		/* this will always terminate, if prime[] is large enough */
	}

	/* see if we can allocate that much space, and if not, step down */
	last_index = 0;
	while (!last_index && n >= 0) {
		hash_table_size = prime[n];
		last_index = (size_t *)
			TryCalloc(hash_table_size, sizeof (size_t));
		n--;
	}
	if (!last_index) {
		fatal("out of memory");
	}

	/* find sample positions */
	for (n = 0; n < N_SAMPLES; n++) {
		/* straigh-line approximation; uninituitive as usual */
		sample_pos[n] = (
			(2 * n * (Min_Run_Size - 1) + (N_SAMPLES - 1))
		/	(2 * (N_SAMPLES - 1))
		);
	}
}

static size_t hash1(const Token *);

static void
make_forward_references_hash1(void) {
	int n;

	init_hash_table();

	/* set up the forward references using the last_index hash table */
	for (n = 0; n < Number_Of_Texts; n++) {
		struct text *txt = &Text[n];
		size_t j;

		for (	/* all pos'ns in txt except the last Min_Run_Size-1 */
			j = txt->tx_start;			/* >= 1 */
			j + Min_Run_Size - 1 < txt->tx_limit;
			j++
		) {
			if (May_Be_Start_Of_Run(Token_Array[j])) {
				size_t h = hash1(&Token_Array[j]);

				if (last_index[h]) {
					forward_reference[last_index[h]] = j;
				}
				last_index[h] = j;
			}
		}
	}
	Free((char *)last_index);

#ifdef	DB_FORW_REF
	db_forward_references("first hashing");
#endif	/* DB_FORW_REF */
}

static size_t
hash1(const Token *p) {
	/*	hash1(p) returns the hash code of Min_Run_Size
		tokens starting at p; caller guarantees that there
		are at least Min_Run_Size tokens.
	*/
	uint64_t h_val;
	int n;

	h_val = 0;
	for (n = 0; n < N_SAMPLES; n++) {
		h_val = (h_val << 2) OPERATION Token2int(p[sample_pos[n]]);
		if (h_val & (1ULL<<63)) {
			h_val ^= (1ULL<<63|1);
		}
	}

#ifdef	DB_HASH
	/* reduce h_val to the type yielded by hash1(), and print the
	   result in a responsible way
	*/
	size_t h = (size_t) (h_val % hash_table_size);
	fprintf(Debug_File, "hash1 = %s\n", any_uint2string(h, 0));
#endif	/* DB_HASH */

	return (size_t) (h_val % hash_table_size);
}

static size_t hash2(const Token *);

static void
make_forward_references_hash2(void) {
	size_t i;

	/*	Clean out spurious matches, by a quadratic algorithm.
		Note that we do not want to eliminate overlapping
		sequences in this stage, since we might be removing the
		wrong copy.
	*/
	for (i = 0; i+Min_Run_Size < Text_Length(); i++) {
		size_t j = i;
		size_t h2 = hash2(&Token_Array[i]);

		/*	Find the first token sequence in the chain
			with same secondary hash code.
		*/
		while (	/* there is still a forward reference */
			(j = forward_reference[j])
		&&	/* its hash code does not match */
			hash2(&Token_Array[j]) != h2
		) {
			/* continue searching */
		}
		/* short-circuit forward reference to it, or to zero */
		forward_reference[i] = j;
	}

#ifdef	DB_FORW_REF
	db_forward_references("second hashing");
#endif	/* DB_FORW_REF */
}

static size_t
hash2(const Token *p) {
	/*	A simple-minded hashing for the secondary sweep;
		sample first, middle, and last token, and on 64-bit systems
		also two tokens from in between these.
	*/
	int pos_last_sample = N_SAMPLES - 1;
	uint64_t h_val = 0;
	h_val ^= ((uint64_t)Token2int(p[sample_pos[0]])) << 0;
	h_val ^= ((uint64_t)Token2int(p[sample_pos[pos_last_sample]])) << 16;
	h_val ^= ((uint64_t)Token2int(p[sample_pos[pos_last_sample/2]])) << 24;
	h_val ^= ((uint64_t)Token2int(p[sample_pos[pos_last_sample*1/4]]))<<32;
	h_val ^= ((uint64_t)Token2int(p[sample_pos[pos_last_sample*3/4]]))<<48;
	/* the last two are ignored on a 32-bit system */
	h_val *= 2147483647;
	h_val &= 017777777777;

#ifdef	DB_HASH
	/* reduce h_val to the type yielded by hash2(), and print the
	   result in a responsible way
	*/
	size_t h = (size_t)h_val;
	fprintf(Debug_File, "hash2 = %s\n", any_uint2string(h, 0));
#endif	/* DB_HASH */

	return (size_t)h_val;
}

#ifdef	DB_FORW_REF

static int hash3(const Token *, const Token *);

static void
db_print_forward_references(void) {
	size_t n;
	size_t *printed_at =
		(size_t *)Calloc(Text_Length(), sizeof (size_t));

	for (n = 1; n < Text_Length(); n++) {
		size_t fw = forward_reference[n];
		if (fw == 0) continue;
		fprintf(Debug_File, "FWR[%s]:", any_uint2string(n, 0));
		if (printed_at[fw]) {
			fprintf(Debug_File, " see %s",
				any_uint2string(printed_at[fw], 0));
		}
		else {
			while (fw) {
				fprintf(Debug_File, " %s",
					any_uint2string(fw, 0));
				printed_at[fw] = n;
				fw = forward_reference[fw];
			}
		}
		fprintf(Debug_File, "\n");
	}
	Free((void *)printed_at);
}

static void
make_forward_references_hash3(void) {
	size_t i;

	/* Do a third hash to check up on the previous two */

	/* This time we use a genuine compare */
	for (i = 0; i+Min_Run_Size < Text_Length(); i++) {
		size_t j = i;

		while (	/* there is still a forward reference */
			(j = forward_reference[j])
		&&	/* its hash code does not match */
			!hash3(&Token_Array[i], &Token_Array[j])
		) {
			/* continue searching */
		}
		/* short-circuit forward reference to it, or to zero */
		forward_reference[i] = j;
	}

	db_forward_references("third hashing");
}

static int
hash3(const Token *p, const Token *q) {
	/* a full comparison for the tertiary sweep */
	size_t n;

	for (n = 0; n < Min_Run_Size; n++) {
		if (!Token_EQ(p[n], q[n])) return 0;
	}
	return 1;
}

static size_t
db_frw_chain(size_t n, char *crossed_out) {
	if (forward_reference[n] == 0) {
		fprintf(Debug_File,
			">>>> db_frw_chain() forward_reference[n] == 0 <<<<\n"
		);
		return 0;
	}

	size_t n_entries = 0;
	size_t fw;

	for (fw = n; fw; fw = forward_reference[fw]) {
		if (crossed_out[fw]) {
			fprintf(Debug_File,
				">>>> error: forward references cross <<<<\n"
			);
		}
		n_entries++;
		crossed_out[fw] = 1;
	}
	fprintf(Debug_File, "chain_start = %s, n_entries = %s\n",
		any_uint2string(n, 0), any_uint2string(n_entries, 0));

	/* return chain length */
	return n_entries - 1;
}

static void
db_forward_references(const char *msg) {
	size_t n;
	size_t n_frw_chains = 0;	/* number of forward ref. chains */
	size_t tot_frwc_len = 0;
	char *crossed_out;

	fprintf(Debug_File, "\n\n**** DB_FORWARD_REFERENCES, %s ****\n", msg);
	fprintf(Debug_File, "hash_table_size = %s\n",
		any_uint2string(hash_table_size, 0));
	fprintf(Debug_File, "N_SAMPLES = %d\n", N_SAMPLES);

	crossed_out = (char *)Calloc(Text_Length(), sizeof (char));

	/*	Each forward_reference[n] starts in principle a new
		chain, and these chains never touch each other.
		We check this property by marking the positions in each
		chain in an array; if we meet a marked entry while
		following a chain, it must have been on an earlier chain
		and we have an error.
		We also determine the lengths of the chains, for statistics.
	*/
	if (forward_reference[0]) {
		fprintf(Debug_File,
			">>>> forward_reference[0] is not zero <<<<\n"
		);
	}
	for (n = 1; n < Text_Length(); n++) {
		if (forward_reference[n] && !crossed_out[n]) {
			/* start of a new chain */
			n_frw_chains++;
			tot_frwc_len += db_frw_chain(n, crossed_out);
		}
	}
	db_print_forward_references();

	Free((char *)crossed_out);

	fprintf(Debug_File,
		"text length = %s, # forward chains = %s, total frw chain length = %s\n\n",
		any_uint2string(Text_Length(), 0),
		any_uint2string(n_frw_chains, 0),
		any_uint2string(tot_frwc_len, 0)
	);
}

#endif	/* DB_FORW_REF */
