<?php
include "../../../../common.php";

$tNum="마이페이지";
$sNum="마이페이지";

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$od_id = get_uniqid();
set_session('ss_order_id', $od_id);

include_once(G5_THEME_PATH.'/head2.php');
?>

<div class="mypage common">
  <div class="width">
    <div class="sub_menu">
        <ul>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_1.php">회원정보 수정</a></li>
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_2.php">회비 납부 내역</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_3.php">학술대회 신청 내역</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_4.php">초록 제출 및 수정</a></li>
        </ul>
    </div>

    <h2>회비 납부</h2>

    <!-- 기존 납부 완료 내역 -->
    <div class="payment_info">
        <h3>회비 납부 완료 내역<span class="scroll_768">* 좌우로 스크롤 해주세요.</span></h3>
        <div class="table_wrap">
        <table>
            <tr>
                <th style="width:20%;">결제항목</th>
                <th style="width:28%;">회원 기간</th>
                <th style="width:12%;">결제금액</th>
                <th style="width:12%;">결제구분</th>
                <th style="width:12%;">결제상태</th>
                <th style="width:16%;">비고</th>
            </tr>
            <?php
			$bank_cancel=0;
				$status_map = array(
					'pending' => '납부예정',
					'completed' => '완료',
					'cancelled' => '취소'
				);

            // 완료된 납부 내역 조회
            $completed_sql = "SELECT a.*, b.mb_memclass FROM g5_membership a 
                             LEFT JOIN g5_member b ON a.mb_member_id = b.mb_id 
                             WHERE a.mb_member_id = '{$member['mb_id']}' 
                             AND a.mb_payment_method <> '' 
                             ORDER BY a.mb_reg_date DESC";
            $completed_result = sql_query($completed_sql);
            
            if (sql_num_rows($completed_result) > 0) {
                while ($completed_row = sql_fetch_array($completed_result)) {
                    $type_map = array('annual' => '연회비', 'entrance' => '입회비');
                    $type_text = isset($type_map[$completed_row['mb_type']]) ? $type_map[$completed_row['mb_type']] : $completed_row['mb_type'];
					if($completed_row['mb_amount']==900000){
						$period="-";
					}else{
						$period = $completed_row['mb_start_date'] . ' ~ ' . ($completed_row['mb_end_date'] == '9999-12-31' ? '무제한' : $completed_row['mb_end_date']);
					}

					if($completed_row['mb_payment_method']=="bank" && $completed_row['mb_status']=="pending") $bank_cancel++;

            ?>
            <tr>
                <td style="width:20%;"><?=$type_text?>(<?=$completed_row['mb_memclass']?>)</td>
                <td style="width:28%;"><?=$period?></td>
                <td style="width:12%;"><?=number_format($completed_row['mb_amount'])?>원</td>
                <td style="width:12%;"><?=$completed_row['mb_payment_method'] ?: '카드결제'?></td>
                <td style="width:12%;"><?=$status_map[$completed_row['mb_status']]?></td>
                <td style="width:16%;"><?=($completed_row['mb_approve_date'] && $completed_row['mb_approve_date']!="0000-00-00 00:00:00")?date('Y-m-d', strtotime($completed_row['mb_approve_date'])):"";?></td>
            </tr>
            <?php
                }
            } else {
            ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #999;">완료된 납부 내역이 없습니다.</td>
            </tr>
            <?php } ?>
        </table>
        </div>
    </div>

    <?php
    $page = (int)$_POST['page'] ?: 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;
            
    $where = "WHERE mb_member_id='{$member['mb_id']}' and mb_payment_method='' ";
    // 총 개수 조회
    $count_sql = "SELECT COUNT(*) as cnt FROM g5_membership $where";
    $count_result = sql_fetch($count_sql);
    $total_count = $count_result['cnt'];
            
    // 목록 조회
    $sql = "SELECT a.*, b.mb_memclass FROM g5_membership a left join g5_member b on a.mb_member_id=b.mb_id 
                    $where 
                    ORDER BY a.mb_reg_date DESC 
                    LIMIT $offset, $per_page";
    $result = sql_query($sql);
    ?>

    <!-- 납부 대기 내역 -->
    <form name="ordform" id="ordform" method="post" action="membership_update.php">
        <div class="payment_info">
            <h3>회비 납부 대기 내역<span class="scroll_768">* 좌우로 스크롤 해주세요.</span></h3>
            <div class="table_wrap">
            <table id="paymentTable">
                <tr>
                    <th style="width:5%;">
                        <input type="checkbox" id="checkAll">
                    </th>
                    <th style="width:15%;">결제항목</th>
                    <th style="width:23%;">구분</th>
                    <th style="width:10%;">기본금액</th>
                    <th style="width:7%;">할인율</th>
                    <th style="width:10%;">결제금액</th>
                    <th style="width:10%;">결제상태</th>
                    <th style="width:20%;">비고</th>
                </tr>
                <?php
                $total_amount = 0;
                if (sql_num_rows($result) > 0) {
                    for($i=0; $row=sql_fetch_array($result); $i++) {
                        // 회비종류 한글화
                        $type_map = array(
                            'annual' => '연회비',
                            'entrance' => '입회비'
                        );
                        $row['mb_type_text'] = isset($type_map[$row['mb_type']]) ? $type_map[$row['mb_type']] : $row['mb_type'];
                ?>
                <tr class="payment-row" data-amount="<?=$row['mb_amount']?>" data-type="<?=$row['mb_type']?>">
                    <td style="width:5%;">
                        <input type="checkbox" name="mb_no[]" id="mb_no_<?=$i?>" class="row-check" value="<?=$row['mb_id']?>" data-amount="<?=$row['mb_amount']?>" data-type="<?=$row['mb_type']?>">
                    </td>
                    <td style="width:15%;"><?=$row['mb_type_text']?>(<?=$row['mb_memclass']?>)</td>
                    <td style="width:23%;">
                        <?php if($row['mb_type']=="annual") { ?>
                        <select name="od_qty[]" id="od_qty_<?=$i?>" class="period-select" data-row="<?=$i?>">
                            <option value="1" data-discount="0">12개월 결제</option>
                            <option value="2" data-discount="5">24개월 결제 (5% 할인)</option>
                            <option value="3" data-discount="10">36개월 결제 (10% 할인)</option>
							<!-- <option value="4" data-discount="0">평생회원(90만원)</option> -->
                        </select>
                        <?php } else { ?>
                        <select name="od_qty[]" id="od_qty_<?=$i?>" class="period-select" data-row="<?=$i?>">
                            <option value="1" data-discount="0">일시납</option>
                        </select>
                        <?php } ?>
                    </td>
                    <td style="width:10%;" class="base-amount"><?=number_format($row['mb_amount'])?>원</td>
                    <td style="width:7%;" class="discount-rate">0%</td>
                    <td style="width:10%;" class="final-amount"><?=number_format($row['mb_amount'])?>원</td>
                    <td style="width:10%;">결제대기</td>
                    <td style="width:20%;"></td>
                </tr>
                <?php
                    }
                } else {
                ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #999;">납부 대기 중인 회비가 없습니다.</td>
                </tr>
                <?php } ?>
            </table>
            </div>

            <div class="payment-summary" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span>선택한 항목: <strong id="selectedCount">0</strong>건</span>
                        <span style="margin-left: 20px;">총 할인금액: <strong id="totalDiscount" style="color: #e74c3c;">0</strong>원</span>
                    </div>
                    <div style="font-size: 18px; font-weight: bold;">
                        합계: <span id="totalAmount" style="color: #2c3e50;">0</span>원
                    </div>
                </div>
            </div>

            <div class="account_info">
                <table>
                    <tr>
                        <th><img src="<?php echo G5_THEME_IMG_URL ?>/icon2.png" alt="" ><p> <span>입금계좌 안내 :</span> 농협은행 301-0372-3344-61 (대한치과이식임플란트 학회)</p></th> 
                    </tr>
                    <tr>
                        <td>
                            <p>학회 사무실에서 확인이 되면 사전등록시 입력한 이메일로 입금 확인 메일이 발송됩니다.<br>
