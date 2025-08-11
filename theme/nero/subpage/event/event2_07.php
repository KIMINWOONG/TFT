<?php
include "../../../../common.php";
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

if (!$is_member && !$is_nonemember)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_THEME_URL."/subpage/event/event2_07.php"));

if($is_nonemember){
	$cr_nonemb_name=get_session("ss_nonemb_name");
	$cr_nonemb_birth=get_session("ss_nonemb_birth");
}

$conference=sql_fetch("select * from g5_conference where sy_gubun='1' and sy_status='active' order by sy_id desc limit 0,1 ");

if (!$conference) {
    alert("현재 진행중인 학술집담회가 없습니다.");
}

if($is_member){
	$sql="select * from g5_conference_registration where cr_sy_id='{$conference['sy_id']}' and cr_mb_id='{$member['mb_id']}' and cr_status not in('cancelled')";
}else{
	$sql="select * from g5_conference_registration where cr_sy_id='{$conference['sy_id']}' and (cr_nonemb_name='{$cr_nonemb_name}' and cr_nonemb_birth='{$cr_nonemb_birth}') and cr_status not in('cancelled')";
}
$registration=sql_fetch($sql);
if($registration){
	alert("신청한 내역이 있습니다.", G5_THEME_URL."/subpage/event/event2_08.php");
}

$tNum = "학술행사";
$sNum = "학술집담회 안내";
$bNum="2";
$g5['title'] = "";

$od_id = get_uniqid();
set_session('ss_order_id', $od_id);

//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$current_date = date('Y-m-d');
$is_early_reg_period = ($current_date >= $conference['sy_early_reg_start'] && $current_date <= $conference['sy_early_reg_end']);
$is_reg_period = ($current_date >= $conference['sy_reg_start'] && $current_date <= $conference['sy_reg_end']);
if (!$is_early_reg_period && !$is_reg_period){
	alert("등록기간이 아닙니다.");
}

$annual_fee_paid = false;
if ($is_member) {
    // 연회비 납부 여부 확인
    $current_year = date('Y');
    $membership_check = sql_fetch("SELECT * FROM g5_membership WHERE mb_member_id = '{$member['mb_id']}' AND (now() between mb_start_date and mb_end_date) AND mb_status = 'completed' AND mb_type = 'annual'");
    $annual_fee_paid = $membership_check ? true : false;

	if(!$annual_fee_paid){
		alert("연회비 납부가 필요합니다");
	}

	if($annual_fee_paid){
		$amount=$conference['sy_fee_member'];
	}else{
		$amount=$conference['sy_fee_associate'];
	}
}else{
	$amount=$conference['sy_fee_nonmember'];
}

include_once(G5_THEME_PATH.'/head.php');
?>

<div class="common event_2" id="event_point">
  <div class="width">
    <div class="sub_menu">
        <ul>
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_01.php" class="menu_on">개최 예정 학술집담회</a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=symposium_last">지난 학술집담회 자료실</a></li>
        </ul>
    </div>
 <h2 class="contents_title"><?=$conference['sy_title']?> - 사전등록 신청</h2>

 <div class="event_menu">
    <ul class="event_02_2">
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_06.php#event_point">등록 안내</a></li>
        <li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_07.php#event_point">사전등록 신청</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_08.php#event_point">등록 신청 확인</a></li>
    </ul>
 </div>

 <div class="submission_form">
      <form id="ordform" name="ordform" action="event2_07_update.php" method="post" onsubmit="return ordform_submit(this);">
        <input type="hidden" name="sy_id" value="<?php echo $conference['sy_id']; ?>">
        <input type="hidden" name="reg_type" value="2">

	<div id="register_form" class="form_01">
        <h4 class="sub_title2">참가자 정보</h4>
		<div class="register_form_inner">
			<ul>
				<li>
					<label for="">
						<span class="required_mark">*</span>회원구분
					</label>
					<div class="input_inner auto_width">
					<?php if($is_member){?>
						<input type="radio" id="member" name="member_class" value="member" <?=$member['mb_memclass']=="member"?"checked":"";?>>
						<label for="member">Member</label>
						<input type="radio" id="student" name="member_class" value="student" class="margin" <?=$member['mb_memclass']=="student"?"checked":"";?>>
						<label for="student">Student</label>
						<input type="radio" id="fellow" name="member_class" value="fellow" class="margin">
						<label for="fellow">Fellow</label>
					<?php }?>
					<?php if($is_nonemember){?>
						<input type="radio" id="nonemember" name="member_class" value="nonemember" checked>
						<label for="nonemember">Non-Member</label>
					<?php }?>
					</div>
				</li>
				
				<li class="input_inner3">
					<label for="" class="padding">
						<span class="required_mark">*</span>성명
					</label>
					<div>
						<?php if($is_member){?><p>기존 Title : <?=$member['mb_title']?>.</p><?php }?>
						<div class="radio_wrap">
						<input type="radio" id="dr" name="title" value="dr" checked>
						<label for="dr">Dr.</label>
						<input type="radio" id="prof" name="title" value="prof" class="margin">
						<label for="prof">Prof.</label>
						<input type="radio" id="ms" name="title" value="ms" class="margin">
						<label for="ms">Ms.</label>
						<input type="radio" id="mr" name="title" value="mr" class="margin">
						<label for="mr">Mr.</label>
						</div>
						<div class="name_input_wrap">
                            <div>
                                <span>국문</span>
							    <input type="text" name="name_kor" value="<?=$member['mb_name']?>" id="name_kor" class="" minlength="2" maxlength="50" placeholder="예)홍길동" required>
                            </div>
                            <div>
                                <span>영문</span>
							    <input type="text" name="name_eng" value="<?=$member['mb_name_en']?>" id="name_eng" class="" minlength="2" maxlength="50" placeholder="예) KILDONG HONG" required>
                            </div>
						</div>
					</div>
				</li>
	            
		<li class="">
	                <label for="license_number"><span class="required_mark">*</span>면허번호</label>
					<div class="input_inner">
	                <input type="text" name="license_number" id="license_number" class="frm_input full_input" maxlength="50" value="<?=$member['mb_license_no']?>">
					<input type="checkbox" name="no_license" id="no_license" class="check_box">
					<label for="no_license">면허번호 없음</label>
					</div>
				</li>
				
				<li class="">
	                <label for=""><span class="required_mark">*</span>소속기관</label>
					<div class="input_inner">
	                <div class="name_input_wrap">
							<span>기관/병원명</span>
							<input type="text" name="hospital_name" value="<?=$member['mb_name']?>" id="hospital_name" class="" maxlength="100">
							<span>소속과</span>
							<input type="text" name="department" value="<?=$member['mb_name_en']?>" id="department" class="" maxlength="100">
						</div>
					</div>
				</li>
				
	            <script>
