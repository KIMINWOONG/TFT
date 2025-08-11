<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<style>
.customer_service { text-align: center; margin: 20px 0; }
.customer_service p { margin: 10px 0; color: #666; font-size: 14px; }
.customer_service h6 { margin: 5px 0; color: #007bff; font-size: 18px; font-weight: bold; }
.alert { padding: 10px; margin: 10px 0; border-radius: 5px; text-align: center; }
.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
.popup-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: none; }
.popup-content { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); max-width: 400px; width: 90%; text-align: center; }
.popup-content h3 { margin: 0 0 20px 0; color: #333; }
.popup-content p { margin: 0 0 20px 0; font-size: 16px; line-height: 1.5; }
.popup-content .success-icon { color: #28a745; font-size: 48px; margin-bottom: 15px; }
.popup-content .error-icon { color: #dc3545; font-size: 48px; margin-bottom: 15px; }
.popup-close { background: #007bff; color: white; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; font-size: 16px; }
.popup-close:hover { background: #0056b3; }
.loading { display: none; text-align: center; margin: 10px 0; }
.loading img { width: 24px; height: 24px; }
</style>
<!-- 회원정보 찾기 시작 { -->
<div id="find_info" class="new_win common find_idpw">
    <h1>Find ID/Password</h1>
    <?php if (isset($success_msg)) { ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php } ?>
    
    <?php if (isset($error_msg)) { ?>
        <div class="alert alert-error"><?php echo $error_msg; ?></div>
    <?php } ?>
    <div class="tab-container">
         <div class="tabs">
            <button class="tab_btn tab_active" onclick="showContent(0)">Find ID</button>
            <button class="tab_btn" onclick="showContent(1)">Find Password</button>
        </div>
       
        <div class="contents">
            <!-- 아이디찾기 -->
            <div class="tab_content" id="content-0">
			<form id="findIdForm">
				<input type="hidden" name="find_type" value="id">
                <h2>Forgot your <span>ID</span>?</h2>

                <div class="login_wrap_box">
                <label for="login_id">Name</label>
                <input type="text" name="mb_name" id="mb_name" required class="frm_input required" size="20" maxLength="20" >
              </div>
              <div class="login_wrap_box">
                <label for="">E-mail</label>
                <input type="text" name="mb_email" id="mb_email" required class="frm_input required" size="20"  maxLength="20">
              </div>
                    <div class="loading" id="loadingId">
                        <p>검색 중입니다...</p>
                    </div>
              <button type="submit" class="find_id">Find ID</button>
			</form>
            </div>

            <!-- 비밀번호 찾기 -->
            <div class="tab_content" id="content-1">
			<form id="findPasswordForm">
				<input type="hidden" name="find_type" value="password">
                <h2>Forgot your <span>Password</span>?</h2>

                
               <div class="login_wrap_box">
                <label for="">Enter ID</label>
                <input type="text" name="mb_id" id="mb_id_pwd" required class="frm_input required" size="20"  maxLength="20">
              </div>
              <div class="login_wrap_box">
                <label for="">Phone Number</label>
                <input type="text" name="mb_hp" id="mb_hp_pwd" required class="frm_input required" size="20"  maxLength="20">
              </div>
              <div class="login_wrap_box">
                <label for="">Password</label>
                <input type="password" name="mb_password" id="mb_password" required class="frm_input required" size="20"  maxLength="20">
              </div>
              <div class="login_wrap_box">
                <label for="">Confirm Password</label></label>
                <input type="password" name="mb_password_re" id="mb_password_re" required class="frm_input required" size="20"  maxLength="20">
              </div>

              <p>고객센터 연결</p>
              <h6>T. 02-2273-3875</h6>
                    <div class="loading" id="loadingPassword">
                        <p>처리 중입니다...</p>
                    </div>
              <button type="submit" class="find_id">Find Password</button>
			</form>
            </div>
        </div>


       
    </div>

   


    <!-- <div class="new_win_con">
        <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
        <fieldset id="info_fs">
            <p>
                회원가입 시 등록하신 이메일 주소를 입력해 주세요.<br>
                해당 이메일로 아이디와 비밀번호 정보를 보내드립니다.
            </p>
            <label for="mb_email" class="sound_only">E-mail 주소<strong class="sound_only">필수</strong></label>
            <input type="text" name="mb_email" id="mb_email" required class="required frm_input full_input email" size="30" placeholder="E-mail 주소">
        </fieldset>
        <?php echo captcha_html();  ?>
        <div class="win_btn">
            <button type="submit" class="btn_submit">확인</button>
            <button type="button" onclick="window.close();" class="btn_close">창닫기</button>  
        </div>
        </form>
    </div> -->
</div>


<!-- 팝업 모달 -->
<div class="popup-overlay" id="popupOverlay">
    <div class="popup-content">
        <div id="popupIcon"></div>
        <h3 id="popupTitle"></h3>
        <p id="popupMessage"></p>
        <button class="popup-close" onclick="closePopup()">확인</button>
    </div>
</div>
<script>
// 팝업 관련 함수
function showPopup(title, message, isSuccess) {
    $('#popupTitle').text(title);
    $('#popupMessage').text(message);
    
    if (isSuccess) {
        $('#popupIcon').html('<div class="success-icon">✓</div>');
    } else {
        $('#popupIcon').html('<div class="error-icon">✗</div>');
    }
    
    $('#popupOverlay').fadeIn(300);
}

function closePopup() {
    $('#popupOverlay').fadeOut(300);
}

// 아이디 찾기 폼 처리
$('#findIdForm').on('submit', function(e) {
    e.preventDefault();
    
    var formData = {
        ajax: 'find_member',
        find_type: 'id',
        mb_name: $('#mb_name').val(),
        mb_email: $('#mb_email').val()
    };
    
    $('#loadingId').show();
    $('.find_id').prop('disabled', true);
    
    $.ajax({
        type: 'POST',
        url: '/bbs/find_member_process.php',
        data: formData,
        dataType: 'json',
        success: function(response) {
            $('#loadingId').hide();
            $('.find_id').prop('disabled', false);
            
            if (response.success) {
                showPopup('아이디 찾기 성공', response.message, true);
                $('#findIdForm')[0].reset();
            } else {
                showPopup('아이디 찾기 실패', response.message, false);
            }
        },
        error: function() {
            $('#loadingId').hide();
            $('.find_id').prop('disabled', false);
            showPopup('오류', '서버 오류가 발생했습니다. 다시 시도해주세요.', false);
        }
    });
});

// 비밀번호 찾기 폼 처리
$('#findPasswordForm').on('submit', function(e) {
    e.preventDefault();
    
    var password = $('#mb_password').val();
    var passwordRe = $('#mb_password_re').val();
    
    if (password !== passwordRe) {
        showPopup('입력 오류', '비밀번호가 일치하지 않습니다.', false);
        return false;
    }
    
    if (password.length < 4) {
        showPopup('입력 오류', '비밀번호는 4자리 이상이어야 합니다.', false);
        return false;
    }
    
    var formData = {
        ajax: 'find_member',
        find_type: 'password',
        mb_id: $('#mb_id_pwd').val(),
        mb_hp: $('#mb_hp_pwd').val(),
        mb_password: password,
        mb_password_re: passwordRe
    };
    $('#loadingPassword').show();
    $('.find_id').prop('disabled', true);
    
    $.ajax({
        type: 'POST',
        url: '/bbs/find_member_process.php',
        data: formData,
        dataType: 'json',
        success: function(response) {
            $('#loadingPassword').hide();
            $('.find_id').prop('disabled', false);
            
            if (response.success) {
                showPopup('비밀번호 변경 성공', response.message, true);
                $('#findPasswordForm')[0].reset();
            } else {
                showPopup('비밀번호 변경 실패', response.message, false);
            }
        },
        error: function() {
            $('#loadingPassword').hide();
            $('.find_id').prop('disabled', false);
            showPopup('오류', '서버 오류가 발생했습니다. 다시 시도해주세요.', false);
        }
    });
});

function validatePassword() {
    var password = document.getElementById('mb_password').value;
    var passwordRe = document.getElementById('mb_password_re').value;
    
    if (password !== passwordRe) {
        alert('비밀번호가 일치하지 않습니다.');
        return false;
    }
    
    if (password.length < 4) {
        alert('비밀번호는 4자리 이상이어야 합니다.');
        return false;
    }
    
    return true;
}

function showContent(index) {
    // 모든 콘텐츠를 숨김 처리
    const contents = document.querySelectorAll(".tab_content");
    contents.forEach(content => {
        content.style.display = "none";
    });

    // 선택한 콘텐츠 표시
    document.getElementById(`content-${index}`).style.display = "block";

    // 모든 탭에서 'active' 클래스 제거
    const tabs = document.querySelectorAll(".tab_btn");
    tabs.forEach(tab => {
        tab.classList.remove("tab_active");
    });

    // 선택한 탭에 'active' 클래스 추가
    tabs[index].classList.add("tab_active");
}

// 페이지 로드 시 첫 번째 탭 활성화
$(document).ready(function() {
    showContent(0);
    
    // 화면 중앙 정렬
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    
    if (top < 0) top = 0;
    if (left < 0) left = 0;
    
    moveTo(left, top);
});

// 휴대폰 번호 자동 하이픈 추가
$(document).on('input', '#mb_hp_pwd', function() {
    var value = $(this).val().replace(/[^0-9]/g, '');
    var formattedValue = '';
    
    if (value.length <= 3) {
        formattedValue = value;
    } else if (value.length <= 7) {
        formattedValue = value.substring(0, 3) + '-' + value.substring(3);
    } else {
        formattedValue = value.substring(0, 3) + '-' + value.substring(3, 7) + '-' + value.substring(7, 11);
    }
    
    $(this).val(formattedValue);
});

// 팝업 외부 클릭시 닫기
$(document).on('click', '#popupOverlay', function(e) {
    if (e.target === this) {
        closePopup();
    }
});

// ESC 키로 팝업 닫기
$(document).on('keydown', function(e) {
    if (e.keyCode === 27) {
        closePopup();
    }
});
</script>
<!-- } 회원정보 찾기 끝 -->