등록비 결제 관련 문의는 <span>kaid@kaidimplant.or.kr</span>로 부탁드립니다.</p>
                        </td>
                    </tr>
                    
                </table>
            </div>

            <div class="btn_wrap" style="margin-top: 20px;">
                <a href="javascript:order_submit()" id="cardPayBtn" >카드결제</a>
                <a href="javascript:bank_transfer()" id="bankPayBtn">무통장입금</a>
                <?php if($bank_cancel>0){?><a href="javascript:bank_cancel();" >무통장취소</a><?php }?>
            </div>
        </div>
        
        <input type="hidden" name="paymentType" id="paymentType" value="카드">
        <input type="hidden" name="orderId" id="orderId" value="<?=$od_id?>">
        <input type="hidden" name="orderName" id="orderName" value="회비 납부">
        <input type="hidden" name="amount" id="amount" value="0">
        <input type="hidden" name="customerName" id="customerName" value="<?=$member['mb_name']?>">
        <input type="hidden" name="selectedItems" id="selectedItems" value="">
    </form>
  </div>
</div>

<script>
// 전역 변수
let paymentData = {};

// DOM 로드 완료 후 실행
document.addEventListener('DOMContentLoaded', function() {
    initializePaymentSystem();
});

// 결제 시스템 초기화
function initializePaymentSystem() {
    const checkAll = document.getElementById('checkAll');
    const rowChecks = document.querySelectorAll('.row-check');
    const periodSelects = document.querySelectorAll('.period-select');

    // 전체 선택/해제 이벤트
    if (checkAll) {
        checkAll.addEventListener('change', function () {
            rowChecks.forEach(cb => {
                cb.checked = this.checked;
            });
            updatePaymentSummary();
        });
    }

    // 개별 체크박스 이벤트
    rowChecks.forEach(cb => {
        cb.addEventListener('change', function () {
            updateCheckAllStatus();
            updatePaymentSummary();
        });
    });

    // 기간 선택 변경 이벤트
    periodSelects.forEach(select => {
        select.addEventListener('change', function() {
            updateRowAmount(this);
            updatePaymentSummary();
        });
    });

    // 초기 상태 업데이트
    updatePaymentSummary();
}

