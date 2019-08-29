<?php
/**
 * This file is modified
 * by yybird
 * @2016.06.28
 **/
?>
<?php

function pwGen($password,$md5ed=False)
{
    if (!$md5ed) $password=md5($password);
    $salt = sha1(rand());
    $salt = substr($salt, 0, 4);
    $hash = base64_encode( sha1($password . $salt, true) . $salt );
    return $hash;
}

function pwCheck($password,$saved) { // 分别问当前密码、数据库中保存的密码
    if (isOldPW($saved)){ // 是否为旧版密码（旧版密码使用MD5加密）
        $mpw = md5($password);
        if ($mpw==$saved) return True;
        else return False;
    }
    $svd=base64_decode($saved);
    $salt=substr($svd,20);
    $hash = base64_encode( sha1(md5($password).$salt, true) . $salt );
    if (strcmp($hash,$saved)==0) return True;
    else return False;
}

function isOldPW($password)
{
    for ($i=strlen($password)-1;$i>=0;$i--)
    {
        $c = $password[$i];
        if ('0'<=$c && $c<='9') continue;
        if ('a'<=$c && $c<='f') continue;
        if ('A'<=$c && $c<='F') continue;
        return False;
    }
    return True;
}

function is_valid_user_name($user_name){
    $len=strlen($user_name);
    for ($i=0;$i<$len;$i++){
        if (
            ($user_name[$i]>='a' && $user_name[$i]<='z') ||
            ($user_name[$i]>='A' && $user_name[$i]<='Z') ||
            ($user_name[$i]>='0' && $user_name[$i]<='9') ||
            $user_name[$i]=='_'||
            $user_name[$i]=='-'
        );
        else return false;
    }
    return true;
}

function sec2str($sec){
    return sprintf("%02d:%02d:%02d",$sec/3600,$sec%3600/60,$sec%60);
}

function is_running($cid){
    require_once("./include/db_info.inc.php");
    global $mysqli;
    $now=strftime("%Y-%m-%d %H:%M",time());
    $sql="SELECT count(*) FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<'$now' AND `end_time`>'$now'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_array();
    $cnt=intval($row[0]);
    $result->free();
    return $cnt>0;
}
//not include practice
function is_in_running_contest($pid) {
    require_once("./include/db_info.inc.php");
    global $mysqli;
    $sql = "
SELECT 1 FROM contest_problem
INNER JOIN contest ON
contest.contest_id = contest_problem.contest_id
AND contest.start_time < NOW()
AND contest.end_time > NOW()
AND contest.practice = 0
WHERE contest_problem.problem_id = $pid
";
    return $mysqli->query($sql)->num_rows;
}
function is_running_and_not_practice($cid) {
    require_once("./include/db_info.inc.php");
    global $mysqli;
    $now=strftime("%Y-%m-%d %H:%M",time());
    $sql="SELECT count(*) FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<'$now' AND `end_time`>'$now' AND practice = 0";
    $result=$mysqli->query($sql);
    $row=$result->fetch_array();
    $cnt=intval($row[0]);
    $result->free();
    return $cnt>0;
}
function check_ac($cid,$pid){
    require_once("./include/db_info.inc.php");
    global $mysqli;
    $sql="SELECT count(*) FROM `solution` WHERE `contest_id`='$cid' AND `num`='$pid' AND `result`='4' AND `user_id`='".$_SESSION['user_id']."'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_array();
    $ac=intval($row[0]);
    $result->free();
    if ($ac>0) return "<font color=green>Y</font>";
    $sql="SELECT count(*) FROM `solution` WHERE `contest_id`='$cid' AND `num`='$pid' AND `user_id`='".$_SESSION['user_id']."'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_array();
    $sub=intval($row[0]);
    $result->free();
    if ($sub>0) return "<font color=red>N</font>";
    else return "";
}

function RemoveXSS($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
    
    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
        
        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }
    
    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);
    
    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}