document.querySelector("form").addEventListener("submit", function(e) {
    const licenseInput = document.getElementById("license_number");
    const noLicenseCheckbox = document.getElementById("no_license");
    const hospitalName = document.getElementById("hospital_name");
    const department = document.getElementById("department");

    // 면허번호 조건 검사
    const hasLicense = licenseInput.value.trim() !== "";
    const checkedNoLicense = noLicenseCheckbox.checked;

    if (!hasLicense && !checkedNoLicense) {
        alert("면허번호를 입력하거나 '면허번호 없음'을 선택해야 합니다.");
        licenseInput.focus();
        e.preventDefault();
        return;
    }

    // 소속기관 검사
    if (hospitalName.value.trim() === "") {
        alert("기관/병원명을 입력해 주세요.");
        hospitalName.focus();
        e.preventDefault();
        return;
    }

    if (department.value.trim() === "") {
        alert("소속과를 입력해 주세요.");
        department.focus();
        e.preventDefault();
        return;
    }
});
</script>
			
	            <?php
				$mb_hp_arr=explode("-",$member['mb_hp']);
				?>
                <li>
	                <label for=""><span class="required_mark">*</span>휴대전화</label>
					<div class="input_inner number_input">
						<select name="mobile_carrier" id="mobile_carrier" required>
							<option value="">선택</option>
							<option value="010" <?=$mb_hp_arr[0]=="010"?"selected":"";?>>010</option>
							<option value="011" <?=$mb_hp_arr[0]=="011"?"selected":"";?>>011</option>
							<option value="016" <?=$mb_hp_arr[0]=="016"?"selected":"";?>>016</option>
							<option value="017" <?=$mb_hp_arr[0]=="017"?"selected":"";?>>017</option>
							<option value="018" <?=$mb_hp_arr[0]=="018"?"selected":"";?>>018</option>
							<option value="019" <?=$mb_hp_arr[0]=="019"?"selected":"";?>>019</option>
						</select>
						<span>-</span>
						<input type="text" name="mobile1" maxlength="4" required value="<?=$mb_hp_arr[1]?>">
						<span>-</span>
						<input type="text" name="mobile2" maxlength="4" required value="<?=$mb_hp_arr[2]?>">
					</div>
	            </li>

				<li class="email">
	                <label for="email"><span class="required_mark">*</span>이메일</label>
					<div class="input_inner">
	                <input type="email" name="email" value="<?=$member['mb_email']?>" id="email" required class="frm_input email full_input required" size="70" maxlength="100">
                    <span class="required_mark">*등록 확인에 이용되는 점 유의하시길 바랍니다.</span>
					</div>
	            </li>

                <?php if (!$is_member) { ?>
				<li>
	                <label for="password"><span class="required_mark">*</span>비밀번호</label>
					<div class="input_inner">
	                <input type="password" name="password" id="password" class="frm_input full_input" required>
                    <span class="required_mark">*초록제출시 이용되는 점 유의하시길 바랍니다.</span>
					</div>
	            </li>
                <?php } ?>

                <?php if ($is_member) { ?>
                    <?php if ($annual_fee_paid) { ?>
                    <li>
                        <label for="">연회비</label>
                        <div class="input_inner">
                        연회비 결제 완료 
                        </div>
                    </li>
                    <?php } else { ?>
                    <li>
                        <label for="">연회비</label>
                        <div class="input_inner">
                        <a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_2.php" class="btn_frmline">연회비 결제하러 가기</a> 
                        <span class="required_mark">*회비 미결제시 참석 불가합니다</span>
                        </div>
                    </li>
                    <?php } ?>
                <?php } ?>
				
				<li>
					<label for=""><span class="required_mark">*</span>결제방법</label>
					<div class="input_inner auto_width column">
						<input type="radio" id="card" name="payment_method" value="카드" required>
						<label for="card">신용카드</label>
						<input type="radio" id="bank" name="payment_method" value="무통장입금" required class="margin">
						<label for="bank">무통장입금</label><br class="br_650">
                        <p class="gray_text">*결제금액이 0원일 경우 무통장 입금을 선택 해 주세요</p>
					</div>
                    
				</li>
				
                <li>
					<label for="">입금하실 금액</label>
					<div class="input_inner auto_width">
						<span class="color_b" id="registration_fee"><?=number_format($amount)?></span>원
					</div>
				</li>
			</ul>
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

		<button type="submit" class="btn_style_baisc">사전등록</button>

        <input type="hidden" name="paymentType" id="paymentType" value="카드">
        <input type="hidden" name="orderId" id="orderId" value="<?=$od_id?>">
        <input type="hidden" name="orderName" id="orderName" value="<?=$conference['sy_title']?> 사전등록">
        <input type="hidden" name="amount" id="amount" value="<?=$amount?>">
        <input type="hidden" name="customerName" id="customerName" value="<?=$member['mb_name']?>">
	</form>
 </div>

  </div>