// 전체 선택 체크박스 상태 업데이트
function updateCheckAllStatus() {
    const checkAll = document.getElementById('checkAll');
    const rowChecks = document.querySelectorAll('.row-check');
    const allChecked = Array.from(rowChecks).every(cb => cb.checked);
    const anyChecked = Array.from(rowChecks).some(cb => cb.checked);
    
    checkAll.checked = allChecked;
    checkAll.indeterminate = !allChecked && anyChecked;
}

// 개별 행의 금액 업데이트
function updateRowAmount(selectElement) {
    const row = selectElement.closest('.payment-row');
    const baseAmount = parseInt(selectElement.closest('tr').querySelector('.row-check').dataset.amount);
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const discountRate = parseInt(selectedOption.dataset.discount) || 0;
    const months = parseInt(selectedOption.value);

    let totalAmount;
    let discountAmount;
    let finalAmount;

    if (months === 4) {
        totalAmount = 900000; // 평생회원 금액 90만원
        discountAmount = 0; // 평생회원은 할인 없음
        finalAmount = totalAmount;
    } else {
        // 기존 로직 (일반 회비 계산)
        totalAmount = baseAmount * months;
        discountAmount = Math.floor(totalAmount * discountRate / 100);
        finalAmount = totalAmount - discountAmount;
    }
    
    // UI 업데이트
    row.querySelector('.discount-rate').textContent = discountRate + '%';
    row.querySelector('.final-amount').textContent = number_format(finalAmount) + '원';
    
    // 체크박스 데이터 업데이트
    const checkbox = row.querySelector('.row-check');
    checkbox.dataset.finalAmount = finalAmount;
    checkbox.dataset.discountAmount = discountAmount;
    checkbox.dataset.months = months;
}

// 결제 요약 정보 업데이트
function updatePaymentSummary() {
    const checkedBoxes = document.querySelectorAll('.row-check:checked');
    let totalAmount = 0;
    let totalDiscount = 0;
    let selectedItems = [];

    checkedBoxes.forEach(checkbox => {
        const finalAmount = parseInt(checkbox.dataset.finalAmount) || parseInt(checkbox.dataset.amount);
        const discountAmount = parseInt(checkbox.dataset.discountAmount) || 0;
        const months = parseInt(checkbox.dataset.months) || 1;
        
        totalAmount += finalAmount;
        totalDiscount += discountAmount;
        
        selectedItems.push({
            id: checkbox.value,
            amount: finalAmount,
            months: months,
            discount: discountAmount
        });
    });

    // UI 업데이트
    document.getElementById('selectedCount').textContent = checkedBoxes.length;
    document.getElementById('totalAmount').textContent = number_format(totalAmount);
    document.getElementById('totalDiscount').textContent = number_format(totalDiscount);
    document.getElementById('amount').value = totalAmount;
    document.getElementById('selectedItems').value = JSON.stringify(selectedItems);

    // 결제 버튼 활성화/비활성화
    const cardPayBtn = document.getElementById('cardPayBtn');
    const bankPayBtn = document.getElementById('bankPayBtn');
    
    if (checkedBoxes.length > 0 && totalAmount > 0) {
        cardPayBtn.style.backgroundColor = '#495FA1';
        cardPayBtn.style.pointerEvents = 'auto';
        bankPayBtn.style.backgroundColor = '#495FA1';
        bankPayBtn.style.pointerEvents = 'auto';
    } else {
        cardPayBtn.style.backgroundColor = '#ccc';
        cardPayBtn.style.pointerEvents = 'none';
        bankPayBtn.style.backgroundColor = '#ccc';
        bankPayBtn.style.pointerEvents = 'none';
    }
}

