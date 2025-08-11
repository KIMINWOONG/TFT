
<!-- #adm_visit_url -->

<div id="sh_adm_visitor_url">

<?php
$colspan = 4;

$max = 0;
$sum_count = 0;
$arr = array();

$sql = " select * from {$g5['visit_table']} 
            where vi_date >= '".date("Y-m-d",strtotime("-6 days") ) ."' ";            
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {
    $str = $row['vi_referer'];
    preg_match("/^http[s]*:\/\/([\.\-\_0-9a-zA-Z]*)\//", $str, $match);
    $s = isset($match[1]) ? $match[1] : 0;
    $s = preg_replace("/^(www\.|search\.|dirsearch\.|dir\.search\.|dir\.|kr\.search\.|myhome\.)(.*)/", "\\2", $s);

    if( isset($arr[$s]) ){
        $arr[$s]++;
    } else {
        $arr[$s] = 1;
    }

    if ($arr[$s] > $max) $max = $arr[$s];

    $sum_count++;
}
?>
<h3>접속 경로별 방문자 현황 (최근 7일)<a href="<?php echo G5_ADMIN_URL ?>/visit_domain.php"></a></h3>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <colgroup>
        <col width="21%" />
        <col width="" />
        <col width="14%" />
        <col width="14%" />
    </colgroup>
    <tr>
        <th scope="col"><p>접속 도메인</p></th>
        <th scope="col"><p>그래프</p></th>
        <th scope="col"><p>방문자</p></th>
        <th scope="col"><p>비율(%)</p></th>
    </tr>

    <?php
    $i = 0;
    $k = 0;
    $save_count = -1;
    $tot_count = 0;
    if (count($arr)) {
        arsort($arr);
        $j = 0;
        foreach ($arr as $key=>$value) {
            $count = $arr[$key];
            $j++;
            if ($save_count != $count) {
                $i++;
                $no = $i;
                $save_count = $count;
            } else {
                $no = '';
            }

            if (!$key) {
                $link = '';
                $link2 = '';
                $key = '직접';
            }

            $rate = ($count / $sum_count * 100);
            $s_rate = number_format($rate, 1);

    ?>  
            <tr>
        <td><?php echo $key ?></td>
        <td>
            <div class="visit_bar graph<?php echo $j ?>">
                <span style="width:0%"></span>
            </div>        
        </td>
        <td><?php echo $count ?></td>
        <td><?php echo $s_rate ?></td>
    <script>
        $('.graph<?php echo $j ?> span').delay(0).animate({"width":"<?php echo $s_rate ?>%"},700); 
    </script>	
    </tr>

    <?php
        }
    } else {
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    }
    ?>   
    
        </table>

</div>

<!-- #adm_visit_url -->
