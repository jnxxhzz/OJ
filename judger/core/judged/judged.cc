/*
 * Copyright 2008 sempr <iamsempr@gmail.com>
 *
 * Refacted and modified by zhblue<newsclan@gmail.com> 
 * Bug report email newsclan@gmail.com
 * 
 * This file is part of HUSTOJ.
 *
 * HUSTOJ is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * HUSTOJ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with HUSTOJ. if not, see <http://www.gnu.org/licenses/>.
 */
#include <time.h>
#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include <stdlib.h>
#include <unistd.h>
#include <syslog.h>
#include <errno.h>
#include <fcntl.h>
#include <stdarg.h>
#include <mysql/mysql.h>
#include <sys/wait.h>
#include <sys/stat.h>
#include <signal.h>
#include <sys/resource.h>

#define BUFFER_SIZE 1024
#define LOCKFILE "/var/run/judged.pid"
#define LOCKMODE (S_IRUSR|S_IWUSR|S_IRGRP|S_IROTH)
/*
S_IRUSR 或S_IREAD, 00400 权限, 代表该文件所有者具有可读取的权限.
S_IWUSR 或S_IWRITE, 00200 权限, 代表该文件所有者具有可写入的权限.
S_IRGRP 00040 权限, 代表该文件用户组具有可读的权限.
S_IROTH 00004 权限, 代表其他用户具有可读的权限
*/
#define STD_MB 1048576

#define OJ_WT0 0
#define OJ_WT1 1
#define OJ_CI 2
#define OJ_RI 3
#define OJ_AC 4
#define OJ_PE 5
#define OJ_WA 6
#define OJ_TL 7
#define OJ_ML 8
#define OJ_OL 9
#define OJ_RE 10
#define OJ_CE 11
#define OJ_CO 12
static char lock_file[BUFFER_SIZE]=LOCKFILE;
static char host_name[BUFFER_SIZE];
static char user_name[BUFFER_SIZE];
static char password[BUFFER_SIZE];
static char db_name[BUFFER_SIZE];
static char oj_home[BUFFER_SIZE];
static char oj_lang_set[BUFFER_SIZE];
static int port_number;
static int max_running;
static int sleep_time;
static int sleep_tmp;
static int oj_tot;
static int oj_mod;
static int http_judge = 0;
static char http_baseurl[BUFFER_SIZE];
static char http_username[BUFFER_SIZE];
static char http_password[BUFFER_SIZE];

static int  oj_redis = 0;
static char oj_redisserver[BUFFER_SIZE];
static int  oj_redisport;
static char oj_redisauth[BUFFER_SIZE];
static char oj_redisqname[BUFFER_SIZE];
static int turbo_mode = 0;


static bool STOP = false;
static int DEBUG = 0;
static int ONCE = 0;
#ifdef _mysql_h
static MYSQL *conn;
static MYSQL_RES *res; //mysql读取结果集，在_get_http/mysql_jobs()中被更新
static MYSQL_ROW row;
//static FILE *fp_log;
static char query[BUFFER_SIZE]; //在init_mysql_conf中更新，固定取2倍最大判题客户端的待评判题目solution_id
#endif

void call_for_exit(int s) {
	STOP = true;
	printf("Stopping judged...\n");
}

void write_log(const char *fmt, ...) {
	va_list ap;
	char buffer[4096];
//	time_t          t = time(NULL);
//	int             l;
	sprintf(buffer, "%s/log/client.log", oj_home);
	FILE *fp = fopen(buffer, "ae+");
	if (fp == NULL) {
		fprintf(stderr, "openfile error!\n");
		system("pwd");
	}
	va_start(ap, fmt);
	vsprintf(buffer, fmt, ap);
	fprintf(fp, "%s\n", buffer);
	if (DEBUG)
		printf("%s\n", buffer);
	va_end(ap);
	fclose(fp);

}