function canSeeSource($sid) {
    
    global $OJ_AUTO_SHARE;
    global $mysqli;
    /* 获取solution信息 start */
    $sql="SELECT * FROM `solution` WHERE `solution_id`='".$sid."'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_object();
    $pid = $row->problem_id;
    $cid = $row->contest_id;
    $result->free();
    /* 获取solution信息 end */
    
    
    $irc = false; // in running contest
    $sql = "SELECT DISTINCT(contest_id) AS cid FROM contest_problem WHERE problem_id='$pid'";
    $result = $mysqli->query($sql);
    while ($row_cid = $result->fetch_object()) {
        if (is_running_and_not_practice($row_cid->cid)) {
            $irc = true;
            $sql = "SELECT defunct_TA FROM contest WHERE contest_id='$row_cid->cid'";
            $result_tmp = $mysqli->query($sql);
            $row_tmp = $result->fetch_array();
            $result->free();
        }
    }
    /* 判断是否有查看权限 start */
    if (isset($OJ_AUTO_SHARE) && $OJ_AUTO_SHARE && isset($_SESSION['user_id'])){ // 已经AC该题目，可查看该题代码
        $sql = "SELECT 1 FROM solution WHERE result=4 AND problem_id=$pid AND user_id='".$_SESSION['user_id']."'";
        $rrs = $mysqli->query($sql);
        $ok = !$irc && ($rrs->num_rows>0) ;
        $rrs->free();
        if ($ok) return true;
    }
    
    if (isset($_SESSION['user_id'])&&$row && $row->user_id==$_SESSION['user_id']) return true;  // 是本人，可以查看该代码
    else { // 不是本人的情况下
        if (is_running(intval($cid))) { // the problem is in running contest
            return HAS_PRI("see_source_in_contest");
        }
        else if (is_numeric($cid)) { // 没有运行中的比赛包含该题则考察该代码是否在已经结束的比赛中
            $sql = "SELECT defunct_TA, open_source FROM contest WHERE contest_id='$cid'";
            $result = $mysqli->query($sql);
            $row = $result->fetch_object();
            $open_source = $row->open_source=="Y"?1:0; // 默认值为0
            $defunct_TA = $row->defunct_TA=="Y"?1:0; // 默认值为0
            $result->free();
            return  ( (!is_running(intval($cid))  && $open_source) || // 比赛已经结束了且开放源代码查看
                HAS_PRI("see_source_in_contest")
            );
        } else { // 该代码不是在比赛中的
            if (HAS_PRI("see_source_out_of_contest")) return true;
        }
    }
    /* 判断是否有查看权限 end */
    
    return false;
}

function can_see_problem($pid) {
    if(HAS_PRI("edit_".get_problemset($pid)."_problem"))
        return true;
    
    global $mysqli;
    $sql = "SELECT COUNT(1) FROM problem WHERE defunct='N' AND problem_id='$pid'";
    $not_hidden = $mysqli->query($sql)->fetch_array()[0];
    $sql = <<<SQL
        SELECT DISTINCT
          contest_problem.problem_id,
          contest.contest_id,
          start_time,
          end_time
        FROM
          contest_problem
        INNER JOIN contest
        ON contest_problem.contest_id = contest.contest_id
        WHERE
          problem_id='$pid'
          AND now()>=start_time
          AND now()<=end_time
          AND contest.practice = 0
        ORDER BY problem_id
SQL;
    $is_in_running_contest = $mysqli->query($sql)->num_rows;
    if($not_hidden && !$is_in_running_contest)return true;
    
    return false;
}

function can_see_res_info($sid) {
    
    global $OJ_SHOW_DIFF;
    global $mysqli;
    
    /* 获取solution信息 start */
    $sql="SELECT * FROM `solution` WHERE `solution_id`='".$sid."'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_object();
    $pid = $row->problem_id;
    $cid = $row->contest_id;
    $type = $row->result;
    $result->free();
    /* 获取solution信息 end */
    
    /* 判断是否有查看权限 start */
    if($type==10 || $type==11){//RE or CE
        if(isset($_SESSION['user_id'])&&$row && $row->user_id==$_SESSION['user_id']) return true;// is himself
        else {
            if (is_numeric($cid)) {
                return HAS_PRI("see_wa_info_in_contest");
            } else { // 该代码不是在比赛中的
                return HAS_PRI("see_wa_info_out_of_contest");
            }
        }
    }
    //WA or others
    if (isset($_SESSION['user_id'])&&$row && $row->user_id==$_SESSION['user_id'] && $OJ_SHOW_DIFF) return true;  // is himself and show_diff is true
    else {
        if (is_numeric($cid)) {
            return HAS_PRI("see_wa_info_in_contest");
        } else { // 该代码不是在比赛中的
            return HAS_PRI("see_wa_info_out_of_contest");
        }
    }
    /* 判断是否有查看权限 end */
    
    return false;
}

function get_problemset($pid){
    global $mysqli;
    $sql="SELECT problemset FROM problem WHERE problem_id=$pid";
    $res=$mysqli->query($sql);
    return $res->fetch_array()[0];
}
function get_order($group_name){
    global $mysqli;
    $sql="SELECT group_order FROM privilege_groups WHERE group_name='$group_name'";
    //echo "<pre>sql:$sql</pre>";
    $res=$mysqli->query($sql);
    if($res->num_rows){
        $ans=($res->fetch_array()[0]);
    }
    else{
        $ans=99999;
    }
    return $ans;
}
function get_group($uid){
    global $mysqli;
    if($uid=="")$uid=$_SESSION['user_id'];
    $sql="SELECT rightstr FROM privilege WHERE user_id='$uid'";
    return ($mysqli->query($sql)->fetch_array()[0]);
}
?>
