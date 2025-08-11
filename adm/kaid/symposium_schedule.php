<?php
include_once('./_common.php');

// 관리자 체크
if (!$is_admin) {
    alert('관리자만 접근할 수 있습니다.');
}

$g5['title'] = '학술대회 일정 관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

// 학술대회 ID 받기
$sy_id = isset($_GET['sy_id']) ? (int)$_GET['sy_id'] : 0;

if (!$sy_id) {
    alert('학술대회 ID가 필요합니다.');
}

// 학술대회 정보 가져오기
$symposium_sql = "SELECT * FROM g5_conference WHERE sy_id = $sy_id";
$symposium = sql_fetch($symposium_sql);

if (!$symposium) {
    alert('존재하지 않는 학술대회입니다.');
}

// 일정 목록 가져오기
$schedule_sql = "SELECT * FROM g5_conference_schedule WHERE ss_sy_id = $sy_id ORDER BY ss_order ASC, ss_id ASC";
$schedule_result = sql_query($schedule_sql);

// 연자 목록 가져오기 (셀렉트박스용)
$speaker_sql = "SELECT sp_id, sp_name FROM g5_conference_speaker WHERE sp_sy_id = $sy_id ORDER BY sp_order ASC";
$speaker_result = sql_query($speaker_sql);
$speakers = [];
while ($speaker = sql_fetch_array($speaker_result)) {
    $speakers[] = $speaker;
}
?>

