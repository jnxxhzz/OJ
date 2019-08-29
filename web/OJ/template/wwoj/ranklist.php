<?php
  /**
   * This file is created
   * by jnxxhzz
   * @2019.03.27
  **/
?>

<?php $title="Ranklist";?>
<?php include "header.php" ?>
<style>
  .am-form-inline > .am-form-group {
    margin-left: 15px;
  }
  .am-form-inline {
    margin-bottom: 1.5rem;
  }
</style>
<div class='am-container'>
  <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <?php
    // <!--
    // <ul class="am-nav am-nav-tabs">
    //   <li><a href="/OJ/problemset.php">Problems</a></li>
    //   <li><a href="/OJ/status.php">Status</a></li>
    //   <li class="am-active"><a href="/OJ/ranklist.php">Standings</a></li>
    // </ul>
    // -->
    ?>
  </div>
  <div class='am-g'>
    <!-- 用户查找 start -->
    <div class='am-u-md-6'>
      <form class="am-form am-form-inline" action='userinfo.php'>
        <input type="hidden" name="csrf_token" value="f31605cce38e27bcb4e8a76188e92b3b">
        <div class='am-form-group'>
          <select data-am-selected type='text' id ='lv_system'>
            <option value='all' <?php if (isset($_GET['lv_system']) && $_GET['lv_system']=="" || !isset($_GET['lv_system'])) echo "selected"; ?>>全部</option>
          <?php
            foreach($classSet as $class) {
              $selected = "";
              if (isset($_GET['lv_system']) && $_GET['lv_system'] == $level_name_number[$class]) $selected = "selected";
              echo "<option value='".$level_name_number[$class]."' ".$selected.">".$class."</option>";
            }
          ?>

          </select>
          <!-- 选择班级后自动跳转页面的js代码 start -->
          <script type="text/javascript">
            var oSelect=document.getElementById("lv_system");
            oSelect.onchange=function() { //当选项改变时触发
              var valOption=this.options[this.selectedIndex].value; //获取option的value
              var url = window.location.search;
              var cid = url.substr(url.indexOf('=')+1,4);
              var url = window.location.pathname+"?lv_system="+valOption;
              window.location.href = url;
            }
          </script>
          <!-- 选择班级后自动跳转页面的js代码 end -->
        </div>
        <div class="am-form-group am-form-icon">
          <i class="am-icon-search"></i>
          <input type="text" class="am-form-field" placeholder=" &nbsp;Input user ID" name="user">
        </div>
        <button type="submit" class="am-btn am-btn-warning ">Go</button>
      </form>
    </div>
    <!-- 用户查找 end -->
    <?php
//     <!-- 排序模块 start -->
// <!--     <div class='am-u-md-6 am-text-right am-text-middle'>
//       <b>For All:&nbsp</b>
//       <a href=ranklist.php?order_by=s>Level</a>&nbsp&nbsp&nbsp&nbsp
//       <b>For HZNU:</b>&nbsp
//       <a href=ranklist.php?order_by=ac>AC</a>&nbsp&nbsp
//       <a href=ranklist.php?scope=d>Day</a>&nbsp&nbsp
//       <a href=ranklist.php?scope=w>Week</a>&nbsp&nbsp
//       <a href=ranklist.php?scope=m>Month</a>&nbsp&nbsp
//       <a href=ranklist.php?scope=y>Year</a>&nbsp&nbsp
//     </div> -->
//     <!-- 排序模块 end -->
    ?>
  </div>
  <div class="am-g" style="color: grey; text-align: center;">
    <div>
      Standings won't update automatically. Please visit your user info page to update your information.
    </div>
  </div>
  <div class="am-avg-md-1">
    <table class="am-table am-text-nowrap">
      <!-- 表头 start -->
      <thead>
      <tr>
        <th class='am-text-center'>Rank</th>
        <th class='am-text-center'>User</th>
        <th class='am-text-center'>Nick</th>
        <th class='am-text-center'>Solved</th>
        <th class='am-text-center'>Level</th>
        <th class='am-text-center'>Exp</th>
      </tr>
      </thead>
      <!-- 表头 end -->
      
      <!-- 列出排名 start -->
      <tbody>
      <?php
      foreach($view_rank as $row){
          echo "<tr>";
          foreach($row as $table_cell){
              echo "<td align='center'>";
              echo $table_cell;
              echo "</td>";
          }
          echo "</tr>";
      }
      ?>
      </tbody>
      <!-- 列出排名 end -->
    
    </table>
  </div>

<!-- 页标签 start -->
<div class="am-avg-md-1">
  <ul class="am-pagination am-text-center">
    <li><a href="ranklist.php?start=<?php echo $start-30<0?0:$start-30; echo '&'.$filter_url; ?>">&laquo; Prev</a></li>
      <?php
        if (intval($view_total/$page_size)+1 > 7) {
          $i = max(0,$start-3*$page_size);
          if ($start> 4*$page_size-1) {
            echo "<li><a href='./ranklist.php?start=0".$filter_url. "'>1</a></li>";
            if ((intval($start/$page_size)+1)-4 > 1) echo "&nbsp;......&nbsp;&nbsp;";
          }
          for(; $i<min($view_total,$start+4*$page_size); $i+=$page_size) {
            if (intval($i/$page_size)+1 == intval($start/$page_size)+1)
              echo "<li class='am-active'><a href='./ranklist.php?start=" . strval ( $i ).$filter_url. "'>";
            else
              echo "<li><a href='./ranklist.php?start=" . strval ( $i ).$filter_url. "'>";
            echo strval(intval($i/$page_size)+1);
            echo "</a></li>";
          }
          if ($i < $view_total) {
            if (intval(($i)/$page_size)+1 < intval(($view_total-1)/$page_size+1)) echo "&nbsp;&nbsp;......&nbsp;";
            echo "<li><a href='./ranklist.php?start=".(intval($view_total/$page_size)*$page_size).$filter_url. "'>".intval(($view_total-1)/$page_size+1)."</a></li>";
          }
        } else for($i=0; $i<$view_total; $i+=$page_size) {
          if (intval($i/$page_size)+1 == intval($start/$page_size)+1)
            echo "<li class='am-active'><a href='./ranklist.php?start=" . strval ( $i ).$filter_url. "'>";
          else
            echo "<li><a href='./ranklist.php?start=" . strval ( $i ).$filter_url. "'>";
          echo strval(intval($i/$page_size)+1);
          echo "</a></li>";
        }
      ?>
    <li><a href="ranklist.php?start=<?php echo $start+30<$view_total?$start+30:$start; echo '&'.$filter_url; ?>">Next &raquo;</a></li>
  </ul>
</div>
<!-- 页标签 end -->

<?php include "footer.php" ?>


