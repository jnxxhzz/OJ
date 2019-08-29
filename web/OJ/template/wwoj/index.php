<?php
/**
 * by jnxxhzz
 * @2019.05.19
 **/
?>

<?php $title="Home";?>
<?php
require_once("header.php");
require_once('./include/db_info.inc.php');
?>

<style>
  /*.bg{
    background-image: url(template/hznu/ojINDEX.jpg);
    background-repeat: no-repeat;
    background-position: center 0px;
  }*/
  #slider {
    height: 300px;
    width: 1000px;
    margin-left: auto;
    margin-right: auto;
  }
</style>
<div class="am-container" style="margin-top:0px;">
  <div class="am-g ill" style="height: 70px;"></div>
  <!-- <div class="am-g ill" style="height: 20px;">
    <center><div class="link" style="cursor: pointer; height: 20px; width: 100px;"></div></center>
  </div> -->
  <!-- <div class="am-g ill" style="height: 30px;"></div> -->
  <div class='am-g'>
    <!-- 公告模块 start -->
    <div class="am-u-md-8">
      <div class="am-panel am-panel-primary" id="accordion0">
        <div class="am-panel-hd" class="am-panel-title">News</div>
        <div class="am-panel-collapse am-collapse am-in">
          <div class="am-panel-bd">
            <div class="am-panel-group" id="accordion">
                <?php
                $n = count($news_title);
                if ($n) { // 有公告的话
                    for ($i=0; $i<$n; ++$i) {
                        $nid=$news_id[$i];
                        if ($news_importance[$i] == 10) echo "<div class='am-panel am-panel-danger'>";
                        else if ($news_importance[$i] == 3) echo "<div class='am-panel am-panel-warning'>";
                        else if ($news_importance[$i] == 2) echo "<div class='am-panel am-panel-secondary'>";
                        else if ($news_importance[$i] == 1) echo "<div class='am-panel am-panel-success'>";
                        else echo "<div class='am-panel am-panel-default'>";
                        echo <<<HTML
                      <div class="am-panel-hd" class="am-panel-title" data-am-collapse="{target: '#news-$nid'}">
                        $news_title[$i]
                      </div>
                      <div id="news-$nid" class="am-panel-collapse am-collapse">
                        <div class="am-panel-bd">
                        </div>
                      </div>    
                    </div>  
HTML;
                    }
                }
                ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 公告模块 end -->
    
    
    <!--Submission Statics START-->
    <div class="am-u-md-4">
        <?php
        $sql="SELECT count(*) FROM solution WHERE in_date<=NOW() AND in_date>date_add(NOW(), interval -1 day)";
        $res=$mysqli->query($sql);
        $cnt=$res->fetch_array()[0];
        ?>
      <div class="am-panel am-panel-primary">
        <div class="am-panel-hd">Statics</div>
        <div id="submission_chart" style="width: 100%;height: 250px;"></div>
      </div>
    </div>
    <!--Submission Statics END-->
  </div>

<div class="am-avg-md-1">
    <table class="am-table">
      <thead>
      <th class='am-text-center'>ID</th>
      <th class='am-text-center'>Name</th>
      <th class='am-text-center'>Status</th>
      <th class='am-text-center'>Type</th>
      </thead>
      <tbody>
      <?php
      foreach($view_contest as $row){
          echo "<tr class='am-text-center'>";
          foreach($row as $table_cell){
              echo "<td>";
              echo $table_cell;
              echo "</td>";
          }
          echo "</tr>";
      }
      ?>
      </tbody>
    </table>
  </div>
  
</div>

</div>
<?php require_once("footer.php") ?>

<script type="text/javascript">
    $('div.link').click(function(){
        window.open('http://www.pixiv.net/member_illust.php?mode=medium&illust_id=13212258');
        //window.location.href="http://pixiv.net/member.php?id=430651";
    });
</script>



<script type="text/javascript" src="/OJ/plugins/echarts/echarts.min.js"></script>
<!-- <script src="//cdn.bootcss.com/echarts/3.2.3/echarts.min.js"></script> -->
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var submission_chart = echarts.init(document.getElementById('submission_chart'));
    submission_chart.showLoading();
</script>