int after_equal(char * c) {
	int i = 0;
	for (; c[i] != '\0' && c[i] != '='; i++)
		;
	return ++i;
}
void trim(char * c) {
	char buf[BUFFER_SIZE];
	char * start, *end;
	strcpy(buf, c);
	start = buf;
	while (isspace(*start))
		start++;
	end = start;
	while (!isspace(*end))
		end++;
	*end = '\0';
	strcpy(c, start);
}
bool read_buf(char * buf, const char * key, char * value) {
	if (strncmp(buf, key, strlen(key)) == 0) {
		strcpy(value, buf + after_equal(buf));
		trim(value);
		if (DEBUG)
			printf("%s\n", value);
		return 1;
	}
	return 0;
}
void read_int(char * buf, const char * key, int * value) {
	char buf2[BUFFER_SIZE];
	if (read_buf(buf, key, buf2))
		sscanf(buf2, "%d", value);

}
// read the configue file
void init_mysql_conf() {
	FILE *fp = NULL;
	char buf[BUFFER_SIZE];
	host_name[0] = 0;
	user_name[0] = 0;
	password[0] = 0;
	db_name[0] = 0;
	port_number = 3306;
	max_running = 3;
	sleep_time = 1;
	oj_tot = 1;
	oj_mod = 0;
	strcpy(oj_lang_set, "0,1,2,3");
	fp = fopen("./etc/judge.conf", "r");
	if (fp != NULL) {
		while (fgets(buf, BUFFER_SIZE - 1, fp)) {
			read_buf(buf, "OJ_HOST_NAME", host_name);
			read_buf(buf, "OJ_USER_NAME", user_name);
			read_buf(buf, "OJ_PASSWORD", password);
			read_buf(buf, "OJ_DB_NAME", db_name);
			read_int(buf, "OJ_PORT_NUMBER", &port_number);
			read_int(buf, "OJ_RUNNING", &max_running);
			read_int(buf, "OJ_SLEEP_TIME", &sleep_time);
			read_int(buf, "OJ_TOTAL", &oj_tot);

			read_int(buf, "OJ_MOD", &oj_mod);

			read_int(buf, "OJ_HTTP_JUDGE", &http_judge);
			read_buf(buf, "OJ_HTTP_BASEURL", http_baseurl);
			read_buf(buf, "OJ_HTTP_USERNAME", http_username);
			read_buf(buf, "OJ_HTTP_PASSWORD", http_password);
			read_buf(buf, "OJ_LANG_SET", oj_lang_set);
			
			read_int(buf, "OJ_REDISENABLE", &oj_redis);
                        read_buf(buf, "OJ_REDISSERVER", oj_redisserver);
                        read_int(buf, "OJ_REDISPORT", &oj_redisport);
                        read_buf(buf, "OJ_REDISAUTH", oj_redisauth);
                        read_buf(buf, "OJ_REDISQNAME", oj_redisqname);
                        read_int(buf, "OJ_TURBO_MODE", &turbo_mode);


		}
#ifdef _mysql_h
		sprintf(query,
				"SELECT solution_id FROM solution WHERE language in (%s) and result<2 and MOD(solution_id,%d)=%d ORDER BY result ASC,solution_id ASC limit %d",
				oj_lang_set, oj_tot, oj_mod, max_running * 2);
#endif
		sleep_tmp = sleep_time;
		//	fclose(fp);
	}
}


 
//当有代评测提交，并且进程数允许的情况下，创建新的子进程调用该评测函数
//输入：代评测提交的solution_id, 子进程在ID[]中的保存位置 i 
void run_client(int runid, int clientid) {
	char buf[BUFFER_SIZE], runidstr[BUFFER_SIZE];
	//在Linux系统中，Resouce limit指在一个进程的执行过程中，它所能得到的资源的限制，
	//比如进程的core file的最大值，虚拟内存的最大值等 ，这是运行时间，内存大小实现的关键 
	/*
	结构体中 rlim_cur是要取得或设置的资源软限制的值，rlim_max是硬限制
	这两个值的设置有一个小的约束：	
	1） 任何进程可以将软限制改为小于或等于硬限制
	2）任何进程都可以将硬限制降低，但普通用户降低了就无法提高，该值必须等于或大于软限制
	3） 只有超级用户可以提高硬限制
	
	setrlimit(int resource,const struct rlimit rlptr);返回：若成功为0，出错为非0	
	RLIMIT_CPU：CPU时间的最大量值（秒），当超过此软限制时向该进程发送SIGXCPU信号
	RLIMIT_FSIZE:可以创建的文件的最大字节长度，当超过此软限制时向进程发送SIGXFSZ
	*/
	struct rlimit LIM;
	LIM.rlim_max = 800;
	LIM.rlim_cur = 800;
	setrlimit(RLIMIT_CPU, &LIM); //cpu运行时间限制 

	LIM.rlim_max = 180 * STD_MB;
	LIM.rlim_cur = 180 * STD_MB;
	setrlimit(RLIMIT_FSIZE, &LIM); //可文件大小限制，防止恶意程序的吗？

	LIM.rlim_max = STD_MB << 11; //左移11 STD_MB是2^20 MB 2^11MB 2GB机器起码的2GB虚拟内存？
	LIM.rlim_cur = STD_MB << 11;
	setrlimit(RLIMIT_AS, &LIM); //最大运行的虚拟内存大小限制

	LIM.rlim_cur = LIM.rlim_max = 200;
	setrlimit(RLIMIT_NPROC, &LIM); //每个实际用户ID所拥有的最大子进程数，这些都是为了防止恶意程序的吧？？

	//buf[0]=clientid+'0'; buf[1]=0;
	sprintf(runidstr, "%d", runid); //转换成字符？还是字符串？
	sprintf(buf, "%d", clientid); 

	//write_log("sid=%s\tclient=%s\toj_home=%s\n",runidstr,buf,oj_home);
	//sprintf(err,"%s/run%d/error.out",oj_home,clientid);
	//freopen(err,"a+",stderr);
	
	//返回值：如果执行成功则函数不会返回, 执行失败则直接返回-1, 失败原因存于errno 中. 
	//execl()其中后缀"l"代表list也就是参数列表的意思，第一参数path字符指针所指向要执行的文件路径， 
	//接下来的参数代表执行该文件时传递的参数列表：argv[0],argv[1]... 最后一个参数须用空指针NULL作结束。 
	//	执行/bin目录下的ls, 第一参数为程序名ls, 第二个参数为"-al", 第三个参数为"/etc/passwd"
	//execl("/bin/ls", "ls", "-al", "/etc/passwd", (char *) 0);
	//这里第一个参数为程序名称judge_client，第二个参数为代评测题目id, 第三个为本进程pid保存位置，第四个参数为oj目录
	//默认/home/judge,第五个参数为“debug”

	if (!DEBUG)
		execl("/usr/bin/judge_client", "/usr/bin/judge_client", runidstr, buf,
				oj_home, (char *) NULL);
	else
		execl("/usr/bin/judge_client", "/usr/bin/judge_client", runidstr, buf,
				oj_home, "debug", (char *) NULL);


	//exit(0);
}


