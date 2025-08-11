<?php
$sub_menu = "600210";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
}

$cr_id = (int)$_GET['cr_id'];
if (!$cr_id) {
    alert('잘못된 접근입니다.');
}

// 신청자 정보 조회
$sql = "SELECT cr.*, c.sy_title, c.sy_sdate, c.sy_edate,
               m.mb_level, m.mb_datetime, m.mb_login_ip
        FROM g5_conference_registration cr 
        LEFT JOIN g5_conference c ON cr.cr_sy_id = c.sy_id 
        LEFT JOIN g5_member m ON cr.cr_mb_id = m.mb_id 
        WHERE cr.cr_id = {$cr_id}";

$registration = sql_fetch($sql);
if (!$registration) {
    alert('해당 신청자를 찾을 수 없습니다.');
}

// 제출된 초록 수 조회
$abstract_count = sql_fetch("SELECT COUNT(*) as cnt FROM g5_conference_summary WHERE as_cr_id = {$cr_id}");

// 신청 로그 조회 (있다면)
$log_sql = "SELECT * FROM g5_registration_log WHERE rl_cr_id = {$cr_id} ORDER BY rl_reg_date DESC";
$log_result = sql_query($log_sql);

$status_text = array(
    'registered' => '신청완료',
    'cancelled' => '취소',
);

$member_class_text = array(
    'member' => '정회원',
    'student' => '학생회원',
    'fellow' => '펠로우',
);

$title_text = array(
    'dr' => 'Dr.',
    'prof' => 'Prof.',
    'ms' => 'Ms.',
    'mr' => 'Mr.',
);

$payment_status_text = array(
    'pending' => '결제대기',
    'completed' => '결제완료',
    'cancelled' => '결제취소',
);

$payment_method_text = array(
    'card' => '신용카드',
    'bank' => '무통장입금',
);

$annual_fee_status_text = array(
    'paid' => '완료',
    'unpaid' => '미납',
);

