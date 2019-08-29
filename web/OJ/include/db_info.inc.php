<?php
/**
 * This file is modified
 * by yybird @2016.05.25
 * by D_Star @2016.08.23
 * by jnxxhzz @2019.03.15
 **/
?>
<?php @session_start();

ini_set("display_errors","Off");
require_once($_SERVER['DOCUMENT_ROOT']."/OJ/include/static.php");

//if(date('H')<5||date('H')>21||isset($_GET['dark'])) $OJ_CSS="dark.css";
if (isset($_SESSION['OJ_LANG'])) $OJ_LANG=$_SESSION['OJ_LANG'];

//for normal install
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
if($mysqli->connect_errno)
    die('Could not connect: ' . $mysqli->connect_error);
// use db
$mysqli->query("set names utf8");
//if(!$OJ_SAE)mysqli_set_charset("utf8");
//sychronize php and mysql server
date_default_timezone_set("PRC");
$mysqli->query("SET time_zone ='+8:00'");
// 管理权限
function HAS_PRI($pri_str){  // if has privilege
    //non-realtime
    //return $_SESSION[$pri_str];
    if (strpos($pri_str, 'edit_default') !== false) {
        $pri_str = 'edit_default_problem';
    }
    if (strpos($pri_str, 'see_hidden_default') !== false) {
        $pri_str = 'see_hidden_default_problem';
    }

    //realtime checking
    global $mysqli;
    if(isset($_SESSION['user_id'])){
        $sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".$mysqli->real_escape_string($_SESSION['user_id'])."'";
        $res=$mysqli->query($sql);
        while($group_name=$res->fetch_array()['rightstr']){
            $rs=$mysqli->query("SELECT * FROM privilege_distribution WHERE group_name='$group_name'");
            $arr=$rs->fetch_array();
            if($arr[$pri_str]){
                return true;
            }
        }
    }
    return false;
}
/*Count the hit time START*/
//if($_SERVER['REMOTE_ADDR']!='127.0.0.1') {
    $user_id="";
    if(isset($_SESSION['user_id'])) $user_id=$_SESSION['user_id'];
    $require_path=$mysqli->real_escape_string($_SERVER['REQUEST_URI']);
    $sql="INSERT INTO hit_log (ip, time, path, user_id) VALUES ('{$_SERVER['REMOTE_ADDR']}', NOW(), '$require_path', '$user_id')";
    $mysqli->query($sql);
//}
/*Count the hit time END*/
?>