//执行sql语句成功返回1，否则返回0 
//并且关闭是否conn，它在init里初始化开始的
#ifdef _mysql_h
int executesql(const char * sql) {

	if (mysql_real_query(conn, sql, strlen(sql))) {
		if (DEBUG)
			write_log("%s", mysql_error(conn));
		sleep(20);
		conn = NULL;
		return 1;
	} else
		return 0;
}
#endif

#ifdef _mysql_h
int init_mysql() {
	if (conn == NULL) {
		conn = mysql_init(NULL);		// init the database connection
		/* connect the database */
		const char timeout = 30;
		mysql_options(conn, MYSQL_OPT_CONNECT_TIMEOUT, &timeout);

		if (!mysql_real_connect(conn, host_name, user_name, password, db_name,
				port_number, 0, 0)) {
			if (DEBUG)
				write_log("%s", mysql_error(conn));
			sleep(2);
			return 1;
		} else {
			return 0;
		}
	} else {
		return executesql("set names utf8");
	}
}
#endif
FILE * read_cmd_output(const char * fmt, ...) {
	char cmd[BUFFER_SIZE];

	FILE * ret = NULL;
	va_list ap;

	va_start(ap, fmt);
	vsprintf(cmd, fmt, ap);
	va_end(ap);
	//if(DEBUG) printf("%s\n",cmd);
	ret = popen(cmd, "r");

	return ret;
}
int read_int_http(FILE * f) {
	char buf[BUFFER_SIZE];
	fgets(buf, BUFFER_SIZE - 1, f);
	return atoi(buf);
}
bool check_login() {
	const char * cmd =
			"wget --post-data=\"checklogin=1\" --load-cookies=cookie --save-cookies=cookie --keep-session-cookies -q -O - \"%s/admin/problem_judge.php\"";
	int ret = 0;

	FILE * fjobs = read_cmd_output(cmd, http_baseurl);
	ret = read_int_http(fjobs);
	pclose(fjobs);

	return ret > 0;
}
void login() {
	if (!check_login()) {
		char cmd[BUFFER_SIZE];
		sprintf(cmd,
				"wget --post-data=\"user_id=%s&password=%s\" --load-cookies=cookie --save-cookies=cookie --keep-session-cookies -q -O - \"%s/login.php\"",
				http_username, http_password, http_baseurl);
		system(cmd);
	}

}
int _get_jobs_http(int * jobs) {
	login();
	int ret = 0;
	int i = 0;
	char buf[BUFFER_SIZE];
	const char * cmd =
			"wget --post-data=\"getpending=1&oj_lang_set=%s&max_running=%d\" --load-cookies=cookie --save-cookies=cookie --keep-session-cookies -q -O - \"%s/admin/problem_judge.php\"";
	FILE * fjobs = read_cmd_output(cmd, oj_lang_set, max_running, http_baseurl);
	while (fscanf(fjobs, "%s", buf) != EOF) {
		//puts(buf);
		int sid = atoi(buf);
		if (sid > 0)
			jobs[i++] = sid;
		//i++;
	}
	pclose(fjobs);
	ret = i;
	while (i <= max_running * 2)
		jobs[i++] = 0;
	return ret;
}

