<?php
include "../../../../common.php";

$tNum="마이페이지";
$sNum="마이페이지";

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


include_once(G5_THEME_PATH.'/head2.php');
?>

<div class="mypage common">
  <div class="width">
   <div class="sub_menu">
        <ul>
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_1.php" class="menu_on">회원정보 수정</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_2.php">회비 납부 내역</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_3.php">학술대회 신청 내역</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_4.php">초록 제출 및 수정</a></li>
        </ul>
    </div>

    <h2>회원정보 수정</h2>
	<table class="meminfo">
        <tr>
            <th>회원구분</th>
            <td><?php echo $member['mb_memclass'] == 'member' ? 'Member' : 'Student'; ?></td>
        </tr>
        <tr>
            <th>성명</th>
            <td class="space">
                <span><?php 
                    $title_names = array(
                        'dr' => 'Dr.',
                        'prof' => 'Prof.',
                        'ms' => 'Ms.',
                        'mr' => 'Mr.'
                    );
                    echo isset($title_names[$member['mb_title']]) ? $title_names[$member['mb_title']] : '';
                ?></span>
                <?php if ($member['mb_title']) { ?><span class="bar"></span><?php } ?>
                <span><?php echo get_text($member['mb_name']); ?></span>
                <?php if ($member['mb_name_en']) { ?><span class="bar"></span><?php } ?>
                <span><?php echo get_text($member['mb_name_en']); ?></span>
            </td>
        </tr>
        <tr>
            <th>생년월일</th>
            <td><?php echo $member['mb_birth'] ? date('Y.m.d', strtotime($member['mb_birth'])) : '-'; ?></td>
        </tr>
        <tr>
            <th>면허번호</th>
            <td><?php echo $member['mb_license_none'] ? '면허번호 없음' : ($member['mb_license_no'] ? get_text($member['mb_license_no']) : '-'); ?></td>
        </tr>
        <tr>
            <th>직군</th>
            <td><?php 
                if ($member['mb_memclass'] == 'member') {
                    echo get_text($member['mb_job_class']);
                } else {
                    echo get_text($member['mb_student_class']);
                }
            ?></td>
        </tr>
        <tr>
            <th>이메일</th>
            <td><?php echo get_text($member['mb_email']); ?></td>
        </tr>
        <tr>
            <th>휴대전화</th>
            <td><?php echo $member['mb_hp_country'] . ' ' . get_text($member['mb_hp']); ?></td>
        </tr>
        <tr>
            <th>학력</th>
            <td class="space2">
                <span><?php echo get_text($member['mb_school']); ?></span>
                <?php if ($member['mb_school'] && $member['mb_major']) { ?>·<?php } ?>
                <span><?php echo get_text($member['mb_major']); ?></span>
                <?php if ($member['mb_major'] == '기타(전공)' && $member['mb_major_etc']) { ?>
                    (<?php echo get_text($member['mb_major_etc']); ?>)
                <?php } ?>
                <?php if ($member['mb_major'] && $member['mb_branch']) { ?>·<?php } ?>
                <span><?php echo get_text($member['mb_branch']); ?></span>
                <?php if ($member['mb_branch'] && $member['mb_school_etc']) { ?>·<?php } ?>
                <span><?php echo get_text($member['mb_school_etc']); ?></span>
            </td>
        </tr>
        <tr>
            <th>근무지</th>
            <td><?php echo get_text($member['mb_work_name']); ?></td>
        </tr>
        <tr>
            <th>근무지 주소</th>
            <td>
                <?php if ($member['mb_work_zip']) { ?>[<?php echo $member['mb_work_zip']; ?>] <?php } ?>
                <?php echo get_text($member['mb_work_addr1']); ?>
                <?php if ($member['mb_work_addr2']) { ?> <?php echo get_text($member['mb_work_addr2']); ?><?php } ?>
            </td>
        </tr>
        <tr>
            <th>근무지 전화번호</th>
            <td><?php echo get_text($member['mb_work_tel']); ?></td>
        </tr>
        <tr>
            <th>동의 현황</th>
            <td>
                회원검색: <?php echo $member['mb_search_agree']; ?> | 
                메일수신: <?php echo $member['mb_mailling'] ? '동의' : '거부'; ?> | 
                SMS수신: <?php echo $member['mb_sms'] ? '동의' : '거부'; ?>
            </td>
        </tr>
    </table>

    <div class="btn_center">
		<a href="javascript:void(0)" class="mypage_btn" onclick="pop_open()">회원정보 수정</a>
    </div>

    