// 숫자 포맷팅 함수
function number_format(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// 주문명 생성
function generateOrderName() {
    const checkedBoxes = document.querySelectorAll('.row-check:checked');
    if (checkedBoxes.length === 0) return '회비 납부';
    
    const firstItem = checkedBoxes[0].closest('tr').querySelector('td:nth-child(2)').textContent.trim();
    if (checkedBoxes.length === 1) {
        return firstItem;
    } else {
        return firstItem + ' 외 ' + (checkedBoxes.length - 1) + '건';
    }
}

// 결제 검증
function validatePayment() {
    const checkedBoxes = document.querySelectorAll('.row-check:checked');
    const totalAmount = parseInt(document.getElementById('amount').value);
    
    if (checkedBoxes.length === 0) {
        alert('결제할 회비를 선택해주세요.');
        return false;
    }
    
    if (totalAmount <= 0) {
        alert('결제 금액이 올바르지 않습니다.');
        return false;
    }
    
    return true;
}
function bank_cancel(){
	var res=confirm("납부예정인 무통장결제 신청내역을 취소하시겠습니까?");
	if(res){
		location.href="membership_bank_cancel.php";
	}
}
</script>

<script src="//js.tosspayments.com/v1/payment"></script>
<script>
// Toss Payments 설정
let clientKey = '<?=$config['cf_pg_clientkey']?>';
let tossPayments = TossPayments(clientKey);

// 결제 데이터 설정
let paymentDataConfig = {
    "공통": {
        "amount": "",
        "orderId": "",
        "orderName": "",
        "customerName": "",
        "customerEmail": null,
        "customerMobilePhone": null,
        "successUrl": g5_url + "/theme/nero/subpage/mypage/membership_success.php",
        "failUrl": g5_url + "/theme/nero/subpage/mypage/membership_fail.php",
        "windowTarget": "iframe",
        "taxFreeAmount": null,
        "cultureExpense": false
    },
    "카드": {
        "cardCompany": null,
        "cardInstallmentPlan": null,
        "maxCardInstallmentPlan": null,
        "freeInstallmentPlans": null,
        "useCardPoint": false,
        "useAppCardOnly": false,
        "useInternationalCardOnly": false,
        "flowMode": "DEFAULT",
        "easyPay": null,
        "discountCode": null,
        "appScheme": null
    },
    "가상계좌": {
        "validHours": 72,
        "cashReceipt": {
            "type": "소득공제"
        },
        "useEscrow": false,
        "escrowProducts": null,
        "currency": "KRW"
    }
};

// 카드 결제
function order_submit() {
    if (!validatePayment()) return;
    
    // 주문명 업데이트
    document.getElementById('orderName').value = generateOrderName();
    document.getElementById('paymentType').value = '카드';
    
    // 주문 정보 임시저장
    var order_data = $(document.ordform).serialize();
    var save_result = "";
    
    $.ajax({
        type: "POST",
        data: order_data,
        url: g5_url + "/shop/ajax.orderdatasave.php",
        cache: false,
        async: false,
        success: function(data) {
            save_result = data;
        }
    });

    if (save_result) {
        alert(save_result);
        return false;
    }

    let requestJson = initPaymentsData("공통", "카드");
    
    tossPayments.requestPayment("카드", requestJson)
        .catch(function (error) {
            if (error.code === 'USER_CANCEL') {
                alert('결제가 취소되었습니다.');
            } else if (error.code === 'INVALID_CARD_COMPANY') {
                alert('유효하지 않은 카드입니다.');
            } else {
                alert('결제 중 오류가 발생했습니다: ' + error.message);
            }
        });
}

// 무통장입금
function bank_transfer() {
    if (!validatePayment()) return;
    
    document.getElementById('orderName').value = generateOrderName();
    document.getElementById('paymentType').value = '무통장';
	
	document.ordform.submit();
    
}

// 결제 데이터 초기화
function initPaymentsData(general, paymentType) {
    paymentDataConfig[general].amount = document.getElementById("amount").value;
    //paymentDataConfig[general].amount = 1004;
    paymentDataConfig[general].orderId = document.getElementById("orderId").value;
    paymentDataConfig[general].orderName = document.getElementById("orderName").value;
    paymentDataConfig[general].customerName = document.getElementById("customerName").value;
    
    return Object.assign({}, paymentDataConfig[general], paymentDataConfig[paymentType]);
}
</script>


<?php
include_once(G5_THEME_PATH.'/tail.php');