//功能：取得待评测题目信息到jobs数组
//输入：int * jobs :保存solution_id/runid
//输出：如果查询成功则返回：要评测题目数量 
//如果查询待判题目不成功则返回0

#ifdef _mysql_h
int _get_jobs_mysql(int * jobs) {
	//mysql.h
	//如果查询数据包括二进制或者更快速度 用这个
	//如果执行成功，返回0；不成功非0
	if (mysql_real_query(conn, query, strlen(query))) {
		if (DEBUG)
			write_log("%s", mysql_error(conn));
		sleep(20);
		return 0;
	}
	//mysql.h
	//返回具有多个结果的MYSQL_RES结果集合。如果出现错误，返回NULL
	//具体参见百度
	res = mysql_store_result(conn);
	int i = 0;
	int ret = 0;
	//遍历结果集mysql_fetch_row()
	while (res!=NULL && (row = mysql_fetch_row(res)) != NULL) {
		jobs[i++] = atoi(row[0]);
	}

	if(res!=NULL&&!executesql("commit")){
		mysql_free_result(res);                         // free the memory
		res=NULL;
	}                        
	else i=0;
	ret = i; //要评测jobs末端 如 0 1 2 有数据，则i=3代表数据
	while (i <= max_running * 2) 
		jobs[i++] = 0; //设定的最大工作数目为max_running*2，将0-8置位0共9个 max_running*2+1数组开这么大
	return ret;
}
#endif
int _get_jobs_redis(int * jobs){
        int ret=0;
        const char * cmd="redis-cli -h %s -p %d -a %s --raw rpop %s";
        while(ret<=max_running){
                FILE * fjobs = read_cmd_output(cmd,oj_redisserver,oj_redisport,oj_redisauth,oj_redisqname);
                if(fscanf(fjobs,"%d",&jobs[ret])==1){
                        ret++;
                        pclose(fjobs);
                }else{
                        pclose(fjobs);
                        break;
                }

        }
        int i=ret;
        while (i <= max_running * 2)
                jobs[i++] = 0;
        if(DEBUG) printf("redis return %d jobs",ret);
        return ret;
}




int get_jobs(int * jobs) {
	//web和core默认连接方式：数据库，web插入solution,core轮训/更新solution-result，web轮训solution-result
	if (http_judge) {
		return _get_jobs_http(jobs);
	} else {		
		if(oj_redis){
                        return _get_jobs_redis(jobs);
                }else{
#ifdef _mysql_h
                        return _get_jobs_mysql(jobs);
#else
			return 0;
#endif
                }
	}
}

//更新初始化solution表格
//更新成功返回1；否则0
// 疑问：OJ_CI为2，and result < 2这句怎么都是不成立，这个Sql语句怎么都不会执行成功才对啊 
//用limit 1加了一层保障。避免where 条件出现异常时，错误更新影响太多。 
//不知道php初始写多少，但是调用给的参数为2啊，不懂！！！！ 

