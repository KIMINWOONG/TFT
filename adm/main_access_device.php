
<!-- # adm_device -->
<script src="<?php echo G5_ADMIN_URL ?>/adm_include/main_access_device/js/doughnutChart.js"></script> 

<?php

$max = 0;
$sum_count = 0;
$arr = array();

$sql = " select * from {$g5['visit_table']} where vi_date >= '".date("Y-m-d",strtotime("-6 days") ) ."' ";          
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {
    $s = $row['vi_device'];
    if(!$s)
        $s = 'etc';

    if( isset($arr[$s]) ){
        $arr[$s]++;
    } else {
        $arr[$s] = 1;
    }

    if ($arr[$s] > $max) $max = $arr[$s];

    $sum_count++;
}
?>
<script>
$(function(){
  $("#doughnutChart_device").drawDoughnutChart([
    <?php
    $i = 0;
    $k = 0;
    $save_count = -1;
    $tot_count = 0;

    if (count($arr)) {
      // 색상배열지정
      $arr_colors = array("#03a9f5","#616c77");
      // 증가 idx 값
      $idx = 0;
              arsort($arr);
              foreach ($arr as $key=>$value) {
                  $count = $arr[$key];
                  if ($save_count != $count) {
                      $i++;
                      $no = $i;
                      $save_count = $count;
                  } else {
                      $no = '';
                  }
                  if (!$key) {
                      $key = 'etc';
                  }
                  $rate = ($count / $sum_count * 100);
                  $s_rate = number_format($rate, 1);
          ?>      
    { title:"<?php echo $key; ?>", value:<?php echo $count ?>, color:"<?=$arr_colors[$idx]?>" },
    <?php
$idx++;    
        }
    }
    ?>
  ]);
});
</script>

<div id="main_access_device" <?php echo $sum_count ?>>
    <h3>접속 플랫폼 (모바일,태블릿,PC)<a href="<?php echo G5_ADMIN_URL ?>/visit_device.php"></a></h3>
	<div id="doughnutChart_device" class="chart_device">
    	<ul>      
    <?php
    $i = 0;
    $k = 0;
    $save_count = -1;
    $tot_count = 0;
    if (count($arr)) {
        arsort($arr);
        foreach ($arr as $key=>$value) {
            $count = $arr[$key];
            if ($save_count != $count) {
                $i++;
                $no = $i;
                $save_count = $count;
            } else {
                $no = '';
            }

            if (!$key) {
                $key = '기타';

            }

            $rate = ($count / $sum_count * 100);
            $s_rate = number_format($rate, 1);
    ?>     
        	<li class="<?php echo $key; ?>"><?php echo $key; ?> : <?php echo $count ?></li>         
    <?php
        }
    }
    ?>
        </ul>  
    </div>

</div>

<!-- # adm_device -->
