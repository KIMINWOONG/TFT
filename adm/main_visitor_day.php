
<!-- #adm_visit_week --> 
    
<script src="<?php echo G5_ADMIN_URL ?>/adm_include/main_visitor_day/js/Easygraphs.js"></script> 

<div id="sh_adm_visitor_day">
<?php
$today = time();
$week = date("w");

$week_first = $today-($week*86400);

$fr_date = date("Y-m-d",$week_first-(86400*21));
$to_date = date("Y-m-d",$today);

$weekday = array ('일', '월', '화', '수', '목', '금', '토');

$sum_count = 0;
$arr = $week_arr = array();

$sql = " select * 
            from {$g5['visit_sum_table']}
            where vs_date between '{$fr_date}' and '{$to_date}'
            group by vs_date
            order by vs_date ";      

$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $arr[$row['vs_date']] = $row['vs_count'];
    $week_arr[date("w",strtotime($row['vs_date']))] += $row['vs_count'];

    $sum_count += $row['vs_count'];
}
?>

<h3>요일별 방문자 현황 (최근 4주)<a href="<?php echo G5_ADMIN_URL ?>/visit_week.php"></a></h3>

    <div class="graph_wrap">
          
        <div id="visitor_graph_day"></div>

        <script>
        var eg2 = new Easygraphs({
          container: document.getElementById('visitor_graph_day'),
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

            labels: [<?php for ($i=0; $i<7; $i++) { $count = isset($week_arr[$i]) ? (int) $week_arr[$i] : 0; $rate = ($count / $sum_count * 100); $s_rate = round($rate, 1); ?> '<?php echo $weekday[$i]?> (<?php echo $s_rate ?>%)', <?php } ?>]
          
          },
          data: [
            {
            name: '이번주',
            dots: {
              color: 'rgb(3, 169, 245)'
            },
            line: {
              width: 1,
              color: 'rgb(3, 169, 245)'
            },
              values: [<?php for ($d=$week_first; $d<($week_first+86400*7); $d+=86400) { $count = $arr[date("Y-m-d",$d)]; if(!$count) $count=0; ?> '<?php echo $count?>', <?php } ?>]
            },
            {
            name: '저번주',
            dots: {
              color: 'rgb(255, 140, 4)'
            },
            line: {
              width: 1,
              color: 'rgb(255, 140, 4)'
            },
              values: [<?php for ($d=($week_first-86400*7); $d<($week_first); $d+=86400) { $count = $arr[date("Y-m-d",$d)]; if(!$count) $count=0; ?> '<?php echo $count?>', <?php } ?>]
            },
            {
            name: '2주전',
            dots: {
              color: 'rgb(97, 108, 119)'
            },
            line: {
              width: 1,
              color: 'rgb(97, 108, 119)'
            },
              values: [<?php for ($d=($week_first-86400*14); $d<($week_first-86400*7); $d+=86400) { $count = $arr[date("Y-m-d",$d)]; if(!$count) $count=0; ?> '<?php echo $count?>', <?php } ?>]
            },
			{
            name: '3주전',
            dots: {
              color: 'rgb(156, 177, 196)'
            },
            line: {
              width: 1,
              color: 'rgb(156, 177, 196)'
            },
              values: [<?php for ($d=($week_first-86400*21); $d<($week_first-86400*14); $d+=86400) { $count = $arr[date("Y-m-d",$d)]; if(!$count) $count=0; ?> '<?php echo $count?>', <?php } ?>]
            }

          ]
        });
        eg2.render();
        </script>

    </div>

</div>
<!-- #adm_visit_week -->
