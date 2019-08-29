<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.23
   * last modified
   * by yybird
   * @2016.03.23
  **/
?>
<?php
$title="F.A.Qs";
require_once("header.php");
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/plugins/Parserdown.php";
?>
<style>
  .red {
    color: red;
  }
  .green {
    color: green;
  }
  .box{
    border: 1px solid #eee;
    padding: 30px;
    margin: 25px 0 15px 0;
    box-shadow: 2px 2px 10px 0 #ccc;
  }
  .qa {
    padding-top: 10px;
    padding-bottom: 10px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
  }
  #title-index {
    font-size: 95%;
  }
  @media screen and (max-width:640px){
    #content {
      display: none;
    }
  }
</style>
<div class="am-container">
  <h1 style="margin-top: 50px;">Online Video</h1>
  <hr>
  <div class="am-g">
    <div class="am-u-md-3" id="content">
      <div class="box" data-am-sticky="{top:60}">
        <ul id="title-index" class="am-list">
          <li style="font-size: larger; margin-bottom: 20px;">L0-入门</li>
        </ul>
      </div>
    </div>
  
    <div class="am-u-md-9">
          <iframe height="600px" width="100%" src='http://player.youku.com/embed/XMTMyMDEwMDY2OA==' frameborder=0 'allowfullscreen'></iframe>
    </div>
  </div>
</div><!--end container-->
<?php require_once("footer.php");?>
<!-- highlight.js END-->
