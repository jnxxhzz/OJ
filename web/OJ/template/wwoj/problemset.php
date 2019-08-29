<?php
  /**
   * by jnxxhzz
   * @2019.03.22
  **/
?>

<?php
$title="ProblemSet";
require_once("header.php");
$args=Array();

//default args
if($OJ!="all")$args['OJ']=$OJ;
if(isset($sort_method)) $args['sort_method']=$sort_method;
if(isset($_GET['search'])) $args['search']=htmlentities($search);
if(isset($page)) $args['page']=$page;
function generate_url($data){
    // echo "<pre>";
    // var_dump($data);
    // echo "</pre>";
    global $args;
    $link="problemset.php?";
    foreach ($args as $key => $value) {
        if(isset($data["$key"])){
            $value=htmlentities($data["$key"]);
            $link.="&$key=$value";
        }
        else if($value){
            $link.="&$key=".htmlentities($value);
        }
    }
    return $link;
}
?>


<div class="am-container">
  <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
  <?php  
    // <ul class="am-nav am-nav-tabs">
    //   <li class="am-active"><a href="/OJ/problemset.php">Problems</a></li>
    //   <li><a href="/OJ/status.php">Status</a></li>
    //   <li><a href="/OJ/ranklist.php">Standings</a></li>
    // </ul>
   ?>
  </div>
  

