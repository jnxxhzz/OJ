<?php
/*
数据库
CREATE TABLE `online` (
  `hash` varchar(32) collate utf8_unicode_ci NOT NULL,
  `ip` varchar(20) character set utf8 NOT NULL default '',
  `ua` varchar(255) character set utf8 NOT NULL default '',
  `refer` varchar(255) collate utf8_unicode_ci default NULL,
  `lastmove` int(10) NOT NULL,
  `firsttime` int(10) default NULL,
  `uri` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`hash`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 */
/**
 * 判定多久未响应的用户为已经离开的用户
 * @var int
 */

define('ONLINE_DURATION', 600);

/**
 * 
 * 本类用来对在线用户进行统计
 * 
 * @package online
 * @author freefcw
 * @link http://www.missway.cn
 * 
 */
class online{
	/**
	 * database connect
	 * @var databse link
	 */
	protected $db;
	/**
	 * current user ip
	 * @var string
	 */
	protected $ip;
	/**
	 * current user agent
	 * @var string
	 */
	protected $ua;
	/**
	 * cureent user visit web uri
	 * @var string
	 */
	protected $uri;
	/**
	 * session id
	 * @var string
	 */
	protected $hash;
	/**
	 * cureent user refer uri
	 * @var string
	 */
	protected $refer;
	//can add function:
	//example click number count
	protected $click;

	/**
	 * construct fuction,init database link
	 * @return void
	 */
	function __construct()
	{
		$this->ip = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
      
      
         if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){

                    $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    
                    $tmp_ip=explode(',',$REMOTE_ADDR);

                    $this->ip =$tmp_ip[0];

        }

		$this->ua = $msyqli->real_escape_string(htmlspecialchars($_SERVER['HTTP_USER_AGENT']));
		$this->uri = $mysqli->real_escape_string($_SERVER['PHP_SELF']);
		if(isset($_SERVER['HTTP_REFERER'])){
			$this->refer = $mysqli->real_escape_string(htmlspecialchars($_SERVER['HTTP_REFERER']));
	    }
		$this->hash = $mysqli->real_escape_string(session_id());
		//$this->db = new mysqli(DBHOST, DBUSER, DBPASSWORD, )

		//check user existed!
		if($this->exist()){
			//update databse
			$this->update();
		}else if(!(strstr($this->ua,"bot")||strstr($this->ua,"spider"))){
			//if none, add this record
			$this->addRecord();
		}
		//clean the user who leave our site 
		$this->clean();
	}

	/**
	 * 
	 * return all record!
	 * 
	 * @return array
	 */
	function getAll()
	{
		$ret = array();
		
		$sql = 'SELECT * FROM online';
		$res = $mysqli->query($sql);
		//$sql = 'ALTER TABLE `jol`.`online` ENGINE = MEMORY';
		//$res = $mysqli->query($sql);
		if($res ){
			while($rt = $res->fetch_object()) $ret[] = $rt;
			->free();
		}
		return $ret;
	}
	/**
	 * 
	 * return specfy record
	 * @var string ip
	 * @return object 
	 */
	function getRecord($ip)
	{
		$sql = "SELECT * FROM online WHERE ip = '$ip'";
		$res = $mysqli->query($sql);
		if($res->num_rows){
			$ret = $res->fetch_object();
		}else{
			return false;
		}
		->free();
		return $ret;
	}
	
	/**
	 * 
	 * get total count
	 * 
	 * @return int
	 */
	function get_num()
	{
		$sql = 'SELECT count(ip) as nums FROM online';
		$res = $mysqli->query($sql);
		$ret = 0;
		if($res){
			$ret = $res->fetch_object();
			$ret = $ret->nums;
			->free();
	    }
		return $ret;
	}
	/**
	 * check the record exist
	 *
	 * @return boolean 
	 */
	function exist()
	{
		$sql = "SELECT * FROM online WHERE hash = '$this->hash'";
		$res = $mysqli->query($sql);
		if($res&&$res->num_rows == 0)
			return false;
		else
			return true;

	}
	/**
	 * add a record
	 *
	 * @return void
	 */
	function addRecord()
	{
		$now = time();
		$sql = "INSERT INTO online(hash, ip, ua, uri, refer, firsttime, lastmove)
				VALUES ('$this->hash', '$this->ip', '$this->ua', '$this->uri', '$this->refer', '$now', '$now')";
		$mysqli->query($sql);
	}

	/**
	 * update a record
	 *
	 * @return void
	 */
	function update()
	{
		$sql = "UPDATE online
				SET
					ua = '$this->ua',
					uri = '$this->uri',
					refer = '$this->refer',
					lastmove = '".time()."',
					ip = '$this->ip'
				WHERE
					hash = '$this->hash'
				";
		$mysqli->query($sql);
	}
	/**
	 * clean the duration user
	 *
	 * @return void
	 */
	function clean()
	{
		$sql = 'DELETE FROM online WHERE lastmove<'.(time()-ONLINE_DURATION);
		$mysqli->query($sql);
	}
}
