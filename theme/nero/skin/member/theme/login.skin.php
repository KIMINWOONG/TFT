<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/info.css">', 0);
include_once(G5_THEME_PATH.'/head2.php');
?>
<div class="k_login width">
<h2>Login</h2>

<div class="mem_login">
    
</div>

<!-- Login 시작 { -->
<div id="mb_login" class="mbskin">
    <div class="mbskin_box">
        <div class="mb_log_cate">
          <form name="flogin" action="<?=G5_BBS_URL?>/nonmember_login_check.php" onsubmit="return flogin_submit(this);" method="post">
          <input type="hidden" name="url" value="<?php echo $login_url ?>">
           <fieldset id="login_fs">
              <h2>Non-member Login</h2>
              <div class="login_wrap_box">
                <label for="member_name">Name</label>
                <input type="text" name="member_name" id="member_name" required class="frm_input required" size="20" maxLength="20" >
              </div>
              <div class="login_wrap_box">
                <label for="member_birth">Birth Date</label>
                <input type="text" name="member_birth" id="member_birth" required class="frm_input required" size="20" maxLength="20"placeholder="EX : 19801212" >
              </div>

              <button type="submit" class="btn_submit">Login</button>

        
          </fieldset>
		  </form>
            
        </div>

        <div class="line"></div>

        <div class="mb_log_form">
          <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
          <input type="hidden" name="url" value="<?php echo $login_url ?>">

          <fieldset id="login_fs">
              <h2>Member Login</h2>
              <div class="login_wrap_box">
                <label for="login_id">ID</label>
                <input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxLength="20" >
              </div>
              <div class="login_wrap_box">
                <label for="login_pw">Password</label>
                <input type="password" name="mb_password" id="login_pw" required class="frm_input required" size="20" maxLength="20" >
              </div>
              <?php @include_once(get_social_skin_path().'/social_login.skin.php'); // 소셜Login 사용시 소셜Login 버튼 ?>
              <button type="submit" class="btn_submit">Login</button>

              <div id="login_info">
                  
                  <div class="login_if_lpl">
                    <a href="<?php echo G5_BBS_URL ?>/register.php">Join</a>
                    <span></span>
                      <!-- <a href="<?php echo G5_BBS_URL ?>/password_lost.php" id="login_password_lost">Find ID/Password</a> -->
                      <a href="<?php echo G5_BBS_URL ?>/password_lost.php" >Find ID/Password</a>
                  </div>
              </div>
          </fieldset>
          </form>
        </div>
    </div>
</div>
</div>
<script>
jQuery(function($){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
    if( $( document.body ).triggerHandler( 'login_sumit', [f, 'flogin'] ) !== false ){
        return true;
    }
    return false;
}
</script>
<!-- } 로그인 끝 -->
<?php
include_once(G5_THEME_PATH.'/tail.php');
