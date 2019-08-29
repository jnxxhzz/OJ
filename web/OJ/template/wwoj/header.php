<?php
  /**
   * This file is created
   * by jnxxhzz
   * @2019.03.29
  **/
?>


<?php // 是否显示tag的判断
  // require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
  // if(!isset($mysqli))exit(0);
  // $show_tag = true;
  // if (isset($_SESSION['user_id']) && !isset($_SESSION['contest_id'])) {
  //   $uid = $_SESSION['user_id'];
  //   $sql = "SELECT tag FROM users WHERE user_id='$uid'";
  //   $result = $mysqli->query($sql);
  //   $row_h = $result->fetch_array();
  //   $result->free();
  //   if ($row_h['tag'] == "N") $show_tag = false;
  // } else if (isset($_SESSION['tag'])) {
  //   if ($_SESSION['tag'] == "N") $show_tag = false;
  //   else $show_tag = true;
  // }
  // if ($show_tag) $_SESSION['tag'] = "Y";
  // else $_SESSION['tag'] = "N";
?>


<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="UTF-8">
    <title><?php if(isset($title))echo $OJ_NAME."--".$title?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="alternate icon" type="image/jpg" href="image/hznuoj.ico">
    <link rel="stylesheet" href="/OJ/plugins/AmazeUI/css/amazeui.min.css"/>
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"/> -->
    <style type="text/css">
      .blog-footer {
        padding: 10px 0;
        text-align: center;
      }
      .am-container {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        max-width: 1400px;
      }
      .tt{
        margin-top: 20px;
        margin-bottom: 20px;
      }
      .am-badge{
        font-weight: normal;
      }
    </style>
  </head>
  <body class='am-with-topbar-fixed-top bg'>
  <header class="am-topbar-inverse am-topbar-fixed-top" style="background-color: #37608c">
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-primary am-show-sm-only" data-am-collapse="{target: '#collapse-head'}">
      <span class="am-sr-only">导航切换</span>
      <span class="am-icon-bars"></span>
    </button>
    <div class="am-container">
      <h1 class="am-topbar-brand">
        <a href="/OJ/index.php">WWOJ</a>
      </h1>
      <div class="am-collapse am-topbar-collapse" id="collapse-head">
        <ul class="am-nav am-nav-pills am-topbar-nav">

          <!-- ProblemSet部分 start -->
          <li <?php
          $page_name=basename($_SERVER['SCRIPT_NAME']);
          if($page_name=="problemset.php" || $page_name=="problem.php") {
            echo "class='am-active'";
          }
          ?>><a href="/OJ/problemset.php">ProblemSet</a></li>
          <!-- ProblemSet部分 end -->
          
          <!-- Status部分 start -->
          <li <?php
          $page_name=basename($_SERVER['SCRIPT_NAME']);
          if($page_name=="status.php") {
            echo "class='am-active'";
          }
          ?>><a href="/OJ/status.php">Status</a></li>
          <!-- Status部分 end -->

          <!-- ranklist部分 start -->
          <li <?php
          $page_name=basename($_SERVER['SCRIPT_NAME']);
          if($page_name=="ranklist.php") {
            echo "class='am-active'";
          }
          ?>><a href="/OJ/ranklist.php">RankList</a></li>
          <!-- ranklist部分 end -->

          <!-- Contest部分 start -->
          <li <?php
          $page_name=basename($_SERVER['SCRIPT_NAME']);
          if($page_name=="contest.php") {
              echo "class='am-active'";
          }
          ?>><a href="/OJ/contest.php">Contest</a></li>
          <!-- Contest部分 end -->

          <!-- Contest部分 start -->
          <li <?php
          $page_name=basename($_SERVER['SCRIPT_NAME']);
          if($page_name=="recent-contest.php") {
              echo "class='am-active'";
          }
          ?>><a href="/OJ/recent-contest.php">Recent</a></li>
          <!-- Contest部分 end -->

          <!-- FAQ部分 start -->
          <li <?php 
          if(basename($_SERVER['SCRIPT_NAME'])=="faqs.php"){
            echo "class='am-active'";
          } 
          ?>><a href="/OJ/faqs.php">F.A.Q</a></li>
          <!-- FAQ部分 end -->


          <!-- Video部分 start -->
          <li <?php 
          if(basename($_SERVER['SCRIPT_NAME'])=="video.php"){
            echo "class='am-active'";
          } 
          ?>><a href="/OJ/video.php">Video</a></li>
          <!-- Video部分 end -->

          <?php
          // <!-- Others Begin 
          // <li class="am-dropdown" data-am-dropdown>
          //   <a href="#" class="am-dropdown-toggle" >Others&nbsp;<span class="am-icon-caret-down"></span></a>
          //   <ul class="am-dropdown-content">
          //     <li><a href="/OJ/c_course.php">Programming Fundamentals</a></li>
          //     <li><a href="/OJ/donation.php">Donation</a></li>
          //     <li><a href="/forum/">Forum</a></li>
          //   </ul>
          // </li>-->
          // <!-- Others End -->
          // <!-- <li><a href="/OJ/tools.php">Tools</a></li> -->
          ?>
        </ul>

        <!-- 用户部分 start -->
        <?php
        if (!isset($_SESSION['user_id'])){
echo <<<BOT
          <div class="am-topbar-right">
            <ul class="am-nav am-nav-pills am-topbar-nav">
              <li class="am-dropdown" data-am-dropdown>
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">Login <span class="am-icon-caret-down"></span></a>
                  <ul class="am-dropdown-content">
                    <li><a href="/OJ/loginpage.php"><span class="am-icon-user"></span> Login</a></li>
                    <li><a href="/OJ/registerpage.php"><span class="am-icon-pencil"></span> Register</a></li>
BOT;
                  // if ($show_tag) echo "<li><a href='/OJ/changeTag.php'><span class='am-icon-toggle-on'></span> Hide Tag</a></li>";
                  // else echo "<li><a href='/OJ/changeTag.php'><span class='am-icon-toggle-off'></span> Show Tag</a></li>";
echo <<<BOT
                  </ul>
              </li>
            </ul>
        </div>
BOT;
        } else {
          $user_session = $_SESSION['user_id'];
echo <<<BOT
          <div class="am-topbar-right">
             <ul class="am-nav am-nav-pills am-topbar-nav">
              <li class="am-dropdown" data-am-dropdown>
                <a class='am-dropdown-toggle' data-am-dropdown-toggle href='javascript:;'><span class='am-icon-user'></span> {$_SESSION['user_id']}<span class='am-icon-caret-down'></span></a>
                  <ul class="am-dropdown-content">
BOT;
          if (!isset($_SESSION['contest_id'])) {
echo <<<BOT
                    <li><a href="/OJ/modifypage.php"><span class="am-icon-eraser"></span> Modify Info</a></li>
                    <li><a href="/OJ/userinfo.php?user={$_SESSION['user_id']}"><span class="am-icon-info-circle"></span> User Info</a></li>
                    <!-- <li><a href="/OJ/mail.php"><span class="am-icon-comments"></span> Mail</a></li> -->
                    <li><a href="/OJ/status.php?user_id=$user_session"><span class="am-icon-leaf"></span> Recent</a></li>                
BOT;
          }
          // if ($show_tag) echo "<li><a href='/OJ/changeTag.php'><span class='am-icon-toggle-on'></span> Hide Tag</a></li>";
          // else echo "<li><a href='/OJ/changeTag.php'><span class='am-icon-toggle-off'></span> Show Tag</a></li>";
          echo "<li><a href='/OJ/logout.php'><span class='am-icon-reply'></span> Logout</a></li>";

          if(HAS_PRI('enter_admin_page')){
            echo <<<BOT
              <li><a href="/OJ/admin/index.php"><span class="am-icon-cog"></span> Admin</a></li>
                      </ul>
                  </li>
                </ul>
          </div>
BOT;
          }else{
            echo <<<BOT
                      </ul>
                  </li>
                </ul>
          </div>
BOT;
          }
        }
        ?>
        <!-- 用户部分 end -->
      </div>
    </div>
  </header>
