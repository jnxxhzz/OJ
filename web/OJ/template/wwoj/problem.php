<?php
/**
 * This file is created
 * by jnxxhzz
 * @2019.3.25
 **/
?>


<?php $title=$view_title;?>
<?php
if (isset($_GET['OJ'])) $OJ = $_GET['OJ'];
else $OJ = "HZNU";

if ($_GET['cid']) require_once("contest_header.php");
else {
    require_once("header.php");
}
function sss($str){
    $after = preg_replace( '/<[^<]+?>/' ,'FUCK$0FUCK', $str);
    $after = preg_replace( '/(?<!FUCK)</' ,'&lt;', $after);
    $after = preg_replace( '/FUCK(?=<)/' ,'', $after);
    $after = preg_replace( '/>(?!FUCK)/' ,'&gt;', $after);
    $after = preg_replace( '/(?<=>)FUCK/' ,'', $after);
    return $after;
}
?>


<!-- Sample Input 和 Sample Output 的背景色 start -->
<style type="text/css">
  .sampledata {
    /*background: none repeat scroll 0 0 rgba(0,0,0,.075);*/
    font-family: Monospace;
    white-space: pre-wrap;
    font-size: 10pt;
  }
  .sample-outer {
    /* border: 1px solid; */
    margin-bottom: 20px;
    border-bottom: 1px solid #ccc;
  }
  .sample-bg {
    background: #f0efef;
    color: #7e2222;
    /* border-top: 1px solid; */
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    padding: 5px;
    line-height: 1;
  }
  .sample-title {
    background: white;
    border: 1px solid #ccc;
    padding-left: 5px;
    padding-right: 5px;
  }
</style>
<!-- Sample Input 和 Sample Output 的背景色 end -->


