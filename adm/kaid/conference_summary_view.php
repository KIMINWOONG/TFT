<?php
$sub_menu = "600300";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
}

$as_id = (int)$_GET['as_id'];
if (!$as_id) {
    alert('잘못된 접근입니다.');
}

// 초록 정보 조회
$sql = "SELECT cs.*, c.sy_title, c.sy_sdate, c.sy_edate, cr.cr_name_kor, cr.cr_mb_id, cr.cr_email,
               cr.cr_hospital_name, cr.cr_mobile_carrier, cr.cr_mobile1, cr.cr_mobile2, 
               cr.cr_nonemb_name, cr.cr_nonemb_birth
        FROM g5_conference_summary cs 
        LEFT JOIN g5_conference c ON cs.as_sy_id = c.sy_id 
        LEFT JOIN g5_conference_registration cr ON cs.as_cr_id = cr.cr_id 
        WHERE cs.as_id = {$as_id}";

$abstract = sql_fetch($sql);
if (!$abstract) {
    alert('해당 초록을 찾을 수 없습니다.');
}

// 저자 정보 조회
$authors_sql = "SELECT * FROM g5_conference_summary_authors WHERE aa_as_id = {$as_id} ORDER BY aa_order";
$authors_result = sql_query($authors_sql);
$authors = array();
while ($author = sql_fetch_array($authors_result)) {
    $authors[] = $author;
}

// 심사 로그 조회
//$log_sql = "SELECT * FROM g5_abstract_submission_log WHERE asl_as_id = {$as_id} ORDER BY asl_reg_date DESC";
//$log_result = sql_query($log_sql);

$status_text = array(
    'submitted' => '제출완료',
    'reviewed' => '심사중',
    'revision_requested' => '수정요청',
    'accepted' => '채택',
    'rejected' => '탈락'
);

$g5['title'] = '초록 상세보기';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<style>
.abstract_view {
    max-width: 1000px;
}

