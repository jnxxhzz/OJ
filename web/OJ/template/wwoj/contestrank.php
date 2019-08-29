<?php
  /**
   * This file is created
   * by jnxxhzz
   * @2019.04.03
  **/
?>

<?php include "contest_header.php" ?>

<?php // 根据当前是否需要滚屏给予show不同的style
  if ($_GET['scroll']) $showStyle = "margin-top:10px;height:1000px;overflow-y:scroll;overflow-x:scroll;";
  else $showStyle = "margin-top:10px;";
?>
<div id='container' class="am-container">
<div style=<?php echo $showStyle?> class='am-g' id='show'>

  <!-- 工具栏 start -->
  <div class='am-text-center'>
    <?php 
      if(HAS_PRI("download_ranklist")) echo "[ <a href='contestrank.php?cid=".$cid."&download_ranklist'>".$MSG_DOWNLOAD_RANK."</a> ]&nbsp;&nbsp;&nbsp;";
      // if (!$_GET['scroll'])
      //   echo "[ <a href='contestrank.php?scroll=true&cid=".$cid."'>Auto-scrolling</a> ]&nbsp;&nbsp;&nbsp;";
      // else 
      //   echo "[ <a href='contestrank.php?cid=".$cid."'>No-scrolling</a> ]&nbsp;&nbsp;&nbsp;";
    
      if(HAS_PRI('see_hidden_user_info')) {
        if ($real_name_mode) {
            echo "[ <a href='contestrank.php?cid=$cid'>Normal mode</a> ]";
        }
        else echo "[ <a href='contestrank.php?cid=$cid&real_name_mode'>Real Name Mode</a> ]";
      }
    ?>
    [Choose Level_System
      <select id="lv_system">
        <option value="" <?php if ($_GET['lv_system']=="") echo "selected"; ?> >显示全部</option>
        <?php
        // <option value="null" <php if ($_GET['class']=="null") echo "selected"; > >其它</option>
        // <!-- don't remove "其它" option to for loop, if both null and "null" exist, there will occur two options -->
        ?>
        <?php 
          $sz = count($classSet);
          for ($i=0; $i<$sz; $i++) {
            if ($classSet[$i]==null || $classSet[$i]=="null" || $classSet[$i]=="其它") continue; 
        ?>
            <option value="<?php echo $level_choose_name[$classSet[$i]]; ?>" <?php if ($_GET['lv_system']==$level_choose_name[$classSet[$i]]) echo "selected"; ?> ><?php echo $level_choose_name[$classSet[$i]]; ?></option>
        <?php
          }
        ?>
      </select>
    ]
    <!-- 选择班级后自动跳转页面的js代码 start -->
    <script type="text/javascript">
      var oSelect=document.getElementById("lv_system");
      oSelect.onchange=function() { //当选项改变时触发
        var valOption=this.options[this.selectedIndex].value; //获取option的value
        var url = window.location.search;
        var cid = <?php echo $cid?>;
        var real_name_mode = <?php echo $real_name_mode?"true":"false" ?>;
        var url = window.location.pathname+"?cid="+cid;
        if(real_name_mode) url += "&real_name_mode";
        url += "&lv_system="+valOption;
        window.location.href = url;
      }
    </script>
    <!-- 选择班级后自动跳转页面的js代码 end -->
  </div>
  <!-- 工具栏 end --> 

  <br />

  <!-- 排名表格 start -->
  <style type="text/css" media="screen">
    .rankcell{
      white-space: normal;
      overflow: hidden;
      padding-top: 1px !important;
      padding-bottom: 1px !important;
      padding-left: 4px;
      padding-right: 4px;
      vertical-align: middle !important;
      line-height: 1.4 !important;
      border-left: 1px solid #ddd;
    }
    .pcell{
      white-space: normal;
      overflow: hidden;
      padding: 1px !important;
      vertical-align: middle !important;
      line-height: 1.4 !important;
      border-left: 1px solid #ddd;
    }
    .has-num:hover{
      cursor: pointer;
    }
    .nick{
      /*max-width: 900px;*/
      width: 200px;
      min-width: 120px;
      height: 30px;
      white-space: nowrap;
      overflow: hidden;
      padding: 0px;
      padding-top: 5px;
      vertical-align: middle !important;
      line-height: 1.4 !important;
    }
    .pcell-ac{
      background: #aefeae;
    }
    .pcell-fb{
      color: white;
      background: #3db03d;
    }
    .pcell-wa{
      background: #ff6b6b;
    }
    .pcell-pd{
      background: #ccc;
    }
    .wa-times{
      font-size: 11px;
    }
    .ac-time{
      font-size: 12px;
    }
  </style>
  <table class="am-table" style='font-size:13px;' id="rank_table">
    <thead align="center" style="height: 30px;">
      <td style="width: 1%;" id="rank">Rank</td>
      <?php if($real_name_mode):?>
        <td style="width: 1%;" id="user">Stu. ID</td>
        <td style="width: 90%;" id="nick">Name</td>
      <?php else: ?>
        <td style="width: 1%;" id="user">User</td>
        <td style="width: 90%;" id="nick">Nick</td>
      <?php endif; ?>
      <td style="width: 1%;" id="solved">Score</td>
      <td style="width: 1%;" id="solved">Solved</td>
      <td style="width: 1%;" id="penalty">Penalty</td>
      <?php
        for ($i=0;$i<$pid_cnt;$i++)
          echo "<td id='p-cell-$i' style='min-width: 40px;'><a href=problem.php?cid=$cid&pid=$i>".PID($i)."</a></td>";
      ?>
    </thead>
    <tbody>
      <?php
        $rank=1;
        $num_gold=$first_prize;
        $num_silver=$second_prize;
        $num_bronze=$third_prize;
        for ($i=0;$i<$user_cnt;$i++){
          echo "<tr align=center>";
          $medal_class="";
          $medal_css="";
          if($rank==1){
            $medal_class="am-badge am-icon-trophy";
            $medal_css="background-color:#ce0000;";
          }
          else if($rank<=$num_gold){
            $medal_class="am-badge";
            $medal_css="background-color:#f8c100;";
          }
          else if($rank<=$num_gold+$num_silver){
            $medal_class="am-badge";
            $medal_css="background-color:#a4a4a4;";
          }
          else if($rank<=$num_gold+$num_silver+$num_bronze){
            $medal_class="am-badge";
            $medal_css="background-color:#815d18;";
          }
          echo "<td class='rankcell' style='border-left:0;'>";
          $uuid=htmlentities($U[$i]->user_id);
          $nick=htmlentities($U[$i]->nick);
          if($real_name_mode) {
              $col2=htmlentities($U[$i]->stu_id);
              $col3=htmlentities($U[$i]->class . "-". $U[$i]->real_name);
          }
          else {
              $col2=htmlentities($U[$i]->user_id);
              $col3=htmlentities($U[$i]->nick);
          }

          if(!isset($is_excluded[$uuid])) {
            echo "<span class='$medal_class' style='$medal_css'>";
            if($rank==1){
              echo " Winner";
            }
            else echo $rank;//名次变量
            echo "</span>";
            $rank++;
          }
          else 
            echo "*";
          echo "</td>";
          $uscore = $U[$i]->score;
          $usolved=$U[$i]->solved;
        echo "<td class='rankcell'>";
          echo "<a name=\"$uuid\" href=\"userinfo.php?user=$uuid\">$col2</a>";


          echo "<td class='rankcell'><div class='nick'>";
          if(isset($is_excluded[$uuid])) echo "<span>*</span>";
          echo "<a href=\"userinfo.php?user=$uuid\">".$col3."</a>";
          echo "</div></td>";
            echo "<td class='rankcell'><a href=\"status.php?user_id=$uuid&cid=$cid\">$uscore</a>";
          echo "<td class='rankcell'><a href=\"status.php?user_id=$uuid&cid=$cid\">$usolved</a>";


          echo "<td class='rankcell'>".floor($U[$i]->time/60);
          for ($j=0;$j<$pid_cnt;$j++){
            $cell_class="pcell ";
            if($U[$i]->is_unknown[$j]){
              $cell_class.="pcell-pd";
            }
            else if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0){
              if($uuid==$first_blood[$j]){
                $cell_class.="pcell-fb";
              }
              else{
                $cell_class.="pcell-ac";
              }
            }else if(isset($U[$i]->p_wa_num[$j])&&isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) {
              $cell_class.="pcell-wa";
            }
            $probelm_lable=PID($j);
            $data_toggle="";
            //echo "<pre>";
            
            //echo "</pre>";
            if($U[$i]->p_wa_num[$j]>0 || $U[$i]->p_ac_sec[$j]>0 || $U[$i]->try_after_lock[$j]>0){
              $cell_class.=" has-num";
              $data_toggle.="data-am-modal=\"{target: '#modal-submission', width:1000}\"";
            }
            echo "<td class='$cell_class' id='pcell $uuid $probelm_lable' $data_toggle>";
            if(isset($U[$i])){
              if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
                echo "<span class='ac-time'>".floor($U[$i]->p_ac_sec[$j]/60)."</span><br>";

              if ($U[$i]->try_after_lock[$j]){
                if(!isset($U[$i]->p_wa_num[$j])) $U[$i]->p_wa_num[$j]=0;
                echo "<span class='wa-times'>(".$U[$i]->p_wa_num[$j]."+".$U[$i]->try_after_lock[$j].")</span>";
              }
              else if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) 
                echo "<span class='wa-times'>(".$U[$i]->p_wa_num[$j].")</span>";
              else
                echo "-";
            }
            //echo "<br/>".$U[$i]->p_wa_num[$j]."-".$U[$i]->p_ac_sec[$j]."<br/>";
            echo "</td>";
          }
          echo "</tr>";
        }       
      ?>
    </tbody>
  </table>
  <!-- 排名表格 END -->
  <div class="am-modal am-modal-no-btn" tabindex="-1" id="modal-submission">
    <div class="am-modal-dialog">
      <div class="am-modal-hd">Submissions
        <a class="am-close am-close-spin" data-am-modal-close>&times;</a>
      </div>
      <div class="am-modal-bd" id="modal-submission-bd">
        <i class="am-icon-spinner am-icon-pulse"></i> Loading...
      </div>
    </div>
  </div>

