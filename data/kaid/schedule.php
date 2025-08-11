<?php
$sub_menu = "600110";
require_once './_common.php';
auth_check_menu($auth, $sub_menu, 'r');

// 현재 년월 설정
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

// 이전/다음 월 계산
$prev_year = $month == 1 ? $year - 1 : $year;
$prev_month = $month == 1 ? 12 : $month - 1;
$next_year = $month == 12 ? $year + 1 : $year;
$next_month = $month == 12 ? 1 : $month + 1;

// 해당 월의 첫날과 마지막날
$first_day = mktime(0, 0, 0, $month, 1, $year);
$last_day = date('t', $first_day);
$start_week = date('w', $first_day);

// 일정 데이터 가져오기
$schedule_data = array();
$sql = "SELECT sc_date, sc_title FROM g5_schedule WHERE YEAR(sc_date) = '$year' AND MONTH(sc_date) = '$month' ORDER BY sc_date";
$result = sql_query($sql);
while ($row = sql_fetch_array($result)) {
    $day = (int)date('d', strtotime($row['sc_date']));
    if (!isset($schedule_data[$day])) {
        $schedule_data[$day] = array();
    }
    $schedule_data[$day][] = $row['sc_title'];
}

// AJAX 처리
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $sc_date = $_POST['sc_date'];
        $sc_title = $_POST['sc_title'];
		$sc_gubun = $_POST['sc_gubun'];
        
        $sql = "INSERT INTO g5_schedule (sc_date, sc_title, sc_gubun) VALUES ('$sc_date', '".addslashes($sc_title)."','$sc_gubun')";
        sql_query($sql);
        
        echo json_encode(array('status' => 'success'));
        exit;
    }
    
    if ($_POST['action'] == 'delete') {
        $sc_date = $_POST['sc_date'];
        $sc_title = $_POST['sc_title'];
        
        $sql = "DELETE FROM g5_schedule WHERE sc_date = '$sc_date' AND sc_title = '".addslashes($sc_title)."'";
        sql_query($sql);
        
        echo json_encode(array('status' => 'success'));
        exit;
    }
    
    if ($_POST['action'] == 'get_schedules') {
        $sc_date = $_POST['sc_date'];
        
        $sql = "SELECT sc_title FROM g5_schedule WHERE sc_date = '$sc_date' ORDER BY sc_title";
        $result = sql_query($sql);
        $schedules = array();
        while ($row = sql_fetch_array($result)) {
            $schedules[] = $row['sc_title'];
        }
        
        echo json_encode($schedules);
        exit;
    }
}
$g5['title'] = '학술일정';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<style>
.calendar-container { max-width: 1200px; margin: 20px auto; }
.calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; }
.calendar-nav { background: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; text-decoration: none; }
.calendar-nav:hover { background: #0056b3; color: white; text-decoration: none; }
.calendar-title { font-size: 24px; font-weight: bold; color: #333; }
.calendar-table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.calendar-table th { background: #007bff; color: white; padding: 15px; text-align: center; font-weight: bold; }
.calendar-table td { border: 1px solid #ddd; width: 14.28%; height: 120px; vertical-align: top; padding: 5px; position: relative; cursor: pointer; }
.calendar-table td:hover { background: #f0f8ff; }
.calendar-day { font-weight: bold; margin-bottom: 5px; }
.calendar-schedule { font-size: 11px; background: #e3f2fd; margin: 1px 0; padding: 2px 4px; border-radius: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer; }
.calendar-schedule:hover { background: #bbdefb; }
.today { background: #fff3cd !important; }
.other-month { color: #ccc; background: #f8f9fa; }
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
.modal-content { background-color: white; margin: 10% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 500px; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
.close:hover { color: black; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
.form-group input[type="text"], .form-group input[type="date"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
.btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; }
.btn-primary { background: #007bff; color: white; }
.btn-primary:hover { background: #0056b3; }
.btn-secondary { background: #6c757d; color: white; }
.btn-secondary:hover { background: #545b62; }
.schedule-list { max-height: 300px; overflow-y: auto; }
.schedule-item { display: flex; justify-content: space-between; align-items: center; padding: 8px; border: 1px solid #ddd; margin-bottom: 5px; border-radius: 4px; }
.delete-btn { background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 3px; cursor: pointer; font-size: 12px; }
.delete-btn:hover { background: #c82333; }
</style>

<div class="calendar-container">
    <div class="calendar-header">
        <a href="?year=<?php echo $prev_year; ?>&month=<?php echo $prev_month; ?>" class="calendar-nav">◀ 이전</a>
        <div class="calendar-title"><?php echo $year; ?>년 <?php echo $month; ?>월</div>
        <a href="?year=<?php echo $next_year; ?>&month=<?php echo $next_month; ?>" class="calendar-nav">다음 ▶</a>
    </div>
    
    <table class="calendar-table">
        <thead>
            <tr>
                <th>일</th>
                <th>월</th>
                <th>화</th>
                <th>수</th>
                <th>목</th>
                <th>금</th>
                <th>토</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $current_day = 1;
            $today = date('Y-m-d');
            
            for ($week = 0; $week < 6; $week++) {
                echo "<tr>";
                
                for ($day_of_week = 0; $day_of_week < 7; $day_of_week++) {
                    if ($week == 0 && $day_of_week < $start_week) {
                        echo "<td class='other-month'></td>";
                    } elseif ($current_day > $last_day) {
                        echo "<td class='other-month'></td>";
                    } else {
                        $current_date = sprintf("%04d-%02d-%02d", $year, $month, $current_day);
                        $is_today = ($current_date == $today) ? 'today' : '';
                        
                        echo "<td class='$is_today' data-date='$current_date'>";
                        echo "<div class='calendar-day'>$current_day</div>";
                        
                        if (isset($schedule_data[$current_day])) {
                            foreach ($schedule_data[$current_day] as $schedule) {
                                echo "<div class='calendar-schedule' title='".htmlspecialchars($schedule)."'>".htmlspecialchars($schedule)."</div>";
                            }
                        }
                        
                        echo "</td>";
                        $current_day++;
                    }
                }
                
                echo "</tr>";
                
                if ($current_day > $last_day) break;
            }
            ?>
        </tbody>
    </table>
</div>

<!-- 일정 추가/조회 모달 -->
<div id="scheduleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">일정 관리</h3>
            <span class="close">&times;</span>
        </div>
        
        <div class="form-group">
            <label>날짜:</label>
            <input type="date" id="scheduleDate" readonly>
        </div>
        
        <div class="form-group">
            <label>일정 제목:</label>
            <input type="text" id="scheduleTitle" placeholder="일정을 입력하세요">
        </div>

        <div class="form-group" style="display:flex;">
            <input type="radio" name="scheduleGubun" id="gubun_1" value="2"> <label for="gubun_1"> 학술대회</label> &nbsp;&nbsp;&nbsp;
			<input type="radio" name="scheduleGubun" id="gubun_2" value="1"> <label for="gubun_2"> 집담회</label>
        </div>

        <div style="margin-bottom: 20px;">
            <button class="btn btn-primary" onclick="addSchedule()">일정 추가</button>
            <button class="btn btn-secondary" onclick="closeModal()">닫기</button>
        </div>
        
        <div id="scheduleList" class="schedule-list"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // 날짜 셀 클릭 이벤트
    $('.calendar-table td').not('.other-month').click(function() {
        var date = $(this).data('date');
        if (date) {
            openModal(date);
        }
    });
    
    // 모달 닫기
    $('.close').click(function() {
        closeModal();
    });
    
    // 모달 외부 클릭시 닫기
    $(window).click(function(event) {
        if (event.target.id === 'scheduleModal') {
            closeModal();
        }
    });
    
    // 엔터키로 일정 추가
    $('#scheduleTitle').keypress(function(e) {
        if (e.which === 13) {
            addSchedule();
        }
    });
});

function openModal(date) {
    $('#scheduleDate').val(date);
    $('#scheduleTitle').val('');
    $('#modalTitle').text(date + ' 일정 관리');
    $('#scheduleModal').show();
    loadSchedules(date);
}

function closeModal() {
    $('#scheduleModal').hide();
}

function addSchedule() {
    var date = $('#scheduleDate').val();
    var title = $('#scheduleTitle').val().trim();
    var gubun = $('input[name="scheduleGubun"]:checked').val().trim();
    
    if (!title) {
        alert('일정 제목을 입력하세요.');
        return;
    }
    
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            action: 'add',
            sc_date: date,
            sc_title: title,
			sc_gubun: gubun
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#scheduleTitle').val('');
                loadSchedules(date);
                location.reload(); // 달력 새로고침
            }
        },
        error: function() {
            alert('일정 추가 중 오류가 발생했습니다.');
        }
    });
}

function loadSchedules(date) {
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            action: 'get_schedules',
            sc_date: date
        },
        dataType: 'json',
        success: function(schedules) {
            var html = '';
            if (schedules.length > 0) {
                html += '<h4>등록된 일정:</h4>';
                for (var i = 0; i < schedules.length; i++) {
                    html += '<div class="schedule-item">';
                    html += '<span>' + escapeHtml(schedules[i]) + '</span>';
                    html += '<button class="delete-btn" onclick="deleteSchedule(\'' + date + '\', \'' + escapeHtml(schedules[i]) + '\')">삭제</button>';
                    html += '</div>';
                }
            } else {
                html = '<p>등록된 일정이 없습니다.</p>';
            }
            $('#scheduleList').html(html);
        }
    });
}

function deleteSchedule(date, title) {
    if (confirm('정말 삭제하시겠습니까?')) {
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                action: 'delete',
                sc_date: date,
                sc_title: title
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    loadSchedules(date);
                    location.reload(); // 달력 새로고침
                }
            },
            error: function() {
                alert('일정 삭제 중 오류가 발생했습니다.');
            }
        });
    }
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>