<!-- 题目查找 start -->
  <div class="am-g">
    <?php
    //     <div class='am-u-md-2'>
    //     <ul class="am-avg-sm-1 am-thumbnails">
    //       <div class='am-u-sm-12'>
    //         <div class="am-progress am-progress-striped am-active" style="margin-bottom:1px">
    //           <div class="am-progress-bar "  style="width: 60%"><php echo $nowuser_level ></div>
    //         </div>
    //       </div>
    //       <div class='am-u-sm-12'>
    //         <div class="am-progress am-progress-striped am-active">
    //           <div class="am-progress-bar"  style="width: 60%"><php echo $nowuser_level ></div>
    //         </div>
    //       </div>
    //     </ul>
    // </div>
    ?>
    <!-- 通过ProblemID查找 start-->
    <div class='am-u-md-3 '>
      <form class="am-form am-form-horizontal" action='problem.php'>
        <input type="hidden" name="csrf_token" value="f31605cce38e27bcb4e8a76188e92b3b"/>
        <div class="am-u-sm-9">
          <div class="am-form-group am-form-icon">
            <i class="am-icon-search"></i>
            <input type="text" class="am-form-field" placeholder="  &nbsp;Problem ID" name="id">
          </div>
        </div>
        <button type="submit" class="am-u-sm-3 am-btn am-btn-warning ">Go</button>
      </form>
    </div>
    <!-- 通过ProblemID查找 end-->


    <!-- 通过关键词查找 start -->
    <div class='am-u-md-4 am-u-md-offset-1'>
      <form class="am-form am-form-horizontal">
        <input type="hidden" name="csrf_token" value="f31605cce38e27bcb4e8a76188e92b3b"/>
        <div class="am-u-sm-9">
          <div class="am-form-group am-form-icon">
            <i class="am-icon-binoculars"></i>
            <input type="text" class="am-form-field" placeholder=" &nbsp;Keywords" name="search" value="<?php echo $args['search'] ?>">
            <input type="hidden" name="OJ" value="<?php echo $args['OJ'] ?>">
          </div>
        </div>
        <button type="submit" class="am-u-sm-3 am-btn am-btn-secondary ">Search</button>
      </form>
    </div>
    <!-- 通过关键词查找 end -->
    <!-- by problemset -->
    <div class='am-u-md-3 am-u-md-offset-1 am-u-end '>
      <form class="am-form am-form-horizontal">
        <input type="hidden" name="csrf_token" value="f31605cce38e27bcb4e8a76188e92b3b"/>
        <div class="am-form-group">
          <label class="am-u-sm-4 am-form-label">ProblemSet:</label>
          <div class="am-u-sm-8">
            <select data-am-selected class='select-problemset' type='text'>
              <option value='all' <?php if(!isset($_GET['OJ'])) echo "selected";?> >All</option>
                <?php
                $res = $mysqli->query("SELECT set_name,set_name_show FROM problemset");
                while($row = $res->fetch_array()){
                    echo "<option value='$row[0]' ";
                    if($_GET['OJ']==$row[0]) echo "selected";
                    echo ">$row[1]</option>";
                }
                ?>
            </select>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!--random choose END-->
  <!-- 题目查找 end -->

  <!-- 页标签 start -->
  <div class="am-g">
    <ul class="am-pagination am-text-center">
        <?php $link = generate_url(Array("page"=>max($page-1, 1)))?>
      <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        for ($i=1;$i<=$view_total_page;$i++){
            $link=generate_url(Array("page"=>"$i"));
            if($page==$i)
                echo "<li class='am-active'><a href=\"$link\">{$i}</a></li>";
            else
                echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(Array("page"=>min($page+1,intval($view_total_page)))) ?>
      <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
    </ul>
  </div>
<!-- 页标签 end -->

  <!-- 罗列题目 start -->
  <style type="text/css">
    td {
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
      text-align: center;
    }
    .table-problem {
      table-layout: fixed;
    }
  </style>
  <div class="am-avg-md-1">
    <table class="am-table am-table-hover am-table-striped am-text-nowrap table-problem">
      <thead>
      <tr>
        <th style='width:3%;'></th>
        <th class='am-text-center' style='width:8%;'>Prob.ID</th>
        <th class='am-text-center' style='width:34%;'>Title</th>
        <th class='am-text-center' style='width:18%;'>Tags</th>
        <th class='am-text-center' style='width:10%;'>Author</th>
        <th class='am-text-center' style='width:21%;'>Source</th>
        <th class='am-text-center' style='width:10%;'>AC/Sub</th>
          <?php
          switch ($args['sort_method']) {
              case 'SCORE_DESC':
                  $score_icon="am-icon-sort-amount-desc";
                  break;
              case 'SCORE_ASCE':
                  $score_icon="am-icon-sort-amount-asc";
                  break;
              default:
                  $score_icon="am-icon-sort";
                  break;
          }
          ?>
        <style type="text/css" media="screen">
          #score:hover{
            cursor: pointer;
          }
        </style>
        <th class='am-text-center' style='width:10%;'>Score</span></th>
      </tr>
      </thead>
      <tbody>
      <?php
      foreach($view_problemset as $row){
          echo "<tr>";
          foreach($row as $table_cell){
              echo $table_cell;
          }
          echo "</tr>";
      }
      ?>
      </tbody>
    </table>
  </div>
  <!-- 罗列题目 end -->
  
  <!-- 页标签 start -->
  <div class="am-g">
    <ul class="am-pagination am-text-center">
        <?php $link = generate_url(Array("page"=>max($page-1, 1)))?>
      <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        for ($i=1;$i<=$view_total_page;$i++){
            $link=generate_url(Array("page"=>"$i"));
            if($page==$i)
                echo "<li class='am-active'><a href=\"$link\">{$i}</a></li>";
            else
                echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(Array("page"=>min($page+1,intval($view_total_page)))) ?>
      <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
    </ul>
  </div>
</div>
<!-- 页标签 end -->

<?php require_once("footer.php") ?>

<!-- problem selector js START-->
<script type="text/javascript">
  $(".select-problemset").change(function(){
    var set_name = $(this).val();
    var url = window.location.pathname;
    if(set_name!="all") url = url+"?OJ="+set_name;
    window.location.href = url;
  });
</script>
<!-- problem selector js END-->

<?php
// <!--random choose js START-->
// <php
//   $sql="SELECT COUNT(problem_id) FROM problem WHERE defunct='N'";
//   $cnt_problem=$mysqli->query($sql)->fetch_array()[0];
//   //echo "<pre>$cnt_problem</pre>";
//   $sql="SELECT problem_id FROM problem WHERE defunct='N' LIMIT ".rand(0,$cnt_problem-1).",1";
//   $res=$mysqli->query($sql);
//   //echo "<pre>$sql</pre>";
//   $id=$res->fetch_array()[0];
//   //echo "<pre>$id</pre>";
// >
// <script type="text/javascript">
//   $("#random_choose").click(function(){
//     window.location.href="problem.php?id=<php echo $id; >";
//   });
// </script>
// <!--random choose js END-->

// <!-- sort by socre BEGIN -->

// <script>
//   $("#score").click(function(){
//     <php
//     if($args['sort_method']=='SCORE_DESC') $args['sort_method']='SCORE_ASCE';
//     else $args['sort_method']='SCORE_DESC';
//     >
//     var link="<php echo generate_url(Array("page"=>"1")) >";
//     window.location.href=link;
//   });
// </script>
// <!-- sort by socre END -->
?>