$g5['title'] = '학술대회 신청자 상세보기';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<style>
.registration_view { max-width: 1000px; }
.view_header { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #007bff; display: flex; justify-content: space-between; align-items: center; }
.view_title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
.view_info { color: #666; font-size: 13px; }
.view_section { background: white; border: 1px solid #e9ecef; margin-bottom: 20px; border-radius: 5px; overflow: hidden; }
.section_header { background: #007bff; color: white; padding: 10px 15px; font-weight: bold; }
.section_body { padding: 15px; }
.info_table { width: 100%; border-collapse: collapse; }
.info_table th, .info_table td { border: 1px solid #dee2e6; padding: 8px 12px; text-align: left; }
.info_table th { background: #f8f9fa; width: 120px; font-weight: bold; font-size: 13px; }
.info_table td { font-size: 13px; }
.status_current { padding: 6px 12px; border-radius: 15px; font-weight: bold; font-size: 12px; color: white; }
.status_registered { background: #28a745; }
.status_cancelled { background: #dc3545; }
.member_badge { padding: 4px 8px; border-radius: 10px; font-size: 11px; font-weight: bold; }
.member_yes { background: #007bff; color: white; }
.member_no { background: #6c757d; color: white; }
.btn_group { text-align: center; margin: 20px 0; }
.btn_group .btn { margin: 0 5px; }
.form_group { margin-bottom: 15px; }
.form_label { font-weight: bold; display: block; margin-bottom: 5px; font-size: 13px; }
.review_textarea { width: 100%; min-height: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; resize: vertical; font-size: 13px; box-sizing: border-box; }
.log_table { width: 100%; border-collapse: collapse; font-size: 12px; }
.log_table th, .log_table td { border: 1px solid #dee2e6; padding: 6px 8px; text-align: left; }
.log_table th { background: #f8f9fa; }
.abstract_summary { background: #e7f3ff; padding: 15px; border-radius: 4px; border-left: 4px solid #007bff; }
.status_radio input[type="radio"] { margin-right: 5px; }
.status_radio label { margin-right: 15px; cursor: pointer; font-size: 13px; }
.payment_info { background: #f8f9fa; padding: 10px; border-radius: 4px; border: 1px solid #e9ecef; }
.payment_completed { color: #28a745; font-weight: bold; }
.payment_pending { color: #ffc107; font-weight: bold; }
.payment_cancelled { color: #dc3545; font-weight: bold; }
.fee_paid { color: #28a745; font-weight: bold; }
.fee_unpaid { color: #dc3545; font-weight: bold; }
</style>

<div class="registration_view">
    <!-- 헤더 -->
    <div class="view_header">
        <div>
            <div class="view_title">학술대회 신청자 상세보기 #<?=$cr_id?></div>
            <div class="view_info">
                신청일: <?=date('Y-m-d H:i:s', strtotime($registration['cr_reg_date']))?>
                <?php if ($registration['cr_update_date'] != $registration['cr_reg_date']) { ?>
                | 수정일: <?=date('Y-m-d H:i:s', strtotime($registration['cr_update_date']))?>
                <?php } ?>
            </div>
        </div>
        <div>
            <span class="status_current status_<?=$registration['cr_status']?>">
                <?=$status_text[$registration['cr_status']]?>
            </span>
            <span class="member_badge <?=$registration['cr_mb_id'] ? 'member_yes' : 'member_no'?>">
                <?=$registration['cr_mb_id'] ? '회원' : '비회원'?>
            </span>
        </div>
    </div>

    <!-- 신청 관리 -->
    <div class="view_section">
        <div class="section_header">신청 관리</div>
        <div class="section_body">
            <form id="statusForm" method="post" action="./registration_status_update.php" target="hidfrm">
                <input type="hidden" name="cr_id" value="<?=$cr_id?>">
                <table class="info_table">
                    <tr>
                        <th>신청일</th>
                        <td><?=date('Y-m-d H:i:s', strtotime($registration['cr_reg_date']))?></td>
                    </tr>
                    <tr>
                        <th>상태</th>
                        <td class="status_radio">
                            <input type="radio" name="status" id="status_registered" value="registered" <?=($registration['cr_status'] == 'registered') ? 'checked' : ''?>>
                            <label for="status_registered">신청완료</label>
                            
                            <input type="radio" name="status" id="status_cancelled" value="cancelled" <?=($registration['cr_status'] == 'cancelled') ? 'checked' : ''?>>
                            <label for="status_cancelled">취소</label>
                        </td>
                    </tr>
                    <tr>
                        <th>주문번호</th>
                        <td><?=htmlspecialchars($registration['cr_od_id'])?></td>
                    </tr>
                </table>
                <div class="btn_group" style="margin-top: 15px;">
                    <button type="button" onclick="updateStatus()" class="btn btn_01">상태 변경</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 학술학술대회 정보 -->
    <div class="view_section">
        <div class="section_header">학술대회 정보</div>
        <div class="section_body">
            <table class="info_table">
                <tr>
                    <th>학술대회명</th>
                    <td><?=htmlspecialchars($registration['sy_title'])?></td>
                </tr>
                <tr>
                    <th>개최일시</th>
                    <td><?=date('Y년 m월 d일', strtotime($registration['sy_sdate']))?></td>
                </tr>
                <?php if ($registration['sy_edate'] && $registration['sy_sdate'] != $registration['sy_edate']) { ?>
                <tr>
                    <th>종료일시</th>
                    <td><?=date('Y년 m월 d일', strtotime($registration['sy_edate']))?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!-- 신청자 정보 -->
    <div class="view_section">
        <div class="section_header">신청자 정보</div>
        <div class="section_body">
            <table class="info_table">
                <tr>
                    <th>신청구분</th>
                    <td><?=$registration['cr_reg_type'] == '1' ? '학술대회' : '집담회'?></td>
                </tr>
                <?php if ($registration['cr_member_class']) { ?>
                <tr>
                    <th>회원구분</th>
                    <td><?=$registration['cr_member_class']?></td>
                </tr>
                <?php } ?>
                <?php if ($registration['cr_title']) { ?>
                <tr>
                    <th>호칭</th>
                    <td><?=$title_text[$registration['cr_title']] ?? $registration['cr_title']?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th>성명(국문)</th>
                    <td>
                        <?php if ($registration['cr_mb_id']) { ?>
                            <?=htmlspecialchars($registration['cr_name_kor'])?>
                            <span style="margin-left: 10px; color: #666;">(회원 ID: <?=$registration['cr_mb_id']?>)</span>
                        <?php } else { ?>
                            <?=htmlspecialchars($registration['cr_nonemb_name'])?>
                            <span style="margin-left: 10px; color: #666;">(비회원)</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php if ($registration['cr_name_eng']) { ?>
                <tr>
                    <th>성명(영문)</th>
                    <td><?=htmlspecialchars($registration['cr_name_eng'])?></td>
                </tr>
                <?php } ?>
                <?php if ($registration['cr_license_number'] || $registration['cr_has_no_license']) { ?>
                <tr>
                    <th>면허번호</th>
                    <td>
                        <?php if ($registration['cr_has_no_license']) { ?>
                            <span style="color: #999;">면허번호 없음</span>
                        <?php } else { ?>
                            <?=htmlspecialchars($registration['cr_license_number'])?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <th>이메일</th>
                    <td><?=htmlspecialchars($registration['cr_email'])?></td>
                </tr>
                <?php if ($registration['cr_mobile1']) { ?>
                <tr>
                    <th>휴대전화</th>
                    <td><?=$registration['cr_mobile_carrier']?>-<?=$registration['cr_mobile1']?>-<?=$registration['cr_mobile2']?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!-- 소속 정보 -->
    <div class="view_section">
        <div class="section_header">소속 정보</div>
        <div class="section_body">
            <table class="info_table">
                <tr>
                    <th>기관/병원명</th>
                    <td><?=htmlspecialchars($registration['cr_hospital_name'])?></td>
                </tr>
                <tr>
                    <th>소속과</th>
                    <td><?=htmlspecialchars($registration['cr_department'])?></td>
                </tr>
                <tr>
                    <th>근무지 주소</th>
                    <td>
                        (<?=$registration['cr_work_zip']?>) 
                        <?=htmlspecialchars($registration['cr_work_addr1'])?>
                        <?php if ($registration['cr_work_addr2']) { ?>
                        <?=htmlspecialchars($registration['cr_work_addr2'])?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>근무지 전화번호</th>
                    <td><?=$registration['cr_work_phone1']?>-<?=$registration['cr_work_phone2']?>-<?=$registration['cr_work_phone3']?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 결제 정보 -->
    <div class="view_section">
	<form name="paymentform" method="post" action="symposium_registration_payment_update.php" target="hidfrm">
	<input type="hidden" name="cr_id" value="<?=$cr_id?>">
        <div class="section_header">결제 정보</div>
        <div class="section_body">
            <table class="info_table">
                <tr>
                    <th>등록비</th>
                    <td><?=number_format($registration['cr_registration_fee'])?>원</td>
                </tr>
                <?php if ($registration['cr_payment_method']) { ?>
                <tr>
                    <th>결제방법</th>
                    <td><?=$payment_method_text[$registration['cr_payment_method']] ?? $registration['cr_payment_method']?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th>결제금액</th>
                    <td>
                        <input type="text" name="cr_receipt_price" value="<?=$registration['cr_receipt_price']?>" class="frm_input"> <input type="checkbox" name="cr_price_chk" value="<?=$registration['cr_registration_fee']?>" onclick="if (this.checked == true) this.form.cr_receipt_price.value=this.form.cr_price_chk.value; else this.form.cr_receipt_price.value = this.form.cr_receipt_price.defaultValue;"> 결제금액 입력
                    </td>
                </tr>
                <tr>
                    <th>결제상태</th>
                    <td>
                        <span class="payment_<?=$registration['cr_payment_status']?>">
                            <?=$payment_status_text[$registration['cr_payment_status']]?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>결제일시</th>
                    <td>
						<input type="text" name="cr_payment_date" value="<?=$registration['cr_payment_date']?>" class="frm_input"> <input type="checkbox" name="od_bank_chk" value="<?=date("Y-m-d H:i:s")?>" onclick="if (this.checked == true) this.form.cr_payment_date.value=this.form.od_bank_chk.value; else this.form.cr_payment_date.value = this.form.cr_payment_date.defaultValue;"> 현재 시간으로 설정
					</td>
                </tr>
                <?php if ($registration['cr_receipt_number']) { ?>
                <tr>
                    <th>영수증번호</th>
                    <td><?=htmlspecialchars($registration['cr_receipt_number'])?></td>
				</tr>
                <?php } ?>
                <?php if ($registration['cr_payment_info']) { ?>
                <tr>
                    <th>결제정보</th>
                    <td>
                        <div class="payment_info">
                            <?=nl2br(htmlspecialchars($registration['cr_payment_info']))?>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
                <div class="btn_group" style="margin-top: 15px;">
                    <button type="button" onclick="updatePayment()" class="btn btn_01">결제 변경</button>
                </div>

	</form>
    </div>

    <!-- 회원 정보 (회원인 경우) -->
    <?php if ($registration['cr_mb_id']) { ?>
    <div class="view_section">
        <div class="section_header">회원 정보</div>
        <div class="section_body">
            <table class="info_table">
                <tr>
                    <th>회원 ID</th>
                    <td><?=$registration['cr_mb_id']?></td>
                </tr>
                <tr>
                    <th>회원 레벨</th>
                    <td><?=$registration['mb_level']?></td>
                </tr>
                <tr>
                    <th>가입일</th>
                    <td><?=date('Y-m-d H:i:s', strtotime($registration['mb_datetime']))?></td>
                </tr>
                <?php if ($registration['mb_login_ip']) { ?>
                <tr>
                    <th>최근 접속 IP</th>
                    <td><?=$registration['mb_login_ip']?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <?php } ?>

    <!-- 제출된 초록 현황 -->
    <div class="view_section">
        <div class="section_header">제출된 초록 현황</div>
        <div class="section_body">
            <div class="abstract_summary">
                <strong>총 제출된 초록 수:</strong> <?=number_format($abstract_count['cnt'])?>개
                <?php if ($abstract_count['cnt'] > 0) { ?>
                <br><br>
                <a href="./summary_list.php?cr_id=<?=$cr_id?>" class="btn btn_03">이 신청자의 초록 보기</a>
                <?php } else { ?>
                <br><span style="color: #666;">아직 제출된 초록이 없습니다.</span>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- 관리자 메모 -->
    <div class="view_section">
        <div class="section_header">관리자 메모</div>
        <div class="section_body">
            <form method="post" action="./registration_memo_update.php">
                <input type="hidden" name="cr_id" value="<?=$cr_id?>">
                
                <div class="form_group">
                    <label for="admin_memo" class="form_label">메모</label>
                    <textarea name="admin_memo" id="admin_memo" class="review_textarea" placeholder="관리자 메모를 입력하세요..."><?=htmlspecialchars($registration['cr_admin_memo'])?></textarea>
                </div>
                
                <div class="btn_group">
                    <button type="submit" class="btn btn_01">메모 저장</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 하단 버튼 -->
    <div class="btn_group">
        <a href="./registration_list.php" class="btn btn_02">목록으로</a>
        <a href="./registration_print.php?cr_id=<?=$cr_id?>" class="btn btn_03" target="_blank">인쇄용 보기</a>
    </div>
</div>
<iframe name="hidfrm" style="display:none;"></iframe>
<script>
function updateStatus() {
    const form = document.getElementById('statusForm');
    const selectedStatus = form.querySelector('input[name="status"]:checked');
    
    if (!selectedStatus) {
        alert('상태를 선택해주세요.');
        return;
    }
    
    const statusText = {
        'registered': '신청완료',
        'cancelled': '취소'
    };
    
    const currentStatus = '<?=$registration['cr_status']?>';
    const newStatus = selectedStatus.value;
    
    if (currentStatus === newStatus) {
        alert('현재 상태와 동일합니다.');
        return;
    }
    
    if (confirm(`상태를 "${statusText[newStatus]}"로 변경하시겠습니까?`)) {
        form.submit();
    }
}
function updatePayment(){
	f=document.paymentform;
	f.submit();
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>