#ifdef _mysql_h
bool _check_out_mysql(int solution_id, int result) {
	char sql[BUFFER_SIZE];
	sprintf(sql,
			"UPDATE solution SET result=%d,time=0,memory=0,judgetime=NOW() WHERE solution_id=%d and result<2 LIMIT 1",
			result, solution_id);
	//执行sql语句，成功返回0；否则非0
	if (mysql_real_query(conn, sql, strlen(sql))) {
		syslog(LOG_ERR | LOG_DAEMON, "%s", mysql_error(conn));
		return false;
	} else {
		//影响行数，更新数大于0，执行成功，返回1，否则0
		if (conn!=NULL&&mysql_affected_rows(conn) > 0ul)
			return true;
		else
			return false;
	}

}
#endif

bool _check_out_http(int solution_id, int result) {
	login();
	const char * cmd =
			"wget --post-data=\"checkout=1&sid=%d&result=%d\" --load-cookies=cookie --save-cookies=cookie --keep-session-cookies -q -O - \"%s/admin/problem_judge.php\"";
	int ret = 0;
	FILE * fjobs = read_cmd_output(cmd, solution_id, result, http_baseurl);
	fscanf(fjobs, "%d", &ret);
	pclose(fjobs);

	return ret;
}

//初始更新solution表
//依据参数不同执行不同的更新函数
bool check_out(int solution_id, int result) {
        if(oj_redis||oj_tot>1) return true;
	if (http_judge) {
		return _check_out_http(solution_id, result);
	} else{
#ifdef _mysql_h
		return _check_out_mysql(solution_id, result);
#else
		return 0;
#endif
	}

}
int work() {
//      char buf[1024];
	static int retcnt = 0; //统计 已经 完成评测次数 
	int i = 0;
	static pid_t ID[100]; //short类型的宏定义，进程表中的索引项，进程号；保存正在执行的子进程pid 
	static int workcnt = 0; //统计 现用 judge_client进程数量 
	int runid = 0; //solution_id，测试运行编号
	int jobs[max_running * 2 + 1]; //max_running 从judge.conf获取，一般为4，这里设置为工作目录：9
	pid_t tmp_pid = 0;

	//for(i=0;i<max_running;i++){
	//      ID[i]=0;
	//}

	//sleep_time=sleep_tmp;
	/* get the database info */
	if (!get_jobs(jobs)){ //如果读取失败或者要评测题目数量为0，jobs[]被置为：1001，1002，0，...0；默认9位
		return 0;
	}
	/* exec the submit */
	//遍历评测每个solution_id的题目，只负责把所以题目全部投入到新的评判进程里
	//不管是否评测完成
	for (int j = 0; jobs[j] > 0; j++) {
		runid = jobs[j];//读取solution_id，待评测提交题目id 
		//老式并发处理中，默认oj_tot 为 1 oj_mod 为0，在init_sql_conf中设置 所以无用 
		
		if (runid % oj_tot != oj_mod)
			continue;
		if (DEBUG) //调试用默认0 无用 
			write_log("Judging solution %d", runid);
		//workcnt 为static 变量，相当于死锁，统计现用run_client进程 数目 
		//本if 等待可用 子进程，并且用 i 腾出保存 新子进程的位置 
		if (workcnt >= max_running) {           // if no more client can running
			//如果达到了可用最大进程数目，那么等待一个子进程结束
			//waitpid，参考linux 下 c 语言编程下的 进程管理 
			//waitpid()会暂时停止目前进程的执行，直到有信号来到或子进程结束
			//pid_t waitpid(pid_t pid,int * status,int options);
			//pid=-1 代表任意子进程；status 取回子进程识别码，这里不需要所以NULL; 
			//参数options提供了一些额外的选项来控制waitpid，比如不等待继续执行，这里0代表不使用，进程挂起
			//如果 有子进程已经结束，那么执行到这里的时候会直接跳过，子进程也会由僵尸进程释放	
			//返回结束的子进程pid	 
			tmp_pid = waitpid(-1, NULL, 0);     // wait 4 one child exit
			for (i = 0; i < max_running; i++){     // get the client id
				if (ID[i] == tmp_pid){
					workcnt--;  //子进程结束了个，那么现用judge_client数量减一 
					retcnt++;   //评测完成数加1
					ID[i] = 0; //清除保存在 ID[]里的已经结束的子进程信息
					break; // got the client id
				}
			}
		} else {                                             // have free client

			for (i = 0; i < max_running; i++)     // find the client id
				if (ID[i] == 0)
					break;    // got the client id
		}
		if(i<max_running){
			//其实这里worknct<max_running 一定成立，除非waitpid()出错 
			//check_out:更新初始化表，但是怎么都不该执行成功才对的啊，为什么还能成功呢
			//如果可以开始新的子进程进行评测 
		
			if (workcnt < max_running && check_out(runid, OJ_CI)) {
				workcnt++;//正运行子进程数目加1----这里是不是太早了，子进程创建一定能成功？？？？？
						  //应该在子进程里更新这个数值吧 
				ID[i] = fork();                                   // start to fork
				//创建子进程 ，将子进程pid返回给父进程，将0返回给子进程  // start to fork
                //这句写的觉得难理解，父进程会将其更新为新进程pid
				//子进程呢，创建之初会更新为0，那到底是多少？？？？？？？
				//按照程序，子进程会完整复制父进程的代码，数据，堆栈
				//那么如果是父进程在执行那么ID[i] 不为0而是子进程pid
				//如果是子进程的在执行，那么数据段又是ID[i]为0？？？？
				//那static 的作用呢
				if (ID[i] == 0) {  //如果成立，那么代表是在执行子进程代码，执行run_judge_client
					if (DEBUG)
						write_log("<<=sid=%d===clientid=%d==>>\n", runid, i);
					run_client(runid, i);    // if the process is the son, run it
					//在子进程里更新ID[0]=pid  // if the process is the son, run it
					exit(0);
					//子进程执行完毕退出0，父进程不会执行这段if ，在run_client里进程会跳转到execl(judge_client)
				    //执行成功不返回，不成功返回非0，保存在erro里，那么这里又是怎么执行到的，子进程如何退出的？？？？？？ 
				}

			} else { //理论上，在上个if里已经保证了这里为ID[i] = 0，这里估计是为了进一步保证
				ID[i] = 0;
			}
		}
	}
	//把本次轮训到的代评测题目全部投入评测后 
	//在非挂起等待子进程的结束，如果有子进程评测完成结束 
	//在上个的for里，当可用进程没有的时候，那么就必须等其中一个进程结束，那么才能继续执行，哪怕在for里已经有 
	// 子进程结束是僵尸进程了，只要workcnt<max_running,那么就也不处理子僵尸进程的回收问题，而是优先投入新的子进程
	//进行评测
	//那么子僵尸进程 谁来回收，何时回收，怎么回收，总不能等可用的全成了僵尸进程，在for里用到的时候在进行回收吧 
	//如果可用进程数开的特别大，而一直没有用户提交，那岂不是，运行一段时间后，系统肯定会一直有max_running个进程的
	//资源被占用，而且大大大99%部分都是死的子僵尸进程，for只是用几个收几个，而不管也没法管其他的，因为for只有当
	//有评测任务的时候才会执行到，大部分没有用户提交程序评测的轮询时间段里，不顺手回收下，岂不可惜！！！ 
	 
	//所以就是while()要完成的任务，父进程执行到这里的时候，扫一眼是否有待回收子僵尸进程，有就 顺手回收一个；
	// 因为不知道有多少待回收的，什么时候要回收；所以只且只能在这个轮询时间段里回收一个 
	//这个while，纯粹是顺手牵羊行为，当然也有更新评测完成数量的重要任务~~~ 
	/*
	如果使用了WNOHANG参数调用waitpid，即使没有子进程退出，它也会立即返回，不会像wait那样永远等下去
	1、当正常返回的时候，waitpid返回收集到的子进程的进程ID；
          2、如果设置了选项WNOHANG，而调用中waitpid发现没有已退出的子进程可收集，则返回0；
          3、如果调用中出错，则返回-1，这时errno会被设置成相应的值以指示错误所在；
	*/
	while ((tmp_pid = waitpid(-1, NULL, 0)) > 0) {
		for (i = 0; i < max_running; i++){     // get the client id
			if (ID[i] == tmp_pid){
			
				workcnt--;
				retcnt++;
				ID[i] = 0;
				break; // got the client id
			}
		}
		printf("tmp_pid = %d\n", tmp_pid);
	}
	//释放数据库资源 
	//这里commit的调用，不知道是为了关闭conn，还是数据库不支持自动commit
	//还是彻底缩小日志，不给机会rollback，待学习？？？？？？

	if (!http_judge) {
#ifdef _mysql_h
		if(res!=NULL) {
			mysql_free_result(res);                         // free the memory
			res=NULL;
		}
		executesql("commit");
#endif
	}
	if (DEBUG && retcnt)
		write_log("<<%ddone!>>", retcnt);
	//free(ID);
	//free(jobs);
	return retcnt;
}

