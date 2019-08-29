<?php
  /**
   * This file is created
   * by jnxxhzz
   * @2019.03.27
  **/
?>

<?php $title="User Info";?>
<?php
require_once("header.php");
?>
<style type="text/css">
  .first-col{
    width: 120px;
  }
</style>
<div class="am-container" style="margin-top:40px;">
  <!-- userinfo上半部分 start -->
  <h1>User Statics</h1><hr>
  <div class='am-g'>
    <!-- 左侧个人信息表格 start -->`
    <div class='am-u-md-4'>
      <form action="modify.php" method="post">
        <table class="am-table am-table-striped am-table-compact am-text-center">
          <tbody>
            <tr><td class="first-col">User ID</td><td><?php echo htmlentities($user)?></td></tr>
            <tr><td class="first-col">Nick Name</td><td><?php echo htmlentities($nick)?></td></tr>
            <tr><td class="first-col">Rank</td><td><?php echo $Rank?></td></tr>
            <tr><td class="first-col">Exp</td><td><?php echo round($strength)?></td></tr>
            <tr><td class="first-col">Level</td><td><?php echo $level?></td></tr>
            <tr><td class="first-col">Hard_Level</td><td><?php echo htmlentities($show_class) ?>
                <?php if ($who_open_this_page == $user): ?>
                <button class="am-btn-secondary" onclick="window.location.href='updateHard_level.php';return false;">申请解锁</button>
                <?php endif ?>
              </td>
            </tr>
            <tr>
              <td class="first-col">Total AC</td>
              <td><a href="status.php?user_id=<?php echo htmlentities($user)?>&jresult=4"><?php echo $local_ac?></a></td>
            </tr>
            <tr><td class="first-col">School</td><td><?php echo htmlentities($school)?></td></tr>
            <tr><td class="first-col">Email</td><td><a href="mailto:<?php echo htmlentities($email); ?>"><?php echo htmlentities($email)?></a></td></tr>
            <?php if (HAS_PRI("edit_user_profile")): ?>
              <?php require_once('./include/set_post_key.php');?>
              <input type="hidden" name="admin_mode" value="1">
              <input type="hidden" name="user_id" value="<?php echo htmlentities($user)?>">
              <tr><td colspan="2" class="am-danger">----The followings are  admin only----</td></tr>
              <tr>
                <td class="first-col" style="padding-top: 10px;">Student ID</td>
                <td>
                  <input class="am-form-field" name="stu_id" type="text" value="<?php echo htmlentities($stu_id)?>">
                </td>
              </tr>
              <tr>
                <td class="first-col" style="padding-top: 10px;">Real Name</td>
                <td>
                  <input class="am-form-field" name="real_name" type="text" value="<?php echo htmlentities($real_name)?>">
                </td>
              </tr>
              <tr>
                <td class="first-col" style="padding-top: 10px;">Class</td>
                <td>
                  <select name="class" data-am-selected="{searchBox: 1, maxHeight: 400, btnWidth:'100%'}">
                    <?php foreach ($classList as $c):?>
                      <option value="<?php echo $c?>" <?php if($c == $class) echo "selected"?>><?php echo $c?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
            <?php elseif (HAS_PRI("see_hidden_user_info")): ?>
              <tr><td colspan="2" class="am-danger">----The followings are  admin only----</td></tr>
              <tr><td class="first-col">Student ID</td><td><?php echo htmlentities($stu_id)?></td></tr>
              <tr><td class="first-col">Real Name</td><td><?php echo htmlentities($real_name)?></td></tr>
              <tr><td class="first-col">Class</td><td><?php echo htmlentities($class)?></td></tr>
            <?php endif ?>
          </tbody>
        </table>
        <?php if (HAS_PRI("edit_user_profile")): ?>
          <div class="am-text-center">
            <button class="am-btn am-btn-secondary">Submit</button>
          </div>
        <?php endif?>
      </form>
    </div>
    <!-- 左侧个人信息表格 end -->
     
    <!-- 个人图表信息 start -->
    <div class="am-u-md-4" >
      <div id="chart-sub" style="height: 327px; width: 100%;"></div>
    </div>
    <div class='am-u-md-4'>
      <?php
      // <!-- <label>用户评价</label><br> -->
      // <!-- <a href="charts/show_fore.php?user=<?php echo $_GET['user']>">用于教学的过程性评价详情请点这里</a> -->
      ?>
      <div id='chart' style='height:327px;width:100%'></div>
    </div>
   
    <!-- 个人图表信息 end -->
  </div>
  <!-- userinfo上半部分 end -->
  <hr />
  <div class="am-g">
    <div class="am-u-md-12">
      <!-- userinfo下半部分 start -->
      <?php if ($AC): ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">Solved Problems List <?php echo "($AC)" ?>:</div>
          <div class="am-panel-bd">
          <?php
            echo "<b>Solved:</b><br/>";
            $sql="SELECT set_name,set_name_show FROM problemset";
            $res=$mysqli->query($sql);
            echo "<div style='margin-left: 10px;'>";
            while($row=$res->fetch_array()){
              $set_name=$row['set_name'];
              $set_name_show=$row['set_name_show'];
              $cnt=count($ac_set[$set_name]);
              if($cnt){
                echo "$set_name_show($cnt):<br/>";

                echo "<div style='margin-left: 20px;'>";
                foreach ($ac_set[$set_name] as $pid) {
                  echo "<a href=problem.php?id=$pid> $pid </a>&nbsp;";
                }
                echo "</div>";
              }
            }
            echo "</div>";
            echo "<br />";
            echo "<div><b>Tried:</b></div>";
            foreach($hznu_unsolved_set as $i) {
              if ($i != 0) echo "<a href=problem.php?id=".$i."> ".$i." </a>&nbsp;";
            }
            echo "<br /><br />";

            //solution video START
            $sql="SELECT DISTINCT video_id FROM solution_video_watch_log WHERE user_id='$user'";
            $res=$mysqli->query($sql);
            $solution_video_set=array();
            while($id=$res->fetch_array()[0]){
              array_push($solution_video_set,$id);
            }
            if(count($solution_video_set)){
              echo "<div><b>Solution Video Watched:</b></div>";
              foreach ($solution_video_set as $id) {
                echo "<a href=problem.php?id=$id> $id </a>";
              }
              echo "<br /><br />";
            }
            //solution video END
            // if(count($hznu_recommend_set)){
            //   echo "<div><b>Recommended:</b></div>";
            //   foreach($hznu_recommend_set as $i) {
            //     echo "<a href=problem.php?id=".$i."> ".$i." </a>&nbsp;";
            //   }
            // }
          ?>
          </div>
        </div>
      <?php endif ?>
      <!-- userinfo下半部分 end -->
    </div>
  </div>
</div>
<?php require_once("footer.php") ?>
<!--<script src="charts/echarts.min.js"></script>-->
<script src="plugins/echarts/echarts.min.js"></script>


<?php
$chart_sub_data="";
for($i=4 ; $i<=11 ; ++$i){
  $sql="SELECT count(*) FROM solution WHERE result=$i AND user_id='{$_GET['user']}'";
  $res=$mysqli->query($sql);
  $cnt=$res->fetch_array()[0];
  $chart_sub_data.="{value: $cnt, name: '{$judge_result[$i]}'},";
}
?>
<script type="text/javascript">
var chart = echarts.init(document.getElementById('chart'));
var option = {
  title : {
    text: "总体评价：<?php echo $avg_score?>分",
    x : 'right',
    y : 'bottom',
  }, 
  tooltip : { trigger: 'axis',},
  calculable : true,
  radar : [
    {
      indicator : [
        {text: '题量', max:100},
        {text: '难度', max:100},
        {text: '活跃', max:100},
        {text: '独立', max:100}
      ],
    }
  ],
  series : [
    {
      name: 'User Info',
      type: 'radar',
      tooltip: { trigger: 'item' },
      itemStyle: { normal: { areaStyle: { type: 'default' } } },
      data : [
        {
          value:[ <?php echo $solved_score.",".$dif_score.",".$act_score.",".$idp_score; ?> ],
          name : '<?php echo $user?>'
        }
      ]
    }
  ]
};
chart.setOption(option);

var chart_sub=echarts.init(document.getElementById("chart-sub"));
option = {
    title : {
        text: "Submissions"
    },
    tooltip : {
        trigger: 'item',
        formatter: "{b} : {c} ({d}%)"  
    },
    color : [
      '#5EB95E', '#6b8e23', '#DD514C', '#F37B1D', '#b8860b', 
      '#ff69b4', '#ba55d3', '#6495ed', '#ffa500', '#40e0d0', 
      '#1e90ff', '#ff6347', '#7b68ee', '#00fa9a', '#ffd700', 
      '#ff00ff', '#3cb371', '#87cefa', '#30e0e0', '#32cd32' ,
    ],
    //color : ['#5EB95E','#DD514C'],
    series : [
        {
            name: 'Submissions in HZNUOJ',
            type: 'pie',
            data:[
                <?php echo $chart_sub_data; ?>
            ],
            itemStyle: {
                normal: {
                  label: {
                    show: false,
                    position: "inner",
                  },
                },
                emphasis: {
                    label: {
                      show: false,
                      position: "inner",
                    },
                },

            }
        }
    ]
};
chart_sub.setOption(option);

$(window).resize(function(){
  chart.resize();
  chart_sub.resize();
});
$(window).ready(function(){
  chart.resize();
  chart_sub.resize();
});
</script>