</div>
</div>
<?php include "footer.php" ?>


<!-- auto set nick-cell's max-width START-->
<script>
  function change_max_width(){
    var p_cnt=<?php echo $pid_cnt ?>;
    var p_width=$("#p-cell-0").outerWidth();
    var c_width=$("#container").outerWidth();
    var else_width=$("#rank").outerWidth()+$("#user").outerWidth()+$("#solved").outerWidth()+$("#penalty").outerWidth();
    var left=c_width-p_cnt*p_width-else_width;
    left-=20;
    $(".nick").css({'width':left});
  }
  $(document).ready(function(){
    change_max_width();
  });
  $(window).resize(function(){
    change_max_width();
  });
</script>
<!-- auto set nick-cell's max-width END-->

<!-- set submission dialog contents START -->
<script>
  $("td[id^='pcell']").click(function(){
    var id=$(this).attr("id");
    var arg=id.split(' ');
    var uid=arg[1];
    var pid=arg[2];
    var cid=<?php echo $cid; ?>;
    //set loading icon
    $("#modal-submission-bd").html("<i class='am-icon-spinner am-icon-pulse'></i> Loading...");
    $.ajax({
      type: "GET",
      url: "status.php",
      data: {
        ranklist_ajax_query: 1,
        cid: cid,
        language: -1,
        jresult: -1,
        user_id: uid,
        problem_id: pid
      },
      context: this,
      success: function(data){
        $("#modal-submission-bd").html(data);
      },
      complete: function(){
        console.log("ajax complete!");
      },
      error: function(xmlrqst,info){
        console.log(info);
      }
    });
  });
</script>
<!-- set submission dialog contents END -->

