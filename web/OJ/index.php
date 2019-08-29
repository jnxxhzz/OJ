<?php
  /**
   * This file is modified
   * by jnxxhzz
   * @2019.05.19
  **/
?>

<?php

function formatTimeLength($length) {
    $result = "";
    $day = floor($length/86400); $length%=86400;
    $hour = floor($length/3600); $length%=3600;
    $minute = floor($length/60); $length%=60;
    $second = $length;
    $result .= $day." Day".($day>1?"s":"")." ";
    $result .= $hour." Hour".($hour>1?"s":"")." ";
    $result .= $minute." Minute".($minute>1?"s":"")." ";
    $result .= $second." Second".($second>1?"s":"")." ";
    return $result;
}

////////////////////////////Common head
$cache_time=10;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title= "Welcome To Online Judge";
///////////////////////////MAIN 
$news_id = array();
$news_title = array();
$news_importance = array();
$i = 0;
$sql = <<<SQL
  SELECT
    news_id,
    title,
    importance
  FROM
    `news`
  WHERE
    `defunct` != 'Y'
  ORDER BY
    `importance` DESC,
    `time` DESC
SQL;

$result = $mysqli->query($sql);
for (; $row=$result->fetch_array(); ++$i) {
  $news_id[$i]=$row['news_id'];
  $news_title[$i] = $row['title'];
  if($row['importance']==10)$news_title[$i].="&nbsp;&nbsp;&nbsp;&nbsp;<b>[置顶]</b>";
  $news_title[$i].="&nbsp&nbsp<i id='news-load-icon-{$row['news_id']}' style='display:none;' class='am-icon-spinner am-icon-pulse'></i>";
  $news_importance[$i] = $row['importance'];
}
/* 获取公告 end */
/* 获取比赛 start */
$keyword="";
if(isset($_POST['keyword'])){
    $keyword=$mysqli->real_escape_string($_POST['keyword']);
}
$sql="SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC limit 5";
// $sql="select * from contest left join (select * from privilege where rightstr like 'm%') p on concat('m',contest_id)=rightstr where contest.defunct='N' and contest.title like '%$keyword%'  order by contest_id desc limit 1000;";
$result=$mysqli->query($sql);
$view_contest=Array();
$i=0;
while ($row=$result->fetch_object()){
    $view_contest[$i][0]= $row->contest_id;
    $view_contest[$i][1]= "<a href='contest.php?cid=$row->contest_id'>$row->title</a>";
    $start_time=strtotime($row->start_time);
    $end_time=strtotime($row->end_time);
    $now=time();
    $length=$end_time-$start_time;
    $left=$end_time-$now;
    
    if ($now>$end_time) { // past
        $view_contest[$i][2]= "<span style='color: #9e9e9e;'>$MSG_Ended@$row->end_time</span>";
    } else if ($now<$start_time){ // pending
        $view_contest[$i][2]= "<span style='color: #03a9f4;'>$MSG_Start@$row->start_time&nbsp;";
        $view_contest[$i][2].= "$MSG_TotalTime ".formatTimeLength($length)."</span>";
    } else { // running
        $view_contest[$i][2]= "<span style='color: #ff5722;'> $MSG_Running&nbsp;";
        $view_contest[$i][2].= "$MSG_LeftTime ".formatTimeLength($left)." </span>";
    }
    $type = "<span style='color: green;'>Public</span>";
    if($row->private) $type = "<span style='color: dodgerblue;'>Password</span>";
    if($row->user_limit=="Y") $type = "<span style='color: #f44336;'>Special</span>";
    if($row->practice) $type = "<span style='color: #009688;'>Practice</span>";
    $view_contest[$i][4]= $type;
    $view_contest[$i][6]=$row->user_id;
    $i++;
}
$result->free();
/* 获取比赛 end */

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/index.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>
