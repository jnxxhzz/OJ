<?php
/**
 * by jnxxhzz
 * @2019.03.22
 **/
?>

<?php 
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once('./include/my_func.inc.php');
require_once("./include/classList.inc.php");

if (!isset($_SESSION['user_id'])){
  require_once("oj-header.php");
  echo "<a href='loginpage.php'>$MSG_Login</a>";
  require_once("oj-footer.php");
  exit(0);
}

  $user_id=$_SESSION['user_id'];

  /* 获取用户当前难度等级 start  */
  $sql = "SELECT class FROM users WHERE user_id='$user_id'";
  $res = $mysqli->query($sql);
  $nowuser_set_name = $res->fetch_array()[0];
  $res = $mysqli->query("SELECT set_name_show from problemset WHERE set_name='$nowuser_set_name'");
  $nowuser_level = $res->fetch_array()[0];
  $i = 0;
  foreach ($classList as $id => $nowclass) {
    if ($nowclass == $nowuser_level) break;
    $i++;
  }
  if ($i == count($classList) - 1){
    print "<script language='javascript'>\n";
    echo "alert('";
    echo "你的Hard_level已经达到最高了！";
    print "');\n history.go(-1);\n</script>";
    exit(0);  
  } 
  $need_number = $class_to_number[$i]; //获取需要升级的题目数量
  /* 获取用户当前难度等级 end  */
  $sql="SELECT DISTINCT problem_id FROM solution WHERE user_id='$user_id' AND result=4 ORDER BY problem_id"; 
  $res=$mysqli->query($sql);  
  $AC = 0;
  while($arr=$res->fetch_array()){
    $pid = $arr[0];
    if (empty($pid) || $pid == 0) continue;
    $set_name=get_problemset($pid);
    if($set_name == $nowuser_set_name) $AC++;
  }

  if ($AC < $need_number){
    $need_number -= $AC;
    print "<script language='javascript'>\n";
    echo "alert('";
    echo "你还需要完成 $need_number 题 $nowuser_level 类别的题目 !\\n";
    print "');\n history.go(-1);\n</script>";
    exit(0);  
  }
  $newuser_level = $classList[$i + 1];
  $newuser_class = $show_name_to_set_name[$newuser_level];
  $sql="UPDATE users SET class = '$newuser_class' WHERE user_id='$user_id'";
  $result=$mysqli->query($sql);
  
  print "<script language='javascript'>\n";
  echo "alert('";
  echo "恭喜你升级！现在等级为：$newuser_level !";
  print "');\n history.go(-1);\n</script>";
  exit(0);  
  
?>