<div class="am-container">
  <?php
  //the header of problem page
  if(isset($_GET['cid'])) {
    echo "<ul class=\"am-nav am-nav-tabs\" style='margin-top: 30px;'>";
    for($i = 0 ; $i<$problem_cnt ; ++$i) {
      $label = PID($i);
      $class = $i == $pid? "am-active": "";
      echo <<<HTML
  <li class="$class"><a href="problem.php?cid=$cid&pid=$i">$label</a></li>
HTML;
    }
    echo "</ul>";
  }
  ?>
  <h1 style="text-align:center;margin-top:40px;">
      <?php
      if (!isset($_GET['cid'])) echo $row->problem_id . ": ";
      echo $row->title;
      if($has_accepted) {
          echo " <i class='am-icon-check-circle' style='color:#4aaa4a'></i>";
      }
      ?>
    <!-- is contest problem -->
      <?php
      $now=time();
      if ($is_practice || isset($_GET['cid']) && ($now>$end_time || HAS_PRI("edit_contest"))): ?>
        <span class="am-badge am-badge-primary am-text-lg">
      <a href="problem.php?id=<?php echo $real_id ?>" style="color: white;">
        <?php echo $real_id ?>
      </a>
    </span>
      <?php endif ?>
  </h1> 
    <div style="text-align:center;">
      Time Limit:&nbsp;&nbsp;<span class=" am-text-sm" style="color:#0c79b1"><?php echo $row->time_limit?> Sec</span>
      &nbsp;&nbsp; Memory Limit: &nbsp;&nbsp;<span class="am-text-sm" style="color:#0c79b1"><?php echo $row->memory_limit?> MB</span>
      <?php if($row->spj) echo "<span class='am-badge am-badge-danger am-round am-text-sm'>Special Judge</span>"?>
    </div>
    <div style="text-align:center;" >
      Submission：<span class="am-text-sm" style="font-weight:bold"><?php echo $submit_num?></span>&nbsp;&nbsp;&nbsp;&nbsp;
      AC：<span class="am-text-sm" style="font-weight:bold"><?php echo $ac_num?></span>&nbsp;&nbsp;&nbsp;&nbsp;
      <?php if(!isset($contest_score)):?>
        <?php
        $score_class = "am-badge-default";
        if ($row->score >= 82) $score_class='am-badge-danger';
        else if ($row->score >= 64) $score_class='am-badge-warning';
        else if ($row->score >= 46) $score_class='am-badge-primary';
        else if ($row->score >= 28) $score_class='am-badge-secondary';
        ?>
        Score：<span class='am-badge am-text-sm am-round <?php echo $score_class ?>'><?php echo $row->score?></span>
      <?php else:?>
        Score：<span class='am-badge am-badge-success am-round am-text-sm'><?php echo $contest_score?></span>
      <?php endif;?>
      
    </div>
    <br />
    <!-- 提交等按钮 start -->
    <div class="am-text-center">
      <a href="
      <?php
      if ($pr_flag){
          echo "submitpage.php?id=$id";
      }else{
          echo "submitpage.php?cid=$cid&pid=$pid&langmask=$langmask";
      }
      ?>
    " style="color:white">
        <button type="button" class="am-btn am-btn-sm am-btn-success ">Submit</button>
      </a>
        <?php
        if(!isset($_GET['cid']) || $is_practice==1) {
            echo<<<HTML
            <a href="problemstatus.php?id={$row->problem_id}" style="color:white">
              <button type="button" class="am-btn am-btn-sm am-btn-primary ">
                Codes
              </button>
            </a>
HTML;
        }
        if (HAS_PRI("edit_".$set_name."_problem")) {
            echo<<<HTML
          <a href="/OJ/admin/problem_edit.php?id=$row->problem_id&getkey={$_SESSION['getkey']}" style='color:white'>
            <button type='button' class='am-btn am-btn-sm am-btn-danger '>
              Edit
            </button>
          </a>
          <a href="/OJ/admin/quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes" style='color:white'>
            <button type='button' class='am-btn am-btn-sm am-btn-warning '>
              Test Data
            </button>
          </a>

HTML;
        }
        ?>
    </div>
    <br />
    <!-- 提交等按钮 end -->
      <?php
      $str=sss($row->description);
      if($str) {
        //编码转义未解决！
          //$tt=htmlspecialchars($row->description);
          echo <<<HTML
          <div class="am-panel am-panel-default am-panel-secondary">
            <header class="am-panel-hd">
             <h3 class="am-panel-title"> Description</h3>
            </header>
            <div class="am-panel-bd">
             $str
            </div>
        </div>
HTML;
      }
      ?>
      <?php
      $str=sss($row->input);
      if($str) {
          echo <<<HTML

          <div class="am-panel am-panel-default am-panel-secondary">
            <header class="am-panel-hd">
             <h3 class="am-panel-title"> Input</h3>
            </header>
            <div class="am-panel-bd">
             $str
            </div>
        </div>
HTML;
      }
      ?>
    
      <?php
      $str=sss($row->output);
      if($str) {
          echo <<<HTML
          <div class="am-panel am-panel-default am-panel-secondary">
            <header class="am-panel-hd">
             <h3 class="am-panel-title"> Output</h3>
            </header>
            <div class="am-panel-bd">
             $str
            </div>
        </div>
HTML;
      }
      ?>

      <?php
      $html_samples="";
      foreach ($samples as $sample) {
          $text_input=htmlentities($sample['input']);
          $text_output=htmlentities($sample['output']);
          if($sample['show_after']){
              $html_samples.= <<<HTML
                <div style='color: grey;'>
                  Show after trying {$sample['show_after']} times:
                </div>
HTML;
          }
          if($text_input || $text_output) {
              $html_samples.= <<<HTML
                <div class="sample-outer">
                  <div class="sample-title">input:</div>
                  <div class="sample-bg"><span class="sampledata">$text_input</span></div>
                  <div class="sample-title">output:</div>
                  <div class="sample-bg"><span class="sampledata">$text_output</span></div>
                </div>
HTML;
          }
      }
      $str=sss($html_samples);
      if($str) {
          echo <<<HTML
          <div class="am-panel am-panel-default am-panel-secondary">
            <header class="am-panel-hd">
             <h3 class="am-panel-title"> Samples</h3>
            </header>
            <div class="am-panel-bd">
             $str
            </div>
          </div>
HTML;
      }
      ?>
    
    
      <?php
      $str=sss($row->hint);
      if($str) {
          echo <<<HTML
          <div class="am-panel am-panel-default am-panel-secondary">
            <header class="am-panel-hd">
             <h3 class="am-panel-title"> Hint</h3>
            </header>
            <div class="am-panel-bd">
             $str
            </div>
          </div>
HTML;
      }
      ?>
    
      <?php
      $str=sss($row->author);
      if($str) {
          echo <<<HTML
          <div class="am-panel am-panel-default am-panel-secondary">
            <header class="am-panel-hd">
              <h3 class="am-panel-title"> Author</h3>
            </header>
            <div class="am-panel-bd">
             <p>
              <a href='problemset.php?search=$row->author'>$str</a>
             </p>
            </div>
          </div>
HTML;
      }
      ?>
  
      <?php
      if (!isset($_GET['cid'])) { // hide source if the problem is in contest
          $str=sss($row->source);
          if($str) {
              echo <<<HTML
              <div class="am-panel am-panel-default am-panel-secondary">
                <header class="am-panel-hd">
                 <h3 class="am-panel-title"> Source</h3>
                </header>
                <div class="am-panel-bd">
                  <p>
                    <a href='problemset.php?search=$row->source'>$str</a>
                  </p>
                </div>
              </div>
HTML;
          }
      }
      ?>
    <!-- 
      <?php if ($can_see_video || HAS_PRI("watch_solution_video")): ?>
        <h2>Solution Video</h2>
          <?php if (file_exists("upload/video/".md5($real_id)."pfb.mp4")): ?>
          <form action="solution_video.php" method="POST">
            <input type="hidden" name="pid" value="<?php echo $real_id ?>" placeholder="">
            <button class="am-btn am-btn-success am-btn-lg">Click To Watch The Video</button>
          </form>
          <?php else: ?>
          <button disabled="1" class="am-btn am-btn-default am-btn-lg">No Solution Video</button>
          <?php endif ?>
        <div style="display: block; color: grey; padding-bottom: 20px;">
          *if you see this button, it means you've submited more than <?php echo $VIDEO_SUBMIT_TIME ?> times.
        </div>
      <?php endif ?> -->
  
    <!-- 提交等按钮 start -->
    <div class="am-text-center">
      <a href="
      <?php
      if ($pr_flag){
          echo "submitpage.php?id=$id";
      }else{
          echo "submitpage.php?cid=$cid&pid=$pid&langmask=$langmask";
      }
      ?>
    " style="color:white">
        <button type="button" class="am-btn am-btn-sm am-btn-success ">Submit</button>
      </a>
        <?php
        if(!isset($_GET['cid']) || $is_practice==1) {
            echo<<<HTML
            <a href="problemstatus.php?id={$row->problem_id}" style="color:white">
              <button type="button" class="am-btn am-btn-sm am-btn-primary ">
                Codes
              </button>
            </a>
HTML;
        }
        if (HAS_PRI("edit_".$set_name."_problem")) {
            echo<<<HTML
          <a href="/OJ/admin/problem_edit.php?id=$row->problem_id&getkey={$_SESSION['getkey']}" style='color:white'>
            <button type='button' class='am-btn am-btn-sm am-btn-danger '>
              Edit
            </button>
          </a>
          <a href="/OJ/admin/quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes" style='color:white'>
            <button type='button' class='am-btn am-btn-sm am-btn-warning '>
              Test Data
            </button>
          </a>

HTML;
        }
        ?>
    </div>
    <!-- 提交等按钮 end -->

