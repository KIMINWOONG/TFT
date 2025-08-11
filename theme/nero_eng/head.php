<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/head.php');
    return;
}

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

include_once(G5_THEME_PATH.'/mildmenu.php');
?>

<?php
if(defined('_INDEX_')) { // index에서만 실행
    include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
}
?>

<div class="all_wrap">

  <!-- pc 상단 메뉴 -->
<nav id="header">
   <div class="head_login_wrap">
    <ul class="head_login width_m">
      <?php if ($is_member || $is_nonemember) {  ?>
	  	<?php if($is_member){?>
      <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_1.php" class="login_btn">Mypage</a></li>
		<?php }else{?>
      <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_5.php" class="login_btn">Mypage</a></li>
		<?php }?>
      <li><a href="<?php echo G5_BBS_URL ?>/logout.php">Logout</a></li>
      
      <?php if ($is_admin) {  ?>
      <li><a href="<?php echo G5_ADMIN_URL ?>" target=http://implantkaid.or.kr/"blank_">Admin</a></li>
      <?php }  ?>
      <?php } else {  ?>
      <li><a href="<?php echo G5_BBS_URL ?>/register.php">Join</a></li>
      <li><a href="<?php echo G5_BBS_URL ?>/login.php" class="login_btn">Login</a></li>
      <?php }  ?>
    </ul>
  </div>
  <div class="width_m">
    <div class="head_menu" id="mild_head">
      <div class="logo">
        <a href="<?php echo G5_URL ?>"><img src="<?php echo G5_THEME_IMG_URL ?>/logo.png" alt=""></a>
      </div>
          <ul class="main_menu" id="mild_menu">
    <?php foreach($topmenu as $tmenu => $url): ?>
    <li class="dept1">
        <a href="<?=$url?>" target="<?=$topmenu_target[$tmenu]?>" class="<?=$tmenu == $tNum ? "menuon" : "";?>"><?=$tmenu?></a>

        <?php if (!empty($submenu[$tmenu])): ?>
        <ul class="sub_menu">
            <?php foreach($submenu[$tmenu] as $smenu => $surl): ?>
            <li class="dept2">
                <a href="<?=$surl?>" target="<?=$submenu_target[$tmenu][$smenu]?>" class="<?=($tmenu == $tNum && $smenu == $sNum) ? "menuon" : "";?>"><?=$smenu?></a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

    </li>
    <?php endforeach; ?>
</ul>
    
  <!-- 번역 -->
  <div class="dropdown">
  <button id="dropdownButton" onclick="toggleDropdown()" class="dropdown-btn">EN</button>
  <div id="dropdownMenu" class="dropdown-content">
      <a href="https://kaidimplant.or.kr/?theme=nero" onclick="selectOption('KO')">KO</a>
    <a href="https://kaidimplant.or.kr/?theme=nero_eng" onclick="selectOption('EN')">EN</a>

  </div>
</div>
  <!-- //번역 -->


    </div>
  </div>
  <div class="menu_bg"></div>

</nav>

<script>
  function toggleDropdown() {
    document.getElementById("dropdownMenu").classList.toggle("show");
  }

  function selectOption(optionText) {
    document.getElementById("dropdownButton").innerText = optionText ;
    document.getElementById("dropdownMenu").classList.remove("show");
  }

  // 외부 클릭 시 드롭다운 닫기
  window.onclick = function(e) {
    if (!e.target.matches('.dropdown-btn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      for (var i = 0; i < dropdowns.length; i++) {
        dropdowns[i].classList.remove('show');
      }
    }
  }
</script>

<!-- 모바일 메뉴 -->
<div class="mobile_head">
  <div class="mobile_logo">
    <a href="<?php echo G5_URL ?>/index.php"><img src="<?php echo G5_THEME_URL ?>/img/logo.png" alt=""></a>
  </div>
  <div class="mobile_btn">
    <a href="javascript:" class="mobile_open">
      <i data-feather="menu"></i>
    </a>
  </div>
</div>
<div class="mob_bg"></div>

<div id="mobile_menu" class="mobile_menu">

  <div class="mob_logo">
    <a href="<?php echo G5_URL ?>/index.php"><img src="<?php echo G5_THEME_URL ?>/img/logo.png" alt="" ></a>
    <div class="m_lang_wrap">
      <span class="mobile_close"><i data-feather="x"></i></span>
    </div>
  </div>

<div class="mob_menu">
    <ul class="mob_menu_btn">
      <?php if ($is_member) {  ?>
      <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_5.php">마이페이지</a></li>
      <li><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
      <?php if ($is_admin) {  ?>
      <li><a href="<?php echo G5_ADMIN_URL ?>"><i class="fa-solid fa-gear"></i>관리자</a></li>
      <?php }  ?>
      <?php } else {  ?>
      <li><a href="<?php echo G5_BBS_URL ?>/register.php"><i class="fa-solid fa-user-plus"></i>회원가입</a></li>
      <li><a href="<?php echo G5_BBS_URL ?>/login.php"><i class="fa-solid fa-user"></i>로그인</a></li>
      <?php }  ?>
    </ul>
    <?php
    foreach($topmenu as $tmenu=>$url){
    ?>
    <a href="#"  target="<?=$topmenu_target[$tmenu]?>" class="top_menu" class="<?=$tmenu==$tNum?"active":"";?>"><?=$tmenu?><i class="fa-solid fa-angle-down"></i></a>
    <ul class="sub_menu">
    <?php
    	foreach($submenu[$tmenu] as $smenu=>$surl){
    ?>
	  <li><a href="<?=$surl?>" target="<?=$submenu_target[$tmenu][$smenu]?>" class="<?=$smenu==$sNum?"active":"";?>"><?=$smenu?></a></li>
    <?php
    	}
    ?>
    </ul>
    <?php
    }
    ?>
  </div>
<div class="mob_lang_menu">
    <img src="<?php echo G5_THEME_IMG_URL ?>/global.png" alt="">
    <div>
        <a href="https://kaidimplant.or.kr/?theme=nero" onclick="selectOption('KO')">KO</a>
    <a href="https://kaidimplant.or.kr/?theme=nero_eng"  class="on_lang"  onclick="selectOption('EN')">EN</a>
    </div>
  </div>
</div>
<div class="mob_bg"></div>




<!-- 서브메뉴 -->

<?php
if (!defined("_INDEX_") && !defined("_NOVISUAL_")){ ?>
  <div class="sub_visual" style="background:url('/theme/nero/img/subvisual.png')">
    <div class="width">
 <div class="lnb_wrap">
  <?php include_once(G5_THEME_PATH.'/mildmap.php'); ?>
</div>
    <div class="sub_top_text">
      <h3><?php echo $tNum ?></h3>
    </div>
     <div class="lnb_wrap">
  <?php include_once(G5_THEME_PATH.'/mildmap2.php'); ?>
</div>
 

</div>
</div>
<?php }
?>

