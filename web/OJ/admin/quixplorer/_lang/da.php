<?php

// Dansk sprog Module for v2.3 (translated by Marie Kroun, Denmark)

$GLOBALS["charset"] = "iso-8859-1";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"		=> "Fejl",
	"back"		=> "Tilbage",
	
	// root
	"home"		=> "Hjem mappen findes ikke, check indstillinger.",
	"abovehome"	=> "Aktuelle mappe kan ikke v�re over hjem mappen.",
	"targetabovehome"	=> "M�l mappen kan ikke v�re over hjem mappen.",
	
	// exist
	"direxist"		=> "Mappen findes ikke.",
	//"filedoesexist"	=> "Filen findes allerede.",
	"fileexist"		=> "Filen findes ikke.",
	"itemdoesexist"	=> "Emnet findes allerede.",
	"itemexist"		=> "Emnet findes ikke.",
	"targetexist"	=> "M�lmappen findes ikke.",
	"targetdoesexist"	=> "M�l emnet findes allerede.",
	
	// open
	"opendir"		=> "Kan ikke �bne mappen.",
	"readdir"		=> "Kan ikke l�se mappen.",
	
	// access
	"accessdir"		=> "Du har ikke adgang til denne mappe.",
	"accessfile"	=> "Du har ikke adgang til denne fil.",
	"accessitem"	=> "Du har ikke adgang til dette emne.",
	"accessfunc"	=> "Du har ikke adgang til at bruge denne funktion.",
	"accesstarget"	=> "Du har ikke adgang til m�l mappen.",
	
	// actions
	"permread"	=> "Tilladelse fejlede..",
	"permchange"	=> "�ndring af tilladelse mislykkedes.",
	"openfile"		=> "Filen kunne ikke �bnes.",
	"savefile"		=> "Filen kunne ikke gemmes.",
	"createfile"		=> "Filen kunne ikke oprettes.",
	"createdir"		=> "Mappen kunne ikke oprettes.",
	"uploadfile"	=> "Filen kunne ikke hentes (upload).",
	"copyitem"		=> "Kopiering fejlede.",
	"moveitem"	=> "Flytning fejlede.",
	"delitem"		=> "Filen blev IKKE slettet.",
	"chpass"		=> "Kodeord kunne ikke �ndres.",
	"deluser"		=> "Bruger kunne ikke fjernes.",
	"adduser"		=> "Oprettelse af ny bruger mislykkedes.",
	"saveuser"		=> "Bruger kunne ikke gemmes.",
	"searchnothing"	=> "Du skal indtaste noget at s�ge efter.",
	
	// misc
	"miscnofunc"	=> "Funktionen mangler.",
	"miscfilesize"	=> "Filen overskrider maksimum st�rrelse.",
	"miscfilepart"	=> "Kun en del af filen blev lagt op.",
	"miscnoname"	=> "Indtast et navn.",
	"miscselitems"	=> "Du har ikke valgt emne(r).",
	"miscdelitems"	=> "Er du sikker p� du vil slette de(t) \"+num+\" emne(r)?",
	"miscdeluser"	=> "Er du sikker p� at du vil slette bruger: '\"+user+\"'?",
	"miscnopassdiff"	=> "Det nye kodeord er det samme som det aktuelle.",
	"miscnopassmatch"	=> "Kodeord er ikke ens.",
	"miscfieldmissed"	=> "Du glemte et vigtigt felt.",
	"miscnouserpass"	=> "Bruger ID eller kodeord er forkert.",
	"miscselfremove"	=> "Du kan ikke slette dig selv.",
	"miscuserexist"	=> "Bruger findes allerede.",
	"miscnofinduser"	=> "Bruger findes ikke.",
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "RETTE TILLADELSER",
	"editlink"		=> "RETTE",
	"downlink"		=> "HENT",
	"uplink"		=> "OP",
	"homelink"		=> "HJEM",
	"reloadlink"	=> "GENINDL�S",
	"copylink"		=> "KOPIER",
	"movelink"		=> "FLYT",
	"dellink"		=> "SLET",
	"comprlink"	=> "ARKIV",
	"adminlink"	=> "ADMIN",
	"logoutlink"	=> "LOG UD",
	"uploadlink"	=> "TILF�J",
	"searchlink"	=> "S�G",
	
	// list
	"nameheader"	=> "Navn",
	"sizeheader"	=> "St�rrelse",
	"typeheader"	=> "Type",
	"modifheader"	=> "Rettet",
	"permheader"	=> "Tillad",
	"actionheader"	=> "Handlinger",
	"pathheader"	=> "Sti",
	
	// buttons
	"btncancel"	=> "Fortryd",
	"btnsave"		=> "Gem",
	"btnchange"	=> "Rette",
	"btnreset"		=> "Nulstil",
	"btnclose"		=> "Luk",
	"btncreate"	=> "Opret",
	"btnsearch"	=> "S�g",
	"btnupload"	=> "Tilf�j",
	"btncopy"		=> "Kopier",
	"btnmove"		=> "Flyt",
	"btnlogin"		=> "Log ind",
	"btnlogout"		=> "Log ud",
	"btnadd"		=> "Tilf�j",
	"btnedit"		=> "Ret",
	"btnremove"	=> "Fjern",
	
	// actions
	"actdir"		=> "Mappe",
	"actperms"		=> "Ret tilladelser",
	"actedit"		=> "Ret fil",
	"actsearchresults"	=> "S�ge resultater",
	"actcopyitems"	=> "Kopier emne(r)",
	"actcopyfrom"	=> "Kopier fra /%s til /%s ",
	"actmoveitems"	=> "Flyt emne(r)",
	"actmovefrom"	=> "Flyt fra /%s til /%s ",
	"actlogin"		=> "Log ind",
	"actloginheader"	=> "Log ind til QuiXplorer",
	"actadmin"		=> "Administration",
	"actchpwd"		=> "Ret kodeord",
	"actusers"		=> "Brugere",
	"actarchive"	=> "Arkiver emne(r)",
	"actupload"	=> "Tilf�j fil(er)",
	
	// misc
	"miscitems"	=> "Emner(r)",
	"miscfree"		=> "Resterende plads",
	"miscusername"	=> "Brugernavn",
	"miscpassword"	=> "Kodeord",
	"miscoldpass"	=> "Gamle Kodeord",
	"miscnewpass"	=> "Nyt kodeord",
	"miscconfpass"	=> "Gentag kodeord",
	"miscconfnewpass"	=> "Bekr�ft nyt kodeord",
	"miscchpass"	=> "Rette kodeord",
	"mischomedir"	=> "Hjem mappe",
	"mischomeurl"	=> "Hjem URL",
	"miscshowhidden"	=> "Vis skjulte emne(r)",
	"mischidepattern"	=> "Skjul koder",
	"miscperms"	=> "Tilladelser",
	"miscuseritems"	=> "(navn, hjem mappe, vis skjulte emner, tilladelser, aktiv)",
	"miscadduser"	=> "Tilf�j bruger",
	"miscedituser"	=> "Ret bruger '%s'",
	"miscactive"	=> "Aktiv",
	"misclang"		=> "Sprog",
	"miscnoresult"	=> "Ingen resultat.",
	"miscsubdirs"	=> "S�g i undermapper",
	"miscpermnames"	=> array("Vis kun","Ret","�ndre kodeord","Ret & �ndre kodeord",
					"Administrator"),
	"miscyesno"		=> array("Ja","Nej","J","N"),
	"miscchmod"		=> array("Ejer", "Gruppe", "Offentlig"),
);
?>