int lockfile(int fd) {
	struct flock fl;
	fl.l_type = F_WRLCK;
	fl.l_start = 0;
	fl.l_whence = SEEK_SET;
	fl.l_len = 0;
	return (fcntl(fd, F_SETLK, &fl));
}

int already_running() {
	int fd;
	char buf[16];
	//只读打开文件lock_file,若不存在则建立文件
	fd = open(lock_file, O_RDWR | O_CREAT, LOCKMODE);

	if (fd < 0) {
		//无法读取文件
		syslog(LOG_ERR | LOG_DAEMON, "can't open %s: %s", LOCKFILE,
				strerror(errno));
		exit(1);
	}
	if (lockfile(fd) < 0) {
		if (errno == EACCES || errno == EAGAIN) {
			close(fd);
			return 1;
		}
		syslog(LOG_ERR | LOG_DAEMON, "can't lock %s: %s", LOCKFILE,
				strerror(errno));
		exit(1);
	}
	ftruncate(fd, 0);
	sprintf(buf, "%d", getpid());
	write(fd, buf, strlen(buf) + 1);
	return (0);
}
int daemon_init(void)

{
	pid_t pid;

	if ((pid = fork()) < 0)
		return (-1);

	else if (pid != 0)
		exit(0); /* parent exit */

	/* child continues */

	setsid(); /* become session leader */

	chdir(oj_home); /* change working directory */

	umask(0); /* clear file mode creation mask */

	close(0); /* close stdin */
	close(1); /* close stdout */
	
	close(2); /* close stderr */
	
	int fd = open( "/dev/null", O_RDWR );
	dup2( fd, 0 );
	dup2( fd, 1 );
	dup2( fd, 2 );
	if ( fd > 2 ){
		close( fd );
	}

	return (0);
}
void turbo_mode2(){
#ifdef _mysql_h
	if(turbo_mode==2){
			char sql[BUFFER_SIZE];
			sprintf(sql," CALL `sync_result`();");
			if (mysql_real_query(conn, sql, strlen(sql)));
	}
#endif

}
int main(int argc, char** argv) {
	DEBUG = (argc > 2);
	ONCE = (argc > 3);
	if (argc > 1)
		strcpy(oj_home, argv[1]);
	else
		strcpy(oj_home, "/home/judge");
	chdir(oj_home);    // change the dir

	sprintf(lock_file,"%s/etc/judge.pid",oj_home);
	if (!DEBUG)
		daemon_init(); //创建一个daemon守护进程
	if ( already_running()) {
		syslog(LOG_ERR | LOG_DAEMON,
				"This daemon program is already running!\n");
		printf("%s already has one judged on it!\n",oj_home);
		return 1;
	}
	if(!DEBUG)
		system("/sbin/iptables -A OUTPUT -m owner --uid-owner judge -j DROP");
//	struct timespec final_sleep;
//	final_sleep.tv_sec=0;
//	final_sleep.tv_nsec=500000000;
#ifdef _mysql_h
	init_mysql_conf();	// set the database info
#endif
	signal(SIGQUIT, call_for_exit);// 输入Quit Key的时候（CTRL+\）发送给所有Foreground Group的进程  
	signal(SIGKILL, call_for_exit);// 无法处理和忽略。中止某个进程  
	signal(SIGTERM, call_for_exit);// 请求中止进程，kill命令缺省发送  
 
	int j = 1;
	int n = 0;
	while (1) {			// start to run
		//这个while的好处在于，只要一有任务就抓紧占用系统优先把所以任务处理完成，哪怕会空循环几次的可能存在
		//但是没有任务后，就会进入到“懒散”的 休息sleep(time)后再轮询是不是有任务，释放系统的资源，避免Damon一直死循环占用系统 
		while (j && (http_judge
#ifdef _mysql_h
			 || !init_mysql()
#endif
		)) {

			j = work();//如果读取失败或者没有要评测的数据，那么返回0，利用那么有限的几个进程来评测无限的任务量 

			n+=j;
			if(turbo_mode==2&&(n>max_running*10||j<max_running)){
				turbo_mode2();
				n=0;
			}
	

			if(ONCE) break;
		}
		turbo_mode2();
		if(ONCE) break;
		sleep(sleep_time);
		j = 1;
	}
	return 0;
}