</div>
<?php require_once("footer.php"); ?>

<!-- ajax for adding user's own tag -->
<script>
    $("#tagSubmit").click(function(){
        $.ajax({
            url: 'addTag.php',
            type: 'post',
            data: $("#tagForm").serialize(),
            async: false,
            success: function(data,status){
                //alert(data+"\r\n"+status);
                window.location.reload();
            },
        });
    });
</script>
<!-- highlight.js START-->
<!-- <link href='highlight/styles/github-gist.css' rel='stylesheet' type='text/css'/> -->
<!-- <script src='highlight/highlight.pack.js' type='text/javascript'></script> -->
<!-- <script src='highlight/highlightjs-line-numbers.min.js' type='text/javascript'></script> -->

<link href="/OJ/plugins/highlight/styles/github-gist.css" rel="stylesheet">
<script src="/OJ/plugins/highlight/highlight.pack.js"></script>
<script src="/OJ/plugins/highlight/highlightjs-line-numbers.min.js"></script>
<style type="text/css">
  .hljs-line-numbers {
    text-align: right;
    border-right: 1px solid #ccc;
    color: #999;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }
  code{
    background: transparent;
  }
  pre.prettyprint{
    background: transparent;
  }
  .main{
    background-color: red !important;
  }
</style>
<script>
    hljs.initHighlightingOnLoad();
    hljs.initLineNumbersOnLoad();
</script>
<!-- highlight.js END-->
<!--auto folding code START-->
<script type="text/javascript">
    $(document).ready(function(){
        $("pre code").parent().before("<button class='am-btn am-btn-block am-btn-default am-text-xs'>Toggle Code</button>");
        $("pre code").parent().hide(0);


        $("button").click(function(){
            $(this).next().toggle(100);
        });
    });
</script>
<!--auto folding code END-->