.view_header {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
    border-left: 4px solid #007bff;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.view_title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

.view_info {
    color: #666;
    font-size: 13px;
}

.view_section {
    background: white;
    border: 1px solid #e9ecef;
    margin-bottom: 20px;
    border-radius: 5px;
    overflow: hidden;
}

.section_header {
    background: #007bff;
    color: white;
    padding: 10px 15px;
    font-weight: bold;
}

.section_body {
    padding: 15px;
}

.info_table {
    width: 100%;
    border-collapse: collapse;
}

.info_table th,
.info_table td {
    border: 1px solid #dee2e6;
    padding: 8px 12px;
    text-align: left;
}

.info_table th {
    background: #f8f9fa;
    width: 120px;
    font-weight: bold;
    font-size: 13px;
}

.info_table td {
    font-size: 13px;
}

.author_list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.author_item {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.author_item:last-child {
    border-bottom: none;
}

.author_info {
    font-weight: bold;
}

.author_role {
    color: #666;
    font-size: 12px;
    margin-left: 10px;
}

.presenter_badge {
    background: #28a745;
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 10px;
}

.abstract_content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
    line-height: 1.6;
    white-space: pre-wrap;
    font-size: 13px;
    border: 1px solid #e9ecef;
}

.file_download {
    background: #e7f3ff;
    padding: 15px;
    border-radius: 4px;
    border-left: 4px solid #007bff;
}

.status_current {
    padding: 6px 12px;
    border-radius: 15px;
    font-weight: bold;
    font-size: 12px;
    color: white;
}

.status_submitted { background: #6c757d; }
.status_reviewed { background: #ffc107; color: #000; }
.status_accepted { background: #28a745; }
.status_revision_requested { background: #fd7e14; }

.review_form {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
}

.review_textarea {
    width: 100%;
    min-height: 80px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    resize: vertical;
    font-size: 13px;
    box-sizing: border-box;
}

.log_table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.log_table th,
.log_table td {
    border: 1px solid #dee2e6;
    padding: 6px 8px;
    text-align: left;
}

.log_table th {
    background: #f8f9fa;
}

.btn_group {
    text-align: center;
    margin: 20px 0;
}

.btn_group .btn {
    margin: 0 5px;
}

.form_group {
    margin-bottom: 15px;
}

.form_label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
    font-size: 13px;
}

.status_radio {
    margin-right: 15px;
}

.status_radio input[type="radio"] {
    margin-right: 5px;
}

.status_radio label {
    margin-right: 10px;
    cursor: pointer;
    font-size: 13px;
}
</style>
<script>
function updateStatus() {
    const form = document.getElementById('statusForm');
    const selectedStatus = form.querySelector('input[name="status"]:checked');
    
    if (!selectedStatus) {
        alert('상태를 선택해주세요.');
        return;
    }
    
    const statusText = {
        'reviewed': '심사중',
		'revision_requested': '수정요청',
        'accepted': '채택',
        'rejected': '탈락'
    };
    
    const currentStatus = '<?=$abstract['as_status']?>';
    const newStatus = selectedStatus.value;
    
    if (currentStatus === newStatus) {
        alert('현재 상태와 동일합니다.');
        return;
    }
    
    if (confirm(`상태를 "${statusText[newStatus]}"로 변경하시겠습니까?`)) {
        form.submit();
    }
}

// 라디오 버튼 변경 시 즉시 반영하는 함수 (선택사항)
function quickStatusChange(status) {
    const statusText = {
        'reviewed': '심사중',
        'revision_requested': '수정요청',
        'accepted': '채택', 
        'rejected': '탈락'
    };
    
    if (confirm(`상태를 "${statusText[status]}"로 즉시 변경하시겠습니까?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = './abstract_status_update.php';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'as_id';
        idInput.value = '<?=$as_id?>';
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        
        form.appendChild(idInput);
        form.appendChild(statusInput);
        document.body.appendChild(form);
        form.submit();
    } else {
        // 취소시 원래 상태로 되돌리기
        const currentStatus = '<?=$abstract['as_status']?>';
        document.querySelector(`input[name="status"][value="${currentStatus}"]`).checked = true;
    }
}
</script>

<div class="abstract_view">
    <!-- 헤더 -->
    <div class="view_header">
        <div>
            <div class="view_title">초록 상세보기 #<?=$as_id?></div>
            <div class="view_info">
                <?=$abstract['sy_year']?>년 <?=$abstract['sy_title']?> | 
                제출일: <?=date('Y-m-d H:i:s', strtotime($abstract['as_submit_date']))?>
                <?php if ($abstract['as_update_date'] != $abstract['as_submit_date']) { ?>
                | 수정일: <?=date('Y-m-d H:i:s', strtotime($abstract['as_update_date']))?>
                <?php } ?>
            </div>
        </div>
        <div>
            <span class="status_current status_<?=$abstract['as_status']?>">
                <?=$status_text[$abstract['as_status']]?>
            </span>
        </div>
    </div>

    <!-- 초록관리 -->
    <div class="view_section">
        <div class="section_header">초록관리</div>
        <div class="section_body">
            <form id="statusForm" method="post" action="./conference_summary_status_update.php">
                <input type="hidden" name="as_id" value="<?=$as_id?>">
                <table class="info_table">
                    <tr>
                        <th>작성일</th>
                        <td><?=date('Y-m-d H:i:s', strtotime($abstract['as_submit_date']))?></td>
                    </tr>
                    <tr>
                        <th>상태</th>
                        <td>
                            <input type="radio" name="status" id="status_reviewed" value="reviewed" <?=($abstract['as_status'] == 'reviewed') ? 'checked' : ''?>>
                            <label for="status_reviewed">심사중</label>
                            
                            <input type="radio" name="status" id="status_revision_requested" value="revision_requested" <?=($abstract['as_status'] == 'revision_requested') ? 'checked' : ''?>>
                            <label for="status_revision_requested">수정요청</label>
                            
                            <input type="radio" name="status" id="status_accepted" value="accepted" <?=($abstract['as_status'] == 'accepted') ? 'checked' : ''?>>
                            <label for="status_accepted">채택</label>
                            
                            <input type="radio" name="status" id="status_rejected" value="rejected" <?=($abstract['as_status'] == 'rejected') ? 'checked' : ''?>>
                            <label for="status_rejected">탈락</label>
                        </td>
                    </tr>
                    <tr>
                        <th>심사완료일</th>
                        <td>
                            <?php if ($abstract['as_review_date']) { ?>
                                <?=date('Y-m-d H:i:s', strtotime($abstract['as_review_date']))?>
                            <?php } else { ?>
                                <span style="color: #999;">미완료</span>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
                <div class="btn_group" style="margin-top: 15px;">
                    <button type="button" onclick="updateStatus()" class="btn btn_01">상태 변경</button>
                </div>
            </form>
        </div>
    </div>


    <!-- 논문 메타정보 -->
    <div class="view_section">
        <div class="section_header">논문 메타정보</div>
        <div class="section_body">
            <table class="info_table">
                <tr>
                    <th>제출자</th>
                    <td><?=htmlspecialchars($abstract['as_submitter'])?></td>
                </tr>
                <tr>
                    <th>논문제목(국문)</th>
                    <td><?=htmlspecialchars($abstract['as_title_kor'])?></td>
                </tr>
                <tr>
                    <th>논문제목(영문)</th>
                    <td><?=htmlspecialchars($abstract['as_title_eng'])?></td>
                </tr>
                <tr>
                    <th>소속기관</th>
                    <td><?=htmlspecialchars($abstract['as_institution'])?></td>
                </tr>
                <tr>
                    <th>언어</th>
                    <td><?=$abstract['as_language']?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 저자 정보 -->
    <div class="view_section">
        <div class="section_header">저자 정보</div>
        <div class="section_body">
            <ul class="author_list">
                <?php if (!empty($authors)) { ?>
                    <?php foreach ($authors as $author) { ?>
                    <li class="author_item">
                        <div>
                            <span class="author_info"><?=htmlspecialchars($author['aa_name'])?></span>
                            <span class="author_role">(<?=$author['aa_role']?>)</span>
                            <?php if ($author['aa_is_presenter']) { ?>
                            <span class="presenter_badge">발표자</span>
                            <?php } ?>
                        </div>
                        <div style="font-size: 11px; color: #666;">
                            순서: <?=$author['aa_order']?>
                        </div>
                    </li>
                    <?php } ?>
                <?php } else { ?>
                    <li class="author_item">저자 정보가 없습니다.</li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <!-- 초록 내용 -->
    <div class="view_section">
        <div class="section_header">초록 내용</div>
        <div class="section_body">
            <h5 style="margin-bottom: 10px;">국문 초록</h5>
            <div class="abstract_content"><?=htmlspecialchars($abstract['as_abstract_kor'])?></div>
            
            <h5 style="margin: 20px 0 10px 0;">영문 초록</h5>
            <div class="abstract_content"><?=htmlspecialchars($abstract['as_abstract_eng'])?></div>
        </div>
    </div>

    <!-- 발표 정보 -->
    <div class="view_section">
        <div class="section_header">발표 정보</div>
        <div class="section_body">
            <table class="info_table">
                <tr>
                    <th>발표분야</th>
                    <td><?=$abstract['as_presentation_field']?></td>
                </tr>
                <tr>
                    <th>발표유형</th>
                    <td><?=$abstract['as_presentation_type']?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 발표자 정보 -->
    <div class="view_section">
        <div class="section_header">발표자 정보</div>
        <div class="section_body">
            <table class="info_table">
                <tr>
                    <th>발표자명</th>
                    <td><?=htmlspecialchars($abstract['as_presenter_name'])?></td>
                </tr>
                <tr>
                    <th>소속기관</th>
                    <td><?=htmlspecialchars($abstract['as_presenter_institution'])?></td>
                </tr>
                <tr>
                    <th>자택전화</th>
                    <td>
                        <?php if ($abstract['as_presenter_home_phone1']) { ?>
                        <?=$abstract['as_presenter_home_phone1']?>-<?=$abstract['as_presenter_home_phone2']?>-<?=$abstract['as_presenter_home_phone3']?>
                        <?php } else { ?>
                        <span style="color: #999;">입력안함</span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>휴대전화</th>
                    <td><?=$abstract['as_presenter_mobile_carrier']?>-<?=$abstract['as_presenter_mobile1']?>-<?=$abstract['as_presenter_mobile2']?></td>
                </tr>
                <tr>
                    <th>이메일</th>
                    <td><?=htmlspecialchars($abstract['as_presenter_email'])?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 첨부파일 -->
    <?php if ($abstract['as_file_path']) { ?>
    <div class="view_section">
        <div class="section_header">첨부파일</div>
        <div class="section_body">
            <div class="file_download">
                <strong>파일명:</strong> <?=htmlspecialchars($abstract['as_file_name'])?><br>
                <strong>파일크기:</strong> <?=number_format($abstract['as_file_size'])?> bytes<br>
                <a href="./conference_summary_download.php?as_id=<?=$as_id?>" class="btn btn_03" style="margin-top: 10px;">파일 다운로드</a>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- 등록자 정보 -->
    <div class="view_section">
        <div class="section_header">등록자 정보</div>
        <div class="section_body">
            <table class="info_table">
                <tr>
                    <th>등록자명</th>
                    <td>
                        <?php if ($abstract['cr_mb_id']) { ?>
                        <?=htmlspecialchars($abstract['cr_name_kor'])?> (회원: <?=$abstract['cr_mb_id']?>)
                        <?php } else { ?>
                        <?=htmlspecialchars($abstract['cr_nonemb_name'])?> (비회원)
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>이메일</th>
                    <td><?=htmlspecialchars($abstract['cr_email'])?></td>
                </tr>
                <tr>
                    <th>소속기관</th>
                    <td><?=htmlspecialchars($abstract['cr_hospital_name'])?></td>
                </tr>
                <tr>
                    <th>연락처</th>
                    <td><?=$abstract['cr_mobile_carrier']?>-<?=$abstract['cr_mobile1']?>-<?=$abstract['cr_mobile2']?></td>
                </tr>
            </table>
        </div>
    </div>

   <!-- 심사 관리 -->
   <!--
    <div class="view_section">
        <div class="section_header">심사 관리</div>
        <div class="section_body">
            <form method="post" action="./abstract_review_update.php">
                <input type="hidden" name="as_id" value="<?=$as_id?>">
                
                <div class="form_group">
                    <label for="review_comments" class="form_label">심사 의견</label>
                    <textarea name="review_comments" id="review_comments" class="review_textarea" placeholder="심사 의견을 입력하세요..."><?=htmlspecialchars($abstract['as_review_comments'])?></textarea>
                </div>
                
                <div class="form_group">
                    <label for="admin_memo" class="form_label">관리자 메모</label>
                    <textarea name="admin_memo" id="admin_memo" class="review_textarea" placeholder="관리자 메모를 입력하세요..."><?=htmlspecialchars($abstract['as_admin_memo'])?></textarea>
                </div>
                
                <div class="btn_group">
                    <button type="submit" name="action" value="save" class="btn btn_03">저장</button>
                    <button type="submit" name="action" value="accept" class="btn btn_01" onclick="return confirm('승인하시겠습니까?')">승인</button>
                    <button type="submit" name="action" value="reject" class="btn btn_02" onclick="return confirm('반려하시겠습니까?')">반려</button>
                    <button type="submit" name="action" value="review" class="btn btn_03">심사중</button>
                </div>
            </form>
        </div>
    </div>
-->

    <!-- 하단 버튼 -->
    <div class="btn_group">
        <a href="./conference_summary_list.php" class="btn btn_02">목록으로</a>
        <!--<a href="./abstract_print.php?as_id=<?=$as_id?>" class="btn btn_03" target="_blank">인쇄용 보기</a>-->
        <?php if ($abstract['as_file_path']) { ?>
        <a href="./conference_summary_download.php?as_id=<?=$as_id?>" class="btn btn_03">파일 다운로드</a>
        <?php } ?>
    </div>
</div>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>