
<!-- #adm_visit_time -->    

<script src="<?php echo G5_ADMIN_URL ?>/adm_include/main_visitor_time/js/Easygraphs.js"></script> 

<div id="sh_adm_visitor_time">
<?php
$fr_date = date("Y-m-d",strtotime("-2 day"));
$to_date = date("Y-m-d");

$sum_count = 0;
$arr = array();

$sql = " select vi_date, SUBSTRING(vi_time,1,2) as vi_hour, count(vi_id) as cnt
            from {$g5['visit_table']}
            where vi_date between '{$fr_date}' and '{$to_date}'
            group by vi_date, vi_hour
            order by vi_date, vi_hour ";

$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $arr[$row['vi_date']][$row['vi_hour']] = $row['cnt'];
    $sum_count += $row['cnt'];
}
?>

    <h3>시간별 방문자 현황 (최근 3일)<a href="<?php echo G5_ADMIN_URL ?>/visit_hour.php"></a></h3>
    
    <div class="graph_wrap">
        <div id="visitor_graph"></div>
        <script>
        var eg2 = new Easygraphs({
          container: document.getElementById('visitor_graph'),
          width: 550,
          height: 160,
          padding: {
            top: 30,
            right: 30,
            left: 65
          },
          tooltip: {
            template: '{{ category }}: {{ value }}',
            widthAuto: true,
            color: '#222',
            background: '#FFF'
          },
          yAxis: {
            text: {
              toFixed: 0
            },
            title: {
              text: '방문자수'
            },
            grid: {
              show: false
            }
          },
          xAxis: {
            labels: [<?php $k = 0; if ($i) { for ($i=0; $i<24; $i++) { $hour = sprintf("%02d", $i); $count = isset($arr[$hour]) ? (int) $arr[$hour] : 0; $rate = ($count / $sum_count * 100); $s_rate = number_format($rate, 1); ?> '<?php echo $hour?>', <?php } } ?>]
          },
          data: [
            {
            name: '오늘',
            dots: {
              color: 'rgb(3, 169, 245)'
            },
            line: {
              width: 1,
              color: 'rgb(3, 169, 245)'
            },
              values: [<?php $k = 0; if ($i) { $date = date("Y-m-d"); for ($i=0; $i<24; $i++) { $hour = sprintf("%02d", $i); $count = isset($arr[$date][$hour]) ? (int) $arr[$date][$hour] : 0; $rate = ($count / $sum_count * 100); $s_rate = number_format($rate, 1); ?> <?php echo $count?>, <?php } } ?>]
            },
            {
            name: '어제',
            dots: {
              color: 'rgb(255, 140, 4)'
            },
            line: {
              width: 1,
              color: 'rgb(255, 140, 4)'
            },
              values: [<?php $k = 0; if ($i) { $date = date("Y-m-d",strtotime("-1 day")); for ($i=0; $i<24; $i++) { $hour = sprintf("%02d", $i); $count = isset($arr[$date][$hour]) ? (int) $arr[$date][$hour] : 0; $rate = ($count / $sum_count * 100); $s_rate = number_format($rate, 1); ?> <?php echo $count?>, <?php } } ?>]
            },
            {
            name: '엊그제',
            dots: {
              color: 'rgb(97, 108, 119)'
            },
            line: {
              width: 1,
              color: 'rgb(97, 108, 119)'
            },
            values: [<?php $k = 0; if ($i) { $date = date("Y-m-d",strtotime("-2 day")); for ($i=0; $i<24; $i++) { $hour = sprintf("%02d", $i); $count = isset($arr[$date][$hour]) ? (int) $arr[$date][$hour] : 0; $rate = ($count / $sum_count * 100); $s_rate = number_format($rate, 1); ?> <?php echo $count?>, <?php } } ?>]
            }
          ]
        });
        eg2.render();
        </script>
    </div>

</div>

<!-- #adm_visit_time -->
