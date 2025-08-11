
<!-- #adm_ new_revisit -->

<div id="sh_adm_new_revisit">
<script src="<?php echo G5_ADMIN_URL ?>/adm_include/main_new_revisit/js/morris.js"></script> 
<script src="<?php echo G5_ADMIN_URL ?>/adm_include/main_new_revisit/js/raphael-min.js"></script> 
<?php
$sql_common =" select vs_date, vs_count as tot from {$g5['visit_sum_table']} where vs_date between";

// 오늘 
$sql = "$sql_common '" . date("Y-m-d") . "' and '" . date("Y-m-d") . "'";
$result = sql_fetch($sql);

// 어제 
$sql = "$sql_common '" . date("Y-m-d",strtotime("-1 day")) . "' and '" . date("Y-m-d",strtotime("-1 day")) . "'";
$result_1 = sql_fetch($sql);

// -2일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-2 day")) . "' and '" . date("Y-m-d",strtotime("-2 day")) . "'";
$result_2 = sql_fetch($sql);

// -3일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-3 day")) . "' and '" . date("Y-m-d",strtotime("-3 day")) . "'";
$result_3 = sql_fetch($sql);

// -4일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-4 day")) . "' and '" . date("Y-m-d",strtotime("-4 day")) . "'";
$result_4 = sql_fetch($sql);

// -5일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-5 day")) . "' and '" . date("Y-m-d",strtotime("-5 day")) . "'";
$result_5 = sql_fetch($sql);

// -6일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-6 day")) . "' and '" . date("Y-m-d",strtotime("-6 day")) . "'";
$result_6 = sql_fetch($sql); 
?>
<?php
$sql_common =" select count(*) as cnt from {$g5['member_table']} where mb_datetime between ";

// 오늘 회원가입수
$sql = "$sql_common '" . date("Y-m-d") . "' and '" . date("Y-m-d") . "'";
$mb_re = sql_fetch($sql);

// 어제 회원가입수
$sql = "$sql_common '" . date("Y-m-d",strtotime("-1 day")) . "' and '" . date("Y-m-d",strtotime("-1 day")) . "'";
$mb_re1 = sql_fetch($sql);

// -2일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-2 day")) . "' and '" . date("Y-m-d",strtotime("-2 day")) . "'";
$mb_re2 = sql_fetch($sql);

// -3일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-3 day")) . "' and '" . date("Y-m-d",strtotime("-3 day")) . "'";
$mb_re3 = sql_fetch($sql);

// -4일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-4 day")) . "' and '" . date("Y-m-d",strtotime("-4 day")) . "'";
$mb_re4 = sql_fetch($sql);

// -5일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-5 day")) . "' and '" . date("Y-m-d",strtotime("-5 day")) . "'";
$mb_re5 = sql_fetch($sql);

// -6일
$sql = "$sql_common '" . date("Y-m-d",strtotime("-6 day")) . "' and '" . date("Y-m-d",strtotime("-6 day")) . "'";
$mb_re6 = sql_fetch($sql); 
?>
    <h3>신규방문 vs 신규회원 (최근 7일)<a href=""></a></h3>
    
    <div id="graph_wrap"></div>
 
	<script>
    Morris.Bar({
      element: 'graph_wrap',
      data: [
        { y: '<?php echo date("d일",strtotime("-6 days"));?>', a: <?php echo number_format($result_6['tot']);?>, b: <?php echo number_format($mb_re6['cnt']);?> },
        { y: '<?php echo date("d일",strtotime("-5 days"));?>', a: <?php echo number_format($result_5['tot']);?>, b: <?php echo number_format($mb_re5['cnt']);?> },
        { y: '<?php echo date("d일",strtotime("-4 days"));?>', a: <?php echo number_format($result_4['tot']);?>, b: <?php echo number_format($mb_re4['cnt']);?> },
        { y: '<?php echo date("d일",strtotime("-3 days"));?>', a: <?php echo number_format($result_3['tot']);?>, b: <?php echo number_format($mb_re3['cnt']);?> },
        { y: '<?php echo date("d일",strtotime("-2 days"));?>', a: <?php echo number_format($result_2['tot']);?>, b: <?php echo number_format($mb_re2['cnt']);?> },
        { y: '<?php echo date("d일",strtotime("-1 days"));?>', a: <?php echo number_format($result_1['tot']);?>, b: <?php echo number_format($mb_re1['cnt']);?> },
        { y: '<?php echo date("오늘",strtotime("today"));?>', a: <?php echo number_format($result['tot']);?>, b: <?php echo number_format($mb_re['cnt']);?> }
      ],
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['신규', '회원']
    });
    </script>

</div>
<!-- #adm_ new_revisit -->