<!--get charts json START-->
<?php
$tot_days=20;
$series_not_ac_data="";
$series_ac_data="";
$series_hits_data="";
$xAxis_data="";
for($i=$tot_days-1 ; $i>=0 ; --$i){
    
    
    $sql="SELECT count(1) FROM solution WHERE in_date<=date_add(date(NOW()), interval -$i+1 day) AND in_date>date_add(date(NOW()), interval -$i day) AND result != 4";
    $res=$mysqli->query($sql);
    $cnt=$res->fetch_array()[0];
    $series_not_ac_data.="$cnt,";
    
    $sql="SELECT count(1) FROM solution WHERE in_date<=date_add(date(NOW()), interval -$i+1 day) AND in_date>date_add(date(NOW()), interval -$i day) AND result = 4";
    $res=$mysqli->query($sql);
    $cnt=$res->fetch_array()[0];
    $series_ac_data.="$cnt,";
    
    $sql="SELECT count(1) FROM hit_log WHERE time<=date_add(date(NOW()), interval -$i+1 day) AND time>date_add(date(NOW()), interval -$i day)";
    $res=$mysqli->query($sql);
    $cnt=$res->fetch_array()[0];
    $series_hits_data.="$cnt,";
    
    if($i==0)$xAxis_data.="'today',";
    else if($i==1) $xAxis_data.="'yesterday',";
    else $xAxis_data.="'".(-$i+1)." days',";
}
?>
<!--get charts json END-->


<script type="text/javascript">
    $(document).ready(function(){
        // 指定图表的配置项和数据
        var option_submission = {
            grid: {
                x: 65,
                x2: 70
            },
            color: ['#3398DB','#5EB95E','grey'],
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                },
                formatter : function(params){
                    var tot=params[0].data+params[1].data;
                    var ac=params[1].data;
                    var hits=params[2].data;
                    return "AC: "+ac+"<br>Total: "+tot+"<br/>Hits: "+hits;
                }
            },
            legend: {
                show: false,
                data:['submissions']
            },
            xAxis: {
                type: 'category',
                data: [<?php echo $xAxis_data; ?>]
            },
            yAxis: [
                {
                    type : "value",
                    name : "Submissions"
                },
                {
                    splitLine: false,
                    type : "value",
                    name : "Hits"
                }
            ],
            series: [
                {
                    name: 'Submissions',
                    type: 'bar',
                    stack: 'total',
                    data: [<?php echo $series_not_ac_data; ?>]
                },
                {
                    name: 'AC',
                    type: 'bar',
                    stack: 'total',
                    data: [<?php echo $series_ac_data; ?>]
                },
                {
                    name: 'Hits',
                    type: 'line',
                    yAxisIndex: 1,
                    data: [<?php echo $series_hits_data; ?>]
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        submission_chart.setOption(option_submission);
        submission_chart.hideLoading();
    });
    $(window).resize(function(){
        submission_chart.resize();
    });
    $(window).ready(function(){
        submission_chart.resize();
    });
</script>

<script type="text/javascript">
    var has_load=[];
    $("div[id^='news-']").on('open.collapse.amui', function() {

        var id=$(this).attr("id");
        var news_id=id.substring(5);
        if(has_load[news_id])return;

        //begin load
        $("i#news-load-icon-"+news_id).show(0);
        $.when($.ajax({
            type: "GET",
            url: "get_news.php",
            data: {
                id: news_id
            },
            context: this,
            success: function(data){
                $(this).find("div.am-panel-bd").html(data);
                has_load[news_id]=true;
                //console.log($(this).attr("id"));
            },
            complete: function(){
                console.log("ajax complete!");
            },
            error: function(xmlrqst,info){
                console.log(info);
            }
        })).done(function(){
            $("i#news-load-icon-"+news_id).hide(0);
        });

    }).on('close.collapse.amui', function() {
        console.log('折叠菜单关闭鸟！');
    });
</script>
<!-- modal auto jump START -->
<!-- <div class="am-modal am-modal-no-btn" tabindex="-1" id="index_ad_modal">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">News
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <a href="hznu_programming_contest_2016" title="">
        <img class="am-img-responsive" src="image/OJ_contest_2016_landspace.jpg?version=3" alt="">
      </a>
    </div>
  </div>
</div> -->
<!-- <script src="AmazeUI/js/jquery.session.js"></script>
<script>
  var $objIndexAd=$("#index_ad_modal");
  if($.session.get("index_ad_do_not_show")!="1"){
    $objIndexAd.modal();
    $.session.set("index_ad_do_not_show","1");
  }
</script> -->
<!-- modal auto jump END -->