</div>

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
        "successUrl": g5_url + "/theme/nero/subpage/event/event2_07_success.php",
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
function ordform_submit(f) {
	if(!chkinput(f)){
		return false;
	}

	if(f.payment_method.value=="카드"){

		// 주문명 업데이트
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
		return false;
	}else{
		return true;
	}
}

// 무통장입금
function bank_transfer() {
    if (!validatePayment()) return;
    
    document.getElementById('paymentType').value = '가상계좌';
    
    let requestJson = initPaymentsData("공통", "가상계좌");
    
    tossPayments.requestPayment("가상계좌", requestJson)
        .catch(function (error) {
            if (error.code === 'USER_CANCEL') {
                alert('결제가 취소되었습니다.');
            } else {
                alert('결제 중 오류가 발생했습니다: ' + error.message);
            }
        });
}

// 결제 데이터 초기화
function initPaymentsData(general, paymentType) {
    paymentDataConfig[general].amount = document.getElementById("amount").value;
    //paymentDataConfig[general].amount = 1004;
    paymentDataConfig[general].orderId = document.getElementById("orderId").value;
    paymentDataConfig[general].orderName = document.getElementById("orderName").value;
    paymentDataConfig[general].customerName = document.getElementById("name_kor").value;
    
    return Object.assign({}, paymentDataConfig[general], paymentDataConfig[paymentType]);
}
</script>
<script>
function chkinput(f) {
    if (!f.member_class.value) {
        alert('회원구분을 선택해주세요.');
        return false;
    }
    
    if (!f.name_kor.value) {
        alert('성명(국문)을 입력해주세요.');
        f.name_kor.focus();
        return false;
    }
    
    if (!f.name_eng.value) {
        alert('성명(영문)을 입력해주세요.');
        f.name_eng.focus();
        return false;
    }
    
    if (!f.email.value) {
        alert('이메일을 입력해주세요.');
        f.email.focus();
        return false;
    }
    
    <?php if (!$is_member) { ?>
    if (!f.password.value) {
        alert('비밀번호를 입력해주세요.');
        f.password.focus();
        return false;
    }
    <?php } ?>
    
    if (!f.payment_method.value) {
        alert('결제방법을 선택해주세요.');
        return false;
    }
	return true;
    
}

// 회원구분에 따른 등록비 계산
document.addEventListener('DOMContentLoaded', function() {
   
    // 면허번호 없음 체크박스
    const noLicenseCheckbox = document.getElementById('no_license');
    const licenseInput = document.getElementById('license_number');
    
    noLicenseCheckbox.addEventListener('change', function() {
        if (this.checked) {
            licenseInput.value = '';
            licenseInput.disabled = true;
        } else {
            licenseInput.disabled = false;
        }
    });
});
</script>
<?php
include_once(G5_THEME_PATH.'/tail.php');