<div id="my_popup" class="my_pop_wrap">
  <div class="my_pop-inner">
    <h3>회원정보 수정</h3>
    <form id="memberVerifyForm" method="post" action="">
    <div class="my_pop-text">
     <div class="pop_title">
        <img src="<?php echo G5_THEME_IMG_URL ?>/mem_icon1.png" alt="">
        <div>
            <h4>회원정보 수정을 원하시면</h4>
            <h5>이메일과 비밀번호를 입력해주세요.</h5>
        </div>
     </div>
     <div class="pop_input_wrap">
        <input type="email" name="verify_email" id="verify_email" placeholder="이메일을 입력해주세요." required value="<?php echo get_text($member['mb_email']); ?>" readonly>
        <input type="password" name="verify_password" id="verify_password" placeholder="비밀번호를 입력해주세요." required>
     </div>
     <div class="txt">
        <p>비밀번호 분실시 고객센터로 문의 주세요</p>
        <h6>T. 02-2273-3875</h6>
     </div>
     <div class="pop_btn_wrap">
        <div class="my_pop_close" onclick="pop_close();">취소</div>
        <a href="javascript:void(0)" class="my_pop_ok" onclick="verifyMember();">확인</a>
     </div>
    </div>
    </form>
  </div>
</div>
<form name="confirmform" method="post" action="<?=G5_BBS_URL?>/register_form.php">
<input type="hidden" name="w" value="u">
<input type="hidden" name="mb_id" id="mb_id">
<input type="hidden" name="mb_password" id="mb_password">
</form>
<script>
function pop_open() {
  document.getElementById('my_popup').style.display = 'flex';
  document.body.classList.add('popup-open');
  document.getElementById('verify_password').focus();
}

function pop_close() {
  document.getElementById('my_popup').style.display = 'none';
  document.body.classList.remove('popup-open');
  document.getElementById('verify_password').value = '';
}

function verifyMember() {
    var email = document.getElementById('verify_email').value;
    var password = document.getElementById('verify_password').value;
    
    if (!email) {
        alert('이메일을 입력해주세요.');
        document.getElementById('verify_email').focus();
        return false;
    }
    
    if (!password) {
        alert('비밀번호를 입력해주세요.');
        document.getElementById('verify_password').focus();
        return false;
    }
    
    // AJAX로 비밀번호 확인
    $.ajax({
        url: '<?php echo G5_BBS_URL ?>/member_confirm_update.php',
        type: 'POST',
        data: {
            mb_id: '<?php echo $member['mb_id']; ?>',
            mb_email: email,
            mb_password: password
        },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                // 회원정보 수정 페이지로 이동
				document.confirmform.mb_id.value=response.mb_id;
				document.confirmform.mb_password.value=password;
				document.confirmform.submit();
                //location.href = '<?php echo G5_BBS_URL ?>/register_form.php?w=u&mb_id=<?php echo $member['mb_id']; ?>';
            } else {
                alert(response.message || '비밀번호가 일치하지 않습니다.');
                document.getElementById('verify_password').focus();
            }
        },
        error: function() {
            alert('처리 중 오류가 발생했습니다.');
        }
    });
}

// 엔터키 처리
$(document).ready(function() {
    $('#verify_password').keypress(function(e) {
        if (e.which == 13) {
            verifyMember();
        }
    });
});

// 팝업 외부 클릭시 닫기
$(document).ready(function() {
    $('#my_popup').click(function(e) {
        if (e.target === this) {
            pop_close();
        }
    });
});
</script>


  </div>
</div>



<?php
include_once(G5_THEME_PATH.'/tail.php');
