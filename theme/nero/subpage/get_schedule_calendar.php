<?php
include "../../../common.php";

$date = empty($_GET['date']) ? date('Y-m-d') : $_GET['date'];
$firstDate = date('Y-m-01',strtotime($date));
$firstDateWcode = date('w',strtotime($firstDate));
$lastDateDay = date('t', strtotime($date));
$lastDateWcode = date('w',strtotime( date('Y-m-'.$lastDateDay,strtotime($date)) ));

$prevDate = date('Y-m-d',strtotime("-1 month",strtotime($firstDate)));
$nextDate = date('Y-m-d',strtotime("+1 month",strtotime($firstDate)));
$prevLastDateDay = date('t', strtotime($prevDate));

$week = array();
$day = 1;
while(1){
	$subWeek = array();
	for($i=0;$i<7;$i++){
		if( $day > $lastDateDay){ 

			// 마지막 날의 날짜코드가 6이 아니라면, 다음달을 미리 넣어준다.
			if( $lastDateWcode != 6){
				for($si=0;$si < 6-$lastDateWcode; $si++){
					$thisDate = date('Y-m-'.sprintf("%02d",($si+1)),strtotime("+1 month",strtotime($firstDate)));
					$subWeek[] = array('type'=>'next', 'day'=>$si+1,'date'=>$thisDate,'wcode'=>date('w',strtotime($thisDate)));		
				}				
			}
			break; 
		}
		if( $day == 1 && $firstDateWcode != 0){
			for($si=($prevLastDateDay-$firstDateWcode);$si < $prevLastDateDay; $si++){
				$thisDate = date('Y-m-'.sprintf("%02d",($si+1)),strtotime("-1 month",strtotime($firstDate)));
				$subWeek[] = array('type'=>'prev', 'day'=>$si+1,'date'=>$thisDate,'wcode'=>date('w',strtotime($thisDate)));
			}
			$i += $firstDateWcode;
		}

		$thisDate = date('Y-m-'.sprintf("%02d",$day),strtotime($firstDate));
		$subWeek[] = array('type'=>'now', 'day'=>$day,'date'=>$thisDate,'wcode'=>date('w',strtotime($thisDate)));		
		$day++;
	}
	$week[] = $subWeek;
	if( $day > $lastDateDay){ break; }
}
?>
        <div class="cal_title">
            <div class="arrow_left arrow2">
                <a href="javascript:get_schedule('<?=$prevDate?>')"><img src="<?php echo G5_THEME_IMG_URL ?>/left_icon.png" alt=""></a>
            </div>
            <h2><?=date("Y년 m월", strtotime($date))?> 학술일정</h2>
            <div class="arrow_right arrow2">
                <a href="javascript:get_schedule('<?=$nextDate?>')"><img src="<?php echo G5_THEME_IMG_URL ?>/right_icon.png" alt=""></a>
            </div>
        </div>

        <table>
            <tr>
                <th class="color_r">일</th>
                <th>월</th>
                <th>화</th>
                <th>수</th>
                <th>목</th>
                <th>금</th>
                <th class="color_b">토</th>
            </tr>
<?php
foreach($week as $k=>$v){
?>
			<tr>
<?php
	foreach($v as $sk=>$sv){
        $schedules = array();
		$day_class="";
		//오늘이전 날짜 비활성화
		$d=$sv['date'];
		if($sv['type']!="now"){
			$day_class="color_g";
		}
		if($day_class==""){
			if($sv['wcode']=="0"){
				$day_class="color_r";
			}elseif($sv['wcode']=="6"){
				$day_class="color_b";
			}
			$sql = "SELECT sc_title,sc_gubun FROM g5_schedule WHERE sc_date = '{$d}' ORDER BY sc_title";
			$result = sql_query($sql);
			while ($row = sql_fetch_array($result)) {
				if($row['sc_gubun']=="1"){
					$url="/theme/nero/subpage/event/event2_01.php";
				}else{
					$url="/theme/nero/subpage/event/event1_01.php";
				}
				$schedules[] = '<a href="'.$url.'">'.$row['sc_title'].'</a>';
			}
			if(count($schedules)>0){
				$day_class="color_schedule";
			}
		}
?>
				<td class="<?=$day_class?>">
					<?=$sv['day']?>
					<div class="speech-bubble"><?=implode("<br>",$schedules)?></div>
				</td>
<?
	}
?>
            </tr>
<?php
}
?>
        </table>
