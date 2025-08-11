<?php
include_once('./_common.php');

// 관리자 체크
if (!$is_admin) {
    alert('관리자만 접근할 수 있습니다.');
}

$g5['title'] = '집담회 연자 관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

// 집담회 ID 받기
$sy_id = isset($_GET['sy_id']) ? (int)$_GET['sy_id'] : 0;

if (!$sy_id) {
    alert('집담회 ID가 필요합니다.');
}

// 집담회 정보 가져오기
$symposium_sql = "SELECT * FROM g5_conference WHERE sy_id = $sy_id";
$symposium = sql_fetch($symposium_sql);

if (!$symposium) {
    alert('존재하지 않는 집담회입니다.');
}

// 연자 목록 가져오기
$speaker_sql = "SELECT * FROM g5_conference_speaker WHERE sp_sy_id = $sy_id ORDER BY sp_order ASC, sp_id ASC";
$speaker_result = sql_query($speaker_sql);
?>

<div class="speaker-management-container">
    <div class="page-header">
        <h1>집담회 연자 관리</h1>
        <div class="symposium-info">
            <strong><a href="conference_form.php?w=u&sy_id=<?=$symposium['sy_id']?>"><?php echo $symposium['sy_title']; ?></a></strong>
            <span class="date"><?php echo $symposium['sy_date']; ?></span>
        </div>
    </div>

    <div class="speaker-actions">
        <button type="button" class="btn btn-primary" onclick="openSpeakerModal()">
            <i class="fa fa-plus"></i> 연자 추가
        </button>
        <button type="button" class="btn btn-secondary" onclick="saveSpeakerOrder()">
            <i class="fa fa-save"></i> 순서 저장
        </button>
    </div>

    <div class="speaker-list" id="speaker-list">
        <?php
        $speaker_count = 0;
        while ($speaker = sql_fetch_array($speaker_result)) {
            $speaker_count++;
        ?>
        <div class="speaker-item" data-sp-id="<?php echo $speaker['sp_id']; ?>" draggable="true">
            <div class="speaker-drag-handle">
                <i class="fa fa-bars"></i>
            </div>
            <div class="speaker-photo">
                <?php if ($speaker['sp_photo']) { ?>
                    <img src="<?php echo $speaker['sp_photo']; ?>" alt="<?php echo $speaker['sp_name']; ?>">
                <?php } else { ?>
                    <div class="no-photo">사진 없음</div>
                <?php } ?>
            </div>
            <div class="speaker-info">
                <div class="speaker-name"><?php echo $speaker['sp_name']; ?></div>
                <div class="speaker-specialty"><?php echo $speaker['sp_specialty']; ?></div>
            </div>
            <div class="speaker-order">
                순서: <?php echo $speaker['sp_order']; ?>
            </div>
            <div class="speaker-actions">
                <button type="button" class="btn btn-sm btn-info" onclick="editSpeaker(<?php echo $speaker['sp_id']; ?>)">
                    <i class="fa fa-edit"></i> 수정
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="deleteSpeaker(<?php echo $speaker['sp_id']; ?>)">
                    <i class="fa fa-trash"></i> 삭제
                </button>
            </div>
        </div>
        <?php } ?>
        
        <?php if ($speaker_count == 0) { ?>
        <div class="no-speakers">
            <p>등록된 연자가 없습니다.</p>
        </div>
        <?php } ?>
    </div>
</div>

