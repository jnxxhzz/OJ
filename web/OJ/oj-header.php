<?php function checkcontest($MSG_CONTEST){
		require_once("./include/db_info.inc.php");
      $now=strftime("%Y-%m-%d %H:%M",time());
		$sql="SELECT count(*) FROM `contest` WHERE `end_time`>'$now' AND `defunct`='N'";
		$result=$mysqli->query($sql);
		$row=$result->fetch_row();
		if (intval($row[0])==0) $retmsg=$MSG_CONTEST;
		else $retmsg=$row[0]."<span class=red>&nbsp;$MSG_CONTEST</span>";
		$result->free();
		return $retmsg;
	}
	
	 $OJ_FAQ_LINK="faqs.php";
   if(isset($OJ_LANG)){
                require_once("./lang/$OJ_LANG.php");
                if(file_exists("./faqs.$OJ_LANG.php")){
                        $OJ_FAQ_LINK="faqs.$OJ_LANG.php";
                }
    }

	

	if($OJ_ONLINE){
		require_once('./include/online.php');
		$on = new online();
	}

	$url=basename($_SERVER['REQUEST_URI']);
	$view_marquee_msg=file_get_contents($OJ_SAE?"saestor://web/msg.txt":"./admin/msg.txt");
   
   
   
	require("template/".$OJ_TEMPLATE."/header.php");
?>