<div class="schedule-management-container">
    <div class="page-header">
        <h1>학술대회 일정 관리</h1>
        <div class="symposium-info">
            <strong><a href="symposium_form.php?w=u&sy_id=<?=$symposium['sy_id']?>"><?php echo $symposium['sy_title']; ?></a></strong>
            <span class="date"><?php echo $symposium['sy_date']; ?></span>
        </div>
    </div>

    <div class="schedule-actions">
        <button type="button" class="btn btn-primary" onclick="openScheduleModal()">
            <i class="fa fa-plus"></i> 일정 추가
        </button>
        <button type="button" class="btn btn-secondary" onclick="saveScheduleOrder()">
            <i class="fa fa-save"></i> 순서 저장
        </button>
    </div>

    <div class="schedule-table-wrapper">
        <table class="schedule-table">
            <thead>
                <tr>
                    <th width="50">순서</th>
                    <th width="150">시간</th>
                    <th>강의내용</th>
                    <th width="200">연자</th>
                    <th width="100">유형</th>
                    <th width="100">배경색</th>
                    <th width="120">관리</th>
                </tr>
            </thead>
            <tbody id="schedule-tbody">
                <?php
                $schedule_count = 0;
                while ($schedule = sql_fetch_array($schedule_result)) {
                    $schedule_count++;
                    
                    // 일정 유형에 따른 스타일 클래스
                    $bg_color = $schedule['ss_bg_color'] ?: '#ffffff';
                    $type_class = '';
                ?>
                <tr class="schedule-item <?php echo $type_class; ?>" data-ss-id="<?php echo $schedule['ss_id']; ?>" draggable="true" style="background-color: <?php echo $bg_color; ?>">
                    <td class="schedule-drag-handle">
                        <i class="fa fa-bars"></i>
                        <span class="order-number"><?php echo $schedule['ss_order']; ?></span>
                    </td>
                    <td class="schedule-time"><?php echo $schedule['ss_time']; ?></td>
                    <td class="schedule-title">
						<div class="ko"><?php echo $schedule['ss_title']; ?></div>
						<div class="en"><?php echo $schedule['ss_title_en']; ?></div>
					</td>
                    <td class="schedule-speaker">
						<div class="ko"><?php echo $schedule['ss_speaker']; ?></div>
						<div class="en"><?php echo $schedule['ss_speaker_en']; ?></div>
					</td>
                    <td class="schedule-type">
                        <?php
                        $type_labels = [
                            'lecture' => '강의',
                            'break' => '휴식',
                            'discussion' => '토론',
                            'event' => '행사',
                            'other' => '기타'
                        ];
                        echo isset($type_labels[$schedule['ss_type']]) ? $type_labels[$schedule['ss_type']] : '기타';
                        ?>
                    </td>
                    <td class="schedule-bg-color">
                        <div class="color-preview" style="background-color: <?php echo $bg_color; ?>; width: 30px; height: 20px; border: 1px solid #ddd; border-radius: 3px;"></div>
                    </td>
                    <td class="schedule-actions">
                        <button type="button" class="btn btn-sm btn-info" onclick="editSchedule(<?php echo $schedule['ss_id']; ?>)">
                            <i class="fa fa-edit"></i> 수정
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteSchedule(<?php echo $schedule['ss_id']; ?>)">
                            <i class="fa fa-trash"></i> 삭제
                        </button>
                    </td>
                </tr>
                <?php } ?>
                
                <?php if ($schedule_count == 0) { ?>
                <tr class="no-schedules">
                    <td colspan="7" style="text-align: center; padding: 40px; color: #6c757d;">
                        등록된 일정이 없습니다.
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 일정 등록/수정 모달 -->
<div id="scheduleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">일정 등록</h3>
            <span class="close" onclick="closeScheduleModal()">&times;</span>
        </div>
        <form id="scheduleForm">
            <input type="hidden" id="ss_id" name="ss_id" value="">
            <input type="hidden" name="sy_id" value="<?php echo $sy_id; ?>">
            
            <div class="form-group">
                <label for="ss_time">시간 <span class="required">*</span></label>
                <input type="text" id="ss_time" name="ss_time" placeholder="예: 14:20 ~ 15:00" required>
            </div>
            
            <div class="form-group">
                <label for="ss_title">강의내용 <span class="required">*</span></label>
                <textarea id="ss_title" name="ss_title" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="ss_title_en">강의내용(영문) <span class="required">*</span></label>
                <textarea id="ss_title_en" name="ss_title_en" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="ss_speaker">연자명</label>
                한글 : <input type="text" id="ss_speaker" name="ss_speaker" placeholder="연자명을 입력하세요">
				영문 : <input type="text" id="ss_speaker_en" name="ss_speaker_en" placeholder="연자명을 입력하세요">
                <div class="speaker-select-wrapper">
                    <label>등록된 연자에서 선택:</label>
                    <select id="speaker-select" onchange="selectSpeaker()">
                        <option value="">연자 선택</option>
                        <?php foreach ($speakers as $speaker) { ?>
                        <option value="<?php echo $speaker['sp_name']; ?>" data-en="<?php echo $speaker['sp_name_en']; ?>"><?php echo $speaker['sp_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="ss_type">일정 유형</label>
                <select id="ss_type" name="ss_type">
                    <option value="lecture">강의</option>
                    <option value="discussion">토론</option>
                    <option value="break">휴식</option>
                    <option value="event">행사</option>
                    <option value="other">기타</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="ss_bg_color">배경색</label>
                <div class="color-input-wrapper">
                    <input type="color" id="ss_bg_color" name="ss_bg_color" value="#ffffff" style="padding:0px;">
                    <input type="text" id="ss_bg_color_text" placeholder="#ffffff" maxlength="7" style="margin-left: 10px; width: 100px;">
                </div>
                <div class="color-presets">
                    <span class="color-preset" data-color="#e3f2fd" style="background-color: #e3f2fd;" title="강의용"></span>
                    <span class="color-preset" data-color="#e8f5e8" style="background-color: #e8f5e8;" title="토론용"></span>
                    <span class="color-preset" data-color="#fff3e0" style="background-color: #fff3e0;" title="휴식용"></span>
                    <span class="color-preset" data-color="#fce4ec" style="background-color: #fce4ec;" title="행사용"></span>
                    <span class="color-preset" data-color="#f5f5f5" style="background-color: #f5f5f5;" title="기타"></span>
                    <span class="color-preset" data-color="#ffffff" style="background-color: #ffffff; border: 1px solid #ddd;" title="기본"></span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="ss_order">순서</label>
                <input type="number" id="ss_order" name="ss_order" value="<?php echo $schedule_count + 1; ?>" min="1">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeScheduleModal()">취소</button>
                <button type="submit" class="btn btn-primary">저장</button>
            </div>
        </form>
    </div>
</div>



<style>
.schedule-management-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
.page-header { margin-bottom: 30px; }
.page-header h1 { color: #333; margin-bottom: 10px; }
.symposium-info { background: #f8f9fa; padding: 10px; border-radius: 5px; }
.symposium-info strong { color: #495057; }
.symposium-info .date { color: #6c757d; margin-left: 10px; }
.schedule-actions { margin-bottom: 20px; text-align: right; }
.btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-left: 5px; }
.btn-primary { background-color: #007bff; color: white; }
.btn-secondary { background-color: #6c757d; color: white; }
.btn-info { background-color: #17a2b8; color: white; }
.btn-danger { background-color: #dc3545; color: white; }
.btn-sm { padding: 4px 8px; font-size: 12px; }
.schedule-table-wrapper { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.schedule-table { width: 100%; border-collapse: collapse; }
.schedule-table th { background: #f8f9fa; padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: bold; }
.schedule-table td { padding: 12px 15px; border-bottom: 1px solid #dee2e6; }
.schedule-item { cursor: move; transition: all 0.2s; }
.schedule-item:hover { background-color: #f8f9fa; }
.schedule-item.dragging { opacity: 0.5; transform: rotate(1deg); }
.schedule-item.drag-over-top { border-top: 3px solid #007bff; }
.schedule-item.drag-over-bottom { border-bottom: 3px solid #007bff; }
.schedule-lecture { background-color: #e3f2fd; }
.schedule-break { background-color: #fff3e0; }
.schedule-discussion { background-color: #e8f5e8; }
.schedule-event { background-color: #fce4ec; }
.schedule-default { background-color: #f5f5f5; }
.color-input-wrapper { display: flex; align-items: center; }
.color-presets { margin-top: 10px; }
.color-preset { display: inline-block; width: 24px; height: 24px; border-radius: 3px; margin-right: 8px; cursor: pointer; border: 1px solid #ccc; }
.color-preset:hover { transform: scale(1.1); }
.color-preview { display: inline-block; }
.schedule-drag-handle { cursor: move; color: #6c757d; text-align: center; }
.order-number { display: block; margin-top: 5px; font-weight: bold; }
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
.modal-content { background-color: white; margin: 5% auto; padding: 0; border-radius: 8px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
.modal-header { padding: 20px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center; }
.modal-header h3 { margin: 0; }
.close { font-size: 28px; font-weight: bold; cursor: pointer; }
.modal-footer { padding: 20px; border-top: 1px solid #dee2e6; text-align: right; }
.modal-footer button { margin-left: 10px; }
.form-group { margin-bottom: 20px; padding: 0 20px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
.required { color: #dc3545; }
.form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; }
.speaker-select-wrapper { margin-top: 10px; padding-top: 10px; border-top: 1px solid #dee2e6; }
.speaker-select-wrapper label { font-size: 12px; color: #6c757d; }
.preview-content { padding: 20px; }
.preview-schedule-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.preview-schedule-table th, .preview-schedule-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
.preview-schedule-table th { background-color: #f8f9fa; font-weight: bold; }
.preview-schedule-table .time-col { width: 150px; }
.preview-schedule-table .speaker-col { width: 200px; }
</style>

<script>
let isEditMode = false;
let draggedElement = null;

// 일정 추가 모달 열기
function openScheduleModal() {
    isEditMode = false;
    $('#modal-title').text('일정 등록');
    $('#scheduleForm')[0].reset();
    $('#ss_id').val('');
    $('#speaker-select').val('');
    $('#ss_bg_color').val('#ffffff');
    $('#ss_bg_color_text').val('#ffffff');
    $('#scheduleModal').show();
}

// 일정 수정 모달 열기
function editSchedule(ssId) {
    isEditMode = true;
    $('#modal-title').text('일정 수정');
    
    $.ajax({
        url: './symposium_schedule_ajax.php',
        type: 'GET',
        data: { action: 'get_schedule', ss_id: ssId },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                $('#ss_id').val(data.schedule.ss_id);
                $('#ss_time').val(data.schedule.ss_time);
                $('#ss_title').val(data.schedule.ss_title);
                $('#ss_speaker').val(data.schedule.ss_speaker);
                $('#ss_title_en').val(data.schedule.ss_title_en);
                $('#ss_speaker_en').val(data.schedule.ss_speaker_en);
                $('#ss_type').val(data.schedule.ss_type || 'lecture');
                $('#ss_order').val(data.schedule.ss_order);
                $('#ss_bg_color').val(data.schedule.ss_bg_color || '#ffffff');
                $('#ss_bg_color_text').val(data.schedule.ss_bg_color || '#ffffff');
                
                $('#scheduleModal').show();
            } else {
                alert('일정 정보를 불러올 수 없습니다.');
            }
        },
        error: function() {
            alert('서버 오류가 발생했습니다.');
        }
    });
}

// 모달 닫기
function closeScheduleModal() {
    $('#scheduleModal').hide();
}

// 연자 선택
function selectSpeaker() {
    const selectedSpeaker = $('#speaker-select').val();
    if (selectedSpeaker) {
        $('#ss_speaker').val(selectedSpeaker);
    }
}

// 일정 삭제
function deleteSchedule(ssId) {
    if (confirm('정말 삭제하시겠습니까?')) {
        $.ajax({
            url: './symposium_schedule_ajax.php',
            type: 'POST',
            data: { action: 'delete', ss_id: ssId },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    alert('삭제되었습니다.');
                    location.reload();
                } else {
                    alert('삭제에 실패했습니다: ' + data.message);
                }
            },
            error: function() {
                alert('서버 오류가 발생했습니다.');
            }
        });
    }
}

// 일정 순서 저장
function saveScheduleOrder() {
    let orderData = [];
    $('.schedule-item').each(function(index) {
        let ssId = $(this).data('ss-id');
        if (ssId) {
            orderData.push({
                ss_id: parseInt(ssId),
                ss_order: index + 1
            });
        }
    });
    
    if (orderData.length === 0) {
        alert('저장할 순서 데이터가 없습니다.');
        return;
    }
    
    let jsonString = JSON.stringify(orderData);
    
    $.ajax({
        url: './symposium_schedule_ajax.php',
        type: 'POST',
        data: { 
            action: 'update_order', 
            order_data: jsonString
        },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                alert('순서가 저장되었습니다.');
                location.reload();
            } else {
                alert('순서 저장에 실패했습니다: ' + data.message);
            }
        },
        error: function() {
            alert('서버 오류가 발생했습니다.');
        }
    });
}



// 폼 제출 처리
$('#scheduleForm').on('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    formData.append('action', isEditMode ? 'update' : 'add');
    
    $.ajax({
        url: './symposium_schedule_ajax.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                alert(isEditMode ? '수정되었습니다.' : '등록되었습니다.');
                closeScheduleModal();
                location.reload();
            } else {
                alert('저장에 실패했습니다: ' + data.message);
            }
        },
        error: function() {
            alert('서버 오류가 발생했습니다.');
        }
    });
});

// 드래그 앤 드롭 정렬
function initDragAndDrop() {
    $('.schedule-item').each(function() {
        $(this).attr('draggable', true);
        
        $(this).on('dragstart', function(e) {
            draggedElement = this;
            $(this).addClass('dragging');
            e.originalEvent.dataTransfer.effectAllowed = 'move';
        });
        
        $(this).on('dragend', function(e) {
            $(this).removeClass('dragging');
            draggedElement = null;
        });
        
        $(this).on('dragover', function(e) {
            e.preventDefault();
            e.originalEvent.dataTransfer.dropEffect = 'move';
            
            if (this !== draggedElement) {
                const rect = this.getBoundingClientRect();
                const midY = rect.top + rect.height / 2;
                
                if (e.originalEvent.clientY < midY) {
                    $(this).addClass('drag-over-top');
                    $(this).removeClass('drag-over-bottom');
                } else {
                    $(this).addClass('drag-over-bottom');
                    $(this).removeClass('drag-over-top');
                }
            }
        });
        
        $(this).on('dragleave', function(e) {
            $(this).removeClass('drag-over-top drag-over-bottom');
        });
        
        $(this).on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('drag-over-top drag-over-bottom');
            
            if (this !== draggedElement) {
                const rect = this.getBoundingClientRect();
                const midY = rect.top + rect.height / 2;
                
                if (e.originalEvent.clientY < midY) {
                    $(this).before(draggedElement);
                } else {
                    $(this).after(draggedElement);
                }
                
                // 순서 번호 업데이트
                updateOrderNumbers();
            }
        });
    });
}

// 순서 번호 업데이트
function updateOrderNumbers() {
    $('.schedule-item').each(function(index) {
        $(this).find('.order-number').text(index + 1);
    });
}

// 색상 관련 이벤트
$(document).ready(function() {
    initDragAndDrop();
    
    // 색상 입력 필드 동기화
    $('#ss_bg_color').on('change', function() {
        $('#ss_bg_color_text').val($(this).val());
    });
    
    $('#ss_bg_color_text').on('input', function() {
        let color = $(this).val();
        if (color.match(/^#[0-9A-Fa-f]{6}$/)) {
            $('#ss_bg_color').val(color);
        }
    });
    
    // 색상 프리셋 클릭
    $('.color-preset').on('click', function() {
        let color = $(this).data('color');
        $('#ss_bg_color').val(color);
        $('#ss_bg_color_text').val(color);
    });
});

</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>