<!-- 연자 등록/수정 모달 -->
<div id="speakerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">연자 등록</h3>
            <span class="close" onclick="closeSpeakerModal()">&times;</span>
        </div>
        <form id="speakerForm" enctype="multipart/form-data">
            <input type="hidden" id="sp_id" name="sp_id" value="">
            <input type="hidden" name="sy_id" value="<?php echo $sy_id; ?>">
            
            <div class="form-group">
                <label for="sp_name">연자명 <span class="required">*</span></label>
                <input type="text" id="sp_name" name="sp_name" required>
            </div>
            <div class="form-group">
                <label for="sp_name_en">연자명 <span class="required">*</span></label>
                <input type="text" id="sp_name_en" name="sp_name_en" required>
            </div>
            
            <div class="form-group">
                <label for="sp_specialty">전문분야</label>
                <textarea id="sp_specialty" name="sp_specialty" rows="3"></textarea>
            </div>
             <div class="form-group">
                <label for="sp_specialty_en">전문분야</label>
                <textarea id="sp_specialty_en" name="sp_specialty_en" rows="3"></textarea>
            </div>
           
            <div class="form-group">
                <label for="sp_photo">사진</label>
                <input type="file" id="sp_photo" name="sp_photo" accept="image/*">
                <div id="current-photo"></div>
            </div>
            
            <div class="form-group">
                <label for="sp_order">순서</label>
                <input type="number" id="sp_order" name="sp_order" value="<?php echo $speaker_count + 1; ?>" min="1">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeSpeakerModal()">취소</button>
                <button type="submit" class="btn btn-primary">저장</button>
            </div>
        </form>
    </div>
</div>

<style>
.speaker-management-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
.page-header { margin-bottom: 30px; }
.page-header h1 { color: #333; margin-bottom: 10px; }
.symposium-info { background: #f8f9fa; padding: 10px; border-radius: 5px; }
.symposium-info strong { color: #495057; }
.symposium-info .date { color: #6c757d; margin-left: 10px; }
.speaker-actions { margin-bottom: 20px; text-align: right; }
.btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn-primary { background-color: #007bff; color: white; }
.btn-secondary { background-color: #6c757d; color: white; }
.btn-info { background-color: #17a2b8; color: white; }
.btn-danger { background-color: #dc3545; color: white; }
.btn-sm { padding: 4px 8px; font-size: 12px; }
.speaker-list { background: white; border-radius: 8px; }
.speaker-item { display: flex; align-items: center; padding: 15px; border: 1px solid #dee2e6; margin-bottom: 10px; border-radius: 5px; background: white; cursor: move; transition: all 0.2s; }
.speaker-item:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.speaker-item.dragging { opacity: 0.5; transform: rotate(2deg); }
.speaker-item.drag-over-top { border-top: 3px solid #007bff; }
.speaker-item.drag-over-bottom { border-bottom: 3px solid #007bff; }
.speaker-drag-handle { cursor: move; color: #6c757d; margin-right: 10px; }
.speaker-photo { width: 80px; height: 80px; margin-right: 15px; }
.speaker-photo img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.no-photo { width: 80px; height: 80px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #6c757d; }
.speaker-info { flex: 1; }
.speaker-name { font-weight: bold; font-size: 16px; margin-bottom: 5px; }
.speaker-specialty { color: #6c757d; font-size: 14px; }
.speaker-order { margin-right: 15px; font-size: 14px; color: #495057; }
.speaker-actions button { margin-left: 5px; }
.no-speakers { text-align: center; padding: 40px; color: #6c757d; }
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
.modal-content { background-color: white; margin: 5% auto; padding: 0; border-radius: 8px; width: 90%; max-width: 500px; }
.modal-header { padding: 20px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center; }
.modal-header h3 { margin: 0; }
.close { font-size: 28px; font-weight: bold; cursor: pointer; }
.modal-footer { padding: 20px; border-top: 1px solid #dee2e6; text-align: right; }
.modal-footer button { margin-left: 10px; }
.form-group { margin-bottom: 20px; padding: 0 20px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
.required { color: #dc3545; }
.form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; }
#current-photo { margin-top: 10px; }
#current-photo img { max-width: 100px; max-height: 100px; border-radius: 50%; }
</style>

<script>
let isEditMode = false;

// 연자 추가 모달 열기
function openSpeakerModal() {
    isEditMode = false;
    $('#modal-title').text('연자 등록');
    $('#speakerForm')[0].reset();
    $('#sp_id').val('');
    $('#current-photo').html('');
    $('#speakerModal').show();
}

// 연자 수정 모달 열기
function editSpeaker(spId) {
    isEditMode = true;
    $('#modal-title').text('연자 수정');
    
    $.ajax({
        url: './conference_speaker_ajax.php',
        type: 'GET',
        data: { action: 'get_speaker', sp_id: spId },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                $('#sp_id').val(data.speaker.sp_id);
                $('#sp_name').val(data.speaker.sp_name);
                $('#sp_name_en').val(data.speaker.sp_name_en);
                $('#sp_specialty').val(data.speaker.sp_specialty);
                $('#sp_specialty_en').val(data.speaker.sp_specialty_en);
                $('#sp_order').val(data.speaker.sp_order);
                
                if (data.speaker.sp_photo) {
                    $('#current-photo').html('<img src="' + data.speaker.sp_photo + '" alt="현재 사진">');
                } else {
                    $('#current-photo').html('');
                }
                
                $('#speakerModal').show();
            } else {
                alert('연자 정보를 불러올 수 없습니다.');
            }
        },
        error: function() {
            alert('서버 오류가 발생했습니다.');
        }
    });
}

// 모달 닫기
function closeSpeakerModal() {
    $('#speakerModal').hide();
}

// 연자 삭제
function deleteSpeaker(spId) {
    if (confirm('정말 삭제하시겠습니까?')) {
        $.ajax({
            url: './conference_speaker_ajax.php',
            type: 'POST',
            data: { action: 'delete', sp_id: spId },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    alert('삭제되었습니다.');
                    reloadSpeakerList();
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

// 연자 순서 저장
function saveSpeakerOrder() {
    let orderData = [];
    $('.speaker-item').each(function(index) {
        let spId = $(this).data('sp-id');
        if (spId) {
            orderData.push({
                sp_id: parseInt(spId),
                sp_order: index + 1
            });
        }
    });
    
    // 디버깅을 위한 로그
    console.log('Order Data:', orderData);
    
    if (orderData.length === 0) {
        alert('저장할 순서 데이터가 없습니다.');
        return;
    }
    
    // JSON 문자열로 변환
    let jsonString = JSON.stringify(orderData);
    console.log('JSON String:', jsonString);
    
    $.ajax({
        url: './conference_speaker_ajax.php',
        type: 'POST',
        data: { 
            action: 'update_order', 
            order_data: jsonString
        },
        dataType: 'json',
        beforeSend: function() {
            // 버튼 비활성화로 중복 클릭 방지
            $('button').prop('disabled', true);
        },
        complete: function() {
            // 요청 완료 후 버튼 활성화
            $('button').prop('disabled', false);
        },
        success: function(data) {
            console.log('Response:', data);
            if (data.success) {
                alert('순서가 저장되었습니다.');
                reloadSpeakerList();
            } else {
                alert('순서 저장에 실패했습니다: ' + data.message);
                console.error('Error:', data);
            }
        },
        error: function(xhr, status, error) {
            alert('서버 오류가 발생했습니다: ' + error);
            console.error('AJAX Error:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
        }
    });
}

// 폼 제출 처리
$('#speakerForm').on('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    formData.append('action', isEditMode ? 'update' : 'add');
    
    $.ajax({
        url: './conference_speaker_ajax.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                alert(isEditMode ? '수정되었습니다.' : '등록되었습니다.');
                closeSpeakerModal();
                reloadSpeakerList();
            } else {
                alert('저장에 실패했습니다: ' + data.message);
            }
        },
        error: function() {
            alert('서버 오류가 발생했습니다.');
        }
    });
});

// 드래그 앤 드롭 정렬 (jQuery UI 없이 구현)
let draggedElement = null;

$(document).ready(function() {
    initDragAndDrop();
});

function initDragAndDrop() {
    $('.speaker-item').each(function() {
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
            }
        });
    });
}

// 모달 외부 클릭시 닫기
/**
$(document).on('click', function(e) {
    if (e.target.id === 'speakerModal') {
        closeSpeakerModal();
    }
});
*/

// 페이지 새로고침 후 드래그 기능 재초기화
function reloadSpeakerList() {
    location.reload();
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>