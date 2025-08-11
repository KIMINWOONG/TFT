<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

$mb = array(
    'mb_certify' => null,
    'mb_adult' => null,
    'mb_sms' => null,
    'mb_intercept_date' => null,
    'mb_id' => null,
    'mb_name' => null,
    'mb_nick' => null,
    'mb_point' => null,
    'mb_email' => null,
    'mb_homepage' => null,
    'mb_hp' => null,
    'mb_tel' => null,
    'mb_zip1' => null,
    'mb_zip2' => null,
    'mb_addr1' => null,
    'mb_addr2' => null,
    'mb_addr3' => null,
    'mb_addr_jibeon' => null,
    'mb_signature' => null,
    'mb_profile' => null,
    'mb_memo' => null,
    'mb_leave_date' => null,
    'mb_1' => null,
    'mb_2' => null,
    'mb_3' => null,
    'mb_4' => null,
    'mb_5' => null,
    'mb_6' => null,
    'mb_7' => null,
    'mb_8' => null,
    'mb_9' => null,
    'mb_10' => null,
);

$sound_only = '';
$required_mb_id = '';
$required_mb_id_class = '';
$required_mb_password = '';
$html_title = '';

if ($w == '') {
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';
} elseif ($w == 'u') {
    $mb = get_member($mb_id);
    if (!$mb['mb_id']) {
        alert('존재하지 않는 회원자료입니다.');
    }

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');
    }

    $required_mb_id = 'readonly';
    $html_title = '수정';

    $mb['mb_name'] = get_text($mb['mb_name']);
    $mb['mb_nick'] = get_text($mb['mb_nick']);
    $mb['mb_email'] = get_text($mb['mb_email']);
    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_addr3'] = get_text($mb['mb_addr3']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
    $mb['mb_1'] = get_text($mb['mb_1']);
    $mb['mb_2'] = get_text($mb['mb_2']);
    $mb['mb_3'] = get_text($mb['mb_3']);
    $mb['mb_4'] = get_text($mb['mb_4']);
    $mb['mb_5'] = get_text($mb['mb_5']);
    $mb['mb_6'] = get_text($mb['mb_6']);
    $mb['mb_7'] = get_text($mb['mb_7']);
    $mb['mb_8'] = get_text($mb['mb_8']);
    $mb['mb_9'] = get_text($mb['mb_9']);
    $mb['mb_10'] = get_text($mb['mb_10']);
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

// 본인확인방법
switch ($mb['mb_certify']) {
    case 'simple':
        $mb_certify_case = '간편인증';
        $mb_certify_val = 'simple';
        break;
    case 'hp':
        $mb_certify_case = '휴대폰';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// SMS 수신
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

if (isset($mb['mb_certify'])) {
    // 날짜시간형이라면 drop 시킴
    if (preg_match("/-/", $mb['mb_certify'])) {
        sql_query(" ALTER TABLE `{$g5['member_table']}` DROP `mb_certify` ", false);
    }
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_certify` TINYINT(4) NOT NULL DEFAULT '0' AFTER `mb_hp` ", false);
}

if (isset($mb['mb_adult'])) {
    sql_query(" ALTER TABLE `{$g5['member_table']}` CHANGE `mb_adult` `mb_adult` TINYINT(4) NOT NULL DEFAULT '0' ", false);
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_adult` TINYINT NOT NULL DEFAULT '0' AFTER `mb_certify` ", false);
}

// 지번주소 필드추가
if (!isset($mb['mb_addr_jibeon'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr_jibeon` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 건물명필드추가
if (!isset($mb['mb_addr3'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr3` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 중복가입 확인필드 추가
if (!isset($mb['mb_dupinfo'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_dupinfo` varchar(255) NOT NULL DEFAULT '' AFTER `mb_adult` ", false);
}

// 이메일인증 체크 필드추가
if (!isset($mb['mb_email_certify2'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_email_certify2` varchar(255) NOT NULL DEFAULT '' AFTER `mb_email_certify` ", false);
}

// 본인인증 내역 테이블 정보가 dbconfig에 없으면 소셜 테이블 정의
if (!isset($g5['member_cert_history'])) {
    $g5['member_cert_history_table'] = G5_TABLE_PREFIX . 'member_cert_history';
}
// 멤버 본인인증 정보 변경 내역 테이블 없을 경우 생성
if (isset($g5['member_cert_history_table']) && !sql_query(" DESC {$g5['member_cert_history_table']} ", false)) {
    sql_query(
        " CREATE TABLE IF NOT EXISTS `{$g5['member_cert_history_table']}` (
                    `ch_id` int(11) NOT NULL auto_increment,
                    `mb_id` varchar(20) NOT NULL DEFAULT '',
                    `ch_name` varchar(255) NOT NULL DEFAULT '',
                    `ch_hp` varchar(255) NOT NULL DEFAULT '',
                    `ch_birth` varchar(255) NOT NULL DEFAULT '',
                    `ch_type` varchar(20) NOT NULL DEFAULT '',
                    `ch_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
                    PRIMARY KEY (`ch_id`),
                    KEY `mb_id` (`mb_id`)
                ) ",
        true
    );
}

$mb_cert_history = '';
if (isset($mb_id) && $mb_id) {
    $sql = "select * from {$g5['member_cert_history_table']} where mb_id = '{$mb_id}' order by ch_id asc";
    $mb_cert_history = sql_query($sql);
}

if ($mb['mb_intercept_date']) {
    $g5['title'] = "차단된 ";
} else {
    $g5['title'] .= "";
}
$g5['title'] .= '회원 ' . $html_title;
require_once './admin.head.php';

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>

<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?></caption>
            <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label for="mb_id">아이디<?php echo $sound_only ?></label></th>
                    <td>
                        <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15" maxlength="20">
                        <?php if ($w == 'u') { ?><a href="./boardgroupmember_form.php?mb_id=<?php echo $mb['mb_id'] ?>" class="btn_frmline">접근가능그룹보기</a><?php } ?>
                    </td>
                    <th scope="row"><label for="mb_password">비밀번호<?php echo $sound_only ?></label></th>
                    <td>
                        <div>
                        <input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20">
                        </div>
                        <div id="mb_password_captcha_wrap" style="display:none">
                            <?php
                            require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
                            $captcha_html = captcha_html();
                            $captcha_js   = chk_captcha_js();
                            echo $captcha_html;
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_name">이름(실명)<strong class="sound_only">필수</strong></label></th>
                    <td><input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required frm_input" size="15" maxlength="20"></td>
                    <th scope="row"><label for="mb_nick">닉네임<strong class="sound_only">필수</strong></label></th>
                    <td><input type="text" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>" id="mb_nick" required class="required frm_input" size="15" maxlength="20"></td>
                </tr>
				<?php
				$mb_1_arr=explode(",", $mb['mb_1']);
				?>
                <tr>
                    <th scope="row"><label for="mb_level">회원 권한</label></th>
                    <td><?php echo get_member_level_select('mb_level', 1, $member['mb_level'], $mb['mb_level']) ?></td>
                    <th scope="row">담당 카테고리</th>
                    <td>
						<input type="checkbox" name="mb_1[]" value="회원가입 및 자격" <?=in_array("회원가입 및 자격",$mb_1_arr)?"checked":"";?>> 회원가입 및 자격 &nbsp;&nbsp;
						<input type="checkbox" name="mb_1[]" value="진료 및 상담" <?=in_array("진료 및 상담",$mb_1_arr)?"checked":"";?>> 진료 및 상담 &nbsp;&nbsp;
						<input type="checkbox" name="mb_1[]" value="학술대회 및 집담회" <?=in_array("학술대회 및 집담회",$mb_1_arr)?"checked":"";?>> 학술대회 및 집담회 &nbsp;&nbsp;
						<input type="checkbox" name="mb_1[]" value="기타 문의" <?=in_array("기타 문의",$mb_1_arr)?"checked":"";?>> 기타 문의
                    </td>
                </tr>
                 <tr>
                    <th scope="row">포인트</th>
                    <td><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $mb['mb_id'] ?>" target="_blank"><?php echo number_format($mb['mb_point']) ?></a> 점</td>
                </tr>               <tr>
                    <th scope="row"><label for="mb_email">E-mail<strong class="sound_only">필수</strong></label></th>
                    <td><input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="required frm_input email" size="30"></td>
                    <th scope="row"><label for="mb_homepage">홈페이지</label></th>
                    <td><input type="text" name="mb_homepage" value="<?php echo $mb['mb_homepage'] ?>" id="mb_homepage" class="frm_input" maxlength="255" size="15"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_hp">휴대폰번호</label></th>
                    <td><input type="text" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" class="frm_input" size="15" maxlength="20"></td>
                    <th scope="row"><label for="mb_tel">전화번호</label></th>
                    <td><input type="text" name="mb_tel" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" class="frm_input" size="15" maxlength="20"></td>
                </tr>
                <tr>
                    <th scope="row">본인확인방법</th>
                    <td colspan="3">
                        <input type="radio" name="mb_certify_case" value="simple" id="mb_certify_sa" <?php if ($mb['mb_certify'] == 'simple') { echo 'checked="checked"'; } ?>>
                        <label for="mb_certify_sa">간편인증</label>
                        <input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp" <?php if ($mb['mb_certify'] == 'hp') { echo 'checked="checked"'; } ?>>
                        <label for="mb_certify_hp">휴대폰</label>
                        <input type="radio" name="mb_certify_case" value="ipin" id="mb_certify_ipin" <?php if ($mb['mb_certify'] == 'ipin') { echo 'checked="checked"'; } ?>>
                        <label for="mb_certify_ipin">아이핀</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">본인확인</th>
                    <td>
                        <input type="radio" name="mb_certify" value="1" id="mb_certify_yes" <?php echo $mb_certify_yes; ?>>
                        <label for="mb_certify_yes">예</label>
                        <input type="radio" name="mb_certify" value="0" id="mb_certify_no" <?php echo $mb_certify_no; ?>>
                        <label for="mb_certify_no">아니오</label>
                    </td>
                    <th scope="row">성인인증</th>
                    <td>
                        <input type="radio" name="mb_adult" value="1" id="mb_adult_yes" <?php echo $mb_adult_yes; ?>>
                        <label for="mb_adult_yes">예</label>
                        <input type="radio" name="mb_adult" value="0" id="mb_adult_no" <?php echo $mb_adult_no; ?>>
                        <label for="mb_adult_no">아니오</label>
                    </td>
                </tr>

               <tr>
                    <th scope="row">회원구분</th>
                    <td>
                        <input type="radio" name="mb_memclass" value="member" <?php echo ($mb['mb_memclass']=='member')?'checked':''; ?>>
                        <label>Member</label>
                        <input type="radio" name="mb_memclass" value="student" <?php echo ($mb['mb_memclass']=='student')?'checked':''; ?>>
                        <label>Student</label>
                    </td>
                    <th scope="row">호칭</th>
                    <td>
                        <input type="radio" name="mb_title" value="dr" <?php echo ($mb['mb_title']=='dr')?'checked':''; ?>>
                        <label>Dr.</label>
                        <input type="radio" name="mb_title" value="prof" <?php echo ($mb['mb_title']=='prof')?'checked':''; ?>>
                        <label>Prof.</label>
                        <input type="radio" name="mb_title" value="ms" <?php echo ($mb['mb_title']=='ms')?'checked':''; ?>>
                        <label>Ms.</label>
                        <input type="radio" name="mb_title" value="mr" <?php echo ($mb['mb_title']=='mr')?'checked':''; ?>>
                        <label>Mr.</label>
                    </td>
                </tr>
               <tr>
                    <th scope="row">구분</th>
                    <td colspan=3>
						<select name="mb_gubun" class="frm_input">
							<option value="" <?php echo ($mb['mb_gubun']=='')?'selected':''; ?>>비고</option>
							<option value="우수 전문의" <?php echo ($mb['mb_gubun']=='우수 전문의')?'selected':''; ?>>우수임플란트임상의</option>
							<option value="인정의" <?php echo ($mb['mb_gubun']=='인정의')?'selected':''; ?>>인정의</option>
							<option value="우수 전문의/인정의" <?php echo ($mb['mb_gubun']=='우수 전문의')?'selected':''; ?>>우수임플란트임상의/인정의</option>
						</select>
                    </td>
                </tr>

				<?php
				if($w==""){
					$member_job_area_class="none";
					$student_job_area_class="none";
				}else{
					if($mb['mb_memclass']=="member"){
						$member_job_area_class="block";
						$student_job_area_class="none";
					}else{
						$member_job_area_class="none";
						$student_job_area_class="block";
					}
				}
				?>

                <tr>
                    <th scope="row">영문성명</th>
                    <td>
                        <input type="text" name="mb_name_en" value="<?php echo get_text($mb['mb_name_en']) ?>" class="frm_input" size="30" maxlength="50">
                    </td>
                    <th scope="row">직군</th>
                    <td>
						<div class="input_inner sel_mem" id="member_job_area" style="display:<?=$member_job_area_class?>;">
                            <input type="radio" name="mb_job_class" value="치과의사" <?php echo ($mb['mb_job_class']=='치과의사')?'checked':''; ?>>
                            <label>치과의사</label>
                            <input type="radio" name="mb_job_class" value="기공사" <?php echo ($mb['mb_job_class']=='기공사')?'checked':''; ?>>
                            <label>기공사</label>
                            <input type="radio" name="mb_job_class" value="간호사" <?php echo ($mb['mb_job_class']=='간호사')?'checked':''; ?>>
                            <label>간호사</label>
						</div>
						<div class="input_inner sel_stu" id="student_job_area" style="display:<?=$student_job_area_class?>;">
                            <input type="radio" name="mb_student_class" value="학생" <?php echo ($mb['mb_student_class']=='학생')?'checked':''; ?>>
                            <label>학생</label>
                            <input type="radio" name="mb_student_class" value="전공의" <?php echo ($mb['mb_student_class']=='전공의')?'checked':''; ?>>
                            <label>전공의</label>
						</div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">생년월일</th>
                    <td>
                        <input type="text" name="mb_birth" value="<?php echo str_replace("-","",$mb['mb_birth']) ?>" class="frm_input" maxlength=8>
                    </td>
                    <th scope="row">성별</th>
                    <td>
                        <input type="radio" name="mb_sex" value="남자" <?php echo ($mb['mb_sex']=='남자')?'checked':''; ?>>
                        <label>남자</label>
                        <input type="radio" name="mb_sex" value="여자" <?php echo ($mb['mb_sex']=='여자')?'checked':''; ?>>
                        <label>여자</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">면허번호</th>
                    <td>
                        <input type="text" name="mb_license_no" value="<?php echo get_text($mb['mb_license_no']) ?>" class="frm_input" maxlength="50">
                        <input type="checkbox" name="mb_license_none" value="1" <?php echo ($mb['mb_license_none'])?'checked':''; ?>>
                        <label>면허번호 없음</label>
                    </td>
                    <th scope="row">휴대전화 국가코드</th>
                    <td>
                        <select name="mb_hp_country">
                            <option value="+82" <?php echo ($mb['mb_hp_country']=='+82')?'selected':''; ?>>대한민국 (+82)</option>
                            <option value="+1" <?php echo ($mb['mb_hp_country']=='+1')?'selected':''; ?>>미국 (+1)</option>
                            <option value="+81" <?php echo ($mb['mb_hp_country']=='+81')?'selected':''; ?>>일본 (+81)</option>
                            <option value="+86" <?php echo ($mb['mb_hp_country']=='+86')?'selected':''; ?>>중국 (+86)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">재직증명서</th>
                    <td colspan="3">
                        <?php for ($i = 1; $i <= 3; $i++) { ?>
                            <div style="margin-bottom: 5px;">
                                파일 <?php echo $i ?>: 
                                <input type="file" name="mb_certi_file<?php echo $i ?>" accept=".jpg,.jpeg,.png,.pdf">
                                <?php if ($mb["mb_certi_file{$i}"]) { ?>
                                    <span>현재 파일: <?php echo basename($mb["mb_certi_file{$i}"]); ?></span>
                                    <input type="checkbox" name="mb_certi_file<?php echo $i ?>_del" value="1"> 삭제
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <small>5MB 이하의 jpg, png, PDF 파일만 업로드 가능</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row">출신학교(학사)</th>
                    <td>
	      				<select name="mb_location_type" id="mb_location_type" class="custom-select" required>
                            <option value="" disabled selected hidden>선택</option>
                            <option value="domestic" <?=$mb['mb_location_type']=="domestic"?"selected":"";?>>국내</option>
                            <option value="international" <?=$mb['mb_location_type']=="international"?"selected":"";?>>국외</option>
                        </select>
					</td>
				</tr>
                <tr>
                    <th scope="row">국내학교(학사)</th>
                    <td>
                        <select name="mb_school" class="frm_input">
                            <option value="">선택</option>
                            <option value="강릉원주대학교 치과대학" <?php echo ($mb['mb_school']=='강릉원주대학교 치과대학')?'selected':''; ?>>강릉원주대학교 치과대학</option>
                            <option value="경북대학교 치과대학/치의학전문대학원" <?php echo ($mb['mb_school']=='경북대학교 치과대학/치의학전문대학원')?'selected':''; ?>>경북대학교 치과대학/치의학전문대학원</option>
                            <option value="경희대학교 치과대학/치의학전문대학원" <?php echo ($mb['mb_school']=='경희대학교 치과대학/치의학전문대학원')?'selected':''; ?>>경희대학교 치과대학/치의학전문대학원</option>
                            <option value="단국대학교 치과대학" <?php echo ($mb['mb_school']=='단국대학교 치과대학')?'selected':''; ?>>단국대학교 치과대학</option>
                            <option value="부산대학교 치과대학/치의학전문대학원" <?php echo ($mb['mb_school']=='부산대학교 치과대학/치의학전문대학원')?'selected':''; ?>>부산대학교 치과대학/치의학전문대학원</option>
                            <option value="서울대학교 치과대학/치의학대학원" <?php echo ($mb['mb_school']=='서울대학교 치과대학/치의학대학원')?'selected':''; ?>>서울대학교 치과대학/치의학대학원</option>
                            <option value="연세대학교 치과대학/치의학전문대학원" <?php echo ($mb['mb_school']=='연세대학교 치과대학/치의학전문대학원')?'selected':''; ?>>연세대학교 치과대학/치의학전문대학원</option>
                            <option value="원광대학교 치과대학" <?php echo ($mb['mb_school']=='원광대학교 치과대학')?'selected':''; ?>>원광대학교 치과대학</option>
                            <option value="전남대학교 치과대학/치의학전문대학원" <?php echo ($mb['mb_school']=='전남대학교 치과대학/치의학전문대학원')?'selected':''; ?>>전남대학교 치과대학/치의학전문대학원</option>
                            <option value="전북대학교 치과대학/치의학전문대학원" <?php echo ($mb['mb_school']=='전북대학교 치과대학/치의학전문대학원')?'selected':''; ?>>전북대학교 치과대학/치의학전문대학원</option>
                            <option value="조선대학교 치과대학/치의학전문대학원" <?php echo ($mb['mb_school']=='조선대학교 치과대학/치의학전문대학원')?'selected':''; ?>>조선대학교 치과대학/치의학전문대학원</option>
							<option value="기타" <?php echo ($mb['mb_school']=='기타')?'selected':''; ?>>기타</option>
                        </select>
                        <input type="text" name="mb_school_domestic_etc" id="mb_school_domestic_etc" value="<?php echo get_text($mb['mb_school_domestic_etc']) ?>" placeholder="기타" class="frm_input">
                    </td>
                    <th scope="row">국외학교(학사)</th>
                    <td>
	      				<select name="mb_school_etc" class="custom-select width_100" id="mb_school_etc" >
                            <option value="">선택</option>
							<option value="University of Michigan-Ann Arbor" <?php echo ($mb['mb_school_etc']=='University of Michigan-Ann Arbor')?'selected':''; ?>>University of Michigan-Ann Arbor</option>
							<option value="Academic Centre for Dentistry Amsterdam (ACTA)" <?php echo ($mb['mb_school_etc']=='Academic Centre for Dentistry Amsterdam (ACTA)')?'selected':''; ?>>Academic Centre for Dentistry Amsterdam (ACTA)</option>
							<option value="The University of Hong Kong" <?php echo ($mb['mb_school_etc']=='The University of Hong Kong')?'selected':''; ?>>The University of Hong Kong</option>
							<option value="King's College London" <?php echo ($mb['mb_school_etc']=='King\'s College London')?'selected':''; ?>>King's College London</option>
							<option value="Tokyo Medical and Dental University" <?php echo ($mb['mb_school_etc']=='Tokyo Medical and Dental University')?'selected':''; ?>>Tokyo Medical and Dental University</option>
							<option value="University of Gothenburg" <?php echo ($mb['mb_school_etc']=='University of Gothenburg')?'selected':''; ?>>University of Gothenburg</option>
							<option value="Harvard University" <?php echo ($mb['mb_school_etc']=='Harvard University')?'selected':''; ?>>Harvard University</option>
							<option value="University of Bern" <?php echo ($mb['mb_school_etc']=='University of Bern')?'selected':''; ?>>University of Bern</option>
							<option value="University of São Paulo" <?php echo ($mb['mb_school_etc']=='University of São Paulo')?'selected':''; ?>>University of São Paulo</option>
							<option value="University of British Columbia" <?php echo ($mb['mb_school_etc']=='University of British Columbia')?'selected':''; ?>>University of British Columbia</option>
							<option value="University of North Carolina, Chapel Hill" <?php echo ($mb['mb_school_etc']=='University of North Carolina, Chapel Hill')?'selected':''; ?>>University of North Carolina, Chapel Hill</option>
							<option value="University of Zurich" <?php echo ($mb['mb_school_etc']=='University of Zurich')?'selected':''; ?>>University of Zurich</option>
							<option value="University of Oslo" <?php echo ($mb['mb_school_etc']=='University of Oslo')?'selected':''; ?>>University of Oslo</option>
							<option value="University of Otago" <?php echo ($mb['mb_school_etc']=='University of Otago')?'selected':''; ?>>University of Otago</option>
							<option value="University of Copenhagen" <?php echo ($mb['mb_school_etc']=='University of Copenhagen')?'selected':''; ?>>University of Copenhagen</option>
							<option value="University of California, San Francisco" <?php echo ($mb['mb_school_etc']=='University of California, San Francisco')?'selected':''; ?>>University of California, San Francisco</option>
							<option value="University of Leeds" <?php echo ($mb['mb_school_etc']=='University of Leeds')?'selected':''; ?>>University of Leeds</option>
							<option value="University of Birmingham" <?php echo ($mb['mb_school_etc']=='University of Birmingham')?'selected':''; ?>>University of Birmingham</option>
							<option value="University of Minnesota" <?php echo ($mb['mb_school_etc']=='University of Minnesota')?'selected':''; ?>>University of Minnesota</option>
							<option value="University of Basel" <?php echo ($mb['mb_school_etc']=='University of Basel')?'selected':''; ?>>University of Basel</option>
							<option value="University of Glasgow" <?php echo ($mb['mb_school_etc']=='University of Glasgow')?'selected':''; ?>>University of Glasgow</option>
							<option value="Seoul National University" <?php echo ($mb['mb_school_etc']=='Seoul National University')?'selected':''; ?>>Seoul National University</option>
							<option value="University of Alberta" <?php echo ($mb['mb_school_etc']=='University of Alberta')?'selected':''; ?>>University of Alberta</option>
							<option value="University of Melbourne" <?php echo ($mb['mb_school_etc']=='University of Melbourne')?'selected':''; ?>>University of Melbourne</option>
							<option value="Universidade Estadual de Campinas (Unicamp)" <?php echo ($mb['mb_school_etc']=='Universidade Estadual de Campinas (Unicamp)')?'selected':''; ?>>Universidade Estadual de Campinas (Unicamp)</option>
							<option value="University of Dundee" <?php echo ($mb['mb_school_etc']=='University of Dundee')?'selected':''; ?>>University of Dundee</option>
							<option value="McGill University" <?php echo ($mb['mb_school_etc']=='McGill University')?'selected':''; ?>>McGill University</option>
							<option value="University of Sydney" <?php echo ($mb['mb_school_etc']=='University of Sydney')?'selected':''; ?>>University of Sydney</option>
							<option value="University of Adelaide" <?php echo ($mb['mb_school_etc']=='University of Adelaide')?'selected':''; ?>>University of Adelaide</option>
							<option value="University of Iowa" <?php echo ($mb['mb_school_etc']=='University of Iowa')?'selected':''; ?>>University of Iowa</option>
							<option value="University of Manchester" <?php echo ($mb['mb_school_etc']=='University of Manchester')?'selected':''; ?>>University of Manchester</option>
							<option value="University of Helsinki" <?php echo ($mb['mb_school_etc']=='University of Helsinki')?'selected':''; ?>>University of Helsinki</option>
							<option value="University of Sheffield" <?php echo ($mb['mb_school_etc']=='University of Sheffield')?'selected':''; ?>>University of Sheffield</option>
							<option value="University of Milan" <?php echo ($mb['mb_school_etc']=='University of Milan')?'selected':''; ?>>University of Milan</option>
							<option value="University of Pennsylvania" <?php echo ($mb['mb_school_etc']=='University of Pennsylvania')?'selected':''; ?>>University of Pennsylvania</option>
							<option value="University of Lisbon" <?php echo ($mb['mb_school_etc']=='University of Lisbon')?'selected':''; ?>>University of Lisbon</option>
							<option value="University of Florida" <?php echo ($mb['mb_school_etc']=='University of Florida')?'selected':''; ?>>University of Florida</option>
							<option value="University of Geneva" <?php echo ($mb['mb_school_etc']=='University of Geneva')?'selected':''; ?>>University of Geneva</option>
							<option value="University of Toronto" <?php echo ($mb['mb_school_etc']=='University of Toronto')?'selected':''; ?>>University of Toronto</option>
							<option value="Universidad Complutense de Madrid" <?php echo ($mb['mb_school_etc']=='Universidad Complutense de Madrid')?'selected':''; ?>>Universidad Complutense de Madrid</option>
							<option value="New York University (NYU)" <?php echo ($mb['mb_school_etc']=='New York University (NYU)')?'selected':''; ?>>New York University (NYU)</option>
                            <option value="기타" <?php echo ($mb['mb_school_etc']=='기타')?'selected':''; ?>>etc</option>
						</select>
                        <input type="text" name="mb_school_foreign_etc" id="mb_school_foreign_etc" value="<?php echo get_text($mb['mb_school_foreign_etc']) ?>" placeholder="기타" class="frm_input">
					</td>
				</tr>

                <tr>
                    <th scope="row">전공과목</th>
                    <td>
                        <select name="mb_major" class="frm_input">
                            <option value="">선택</option>
                            <option value="치과보철학" <?php echo ($mb['mb_major']=='치과보철학')?'selected':''; ?>>치과보철학</option>
                            <option value="소아치과학" <?php echo ($mb['mb_major']=='소아치과학')?'selected':''; ?>>소아치과학</option>
                            <option value="치과교정학" <?php echo ($mb['mb_major']=='치과교정학')?'selected':''; ?>>치과교정학</option>
                            <option value="치과재료학" <?php echo ($mb['mb_major']=='치과재료학')?'selected':''; ?>>치과재료학</option>
                            <option value="구강생물학" <?php echo ($mb['mb_major']=='구강생물학')?'selected':''; ?>>구강생물학</option>
                            <option value="영상치의학" <?php echo ($mb['mb_major']=='영상치의학')?'selected':''; ?>>영상치의학</option>
                            <option value="구강내과학" <?php echo ($mb['mb_major']=='구강내과학')?'selected':''; ?>>구강내과학</option>
                            <option value="구강병리학" <?php echo ($mb['mb_major']=='구강병리학')?'selected':''; ?>>구강병리학</option>
                            <option value="치주과학" <?php echo ($mb['mb_major']=='치주과학')?'selected':''; ?>>치주과학</option>
                            <option value="구강보건학" <?php echo ($mb['mb_major']=='구강보건학')?'selected':''; ?>>구강보건학</option>
                            <option value="구강악안면외과학" <?php echo ($mb['mb_major']=='구강악안면외과학')?'selected':''; ?>>구강악안면외과학</option>
                            <option value="치과보존학" <?php echo ($mb['mb_major']=='치과보존학')?'selected':''; ?>>치과보존학</option>
                            <option value="통합치의학" <?php echo ($mb['mb_major']=='통합치의학')?'selected':''; ?>>통합치의학</option>
                            <option value="전공분야없음" <?php echo ($mb['mb_major']=='전공분야없음')?'selected':''; ?>>전공분야없음</option>
                            <option value="기타(전공)" <?php echo ($mb['mb_major']=='기타(전공)')?'selected':''; ?>>기타(전공)</option>
                        </select>
                        <input type="text" name="mb_major_etc" value="<?php echo get_text($mb['mb_major_etc']) ?>" placeholder="기타 전공 입력" class="frm_input" style="margin-left: 5px;">
                    </td>
                    <th scope="row">분과</th>
                    <td>
                        <select name="mb_branch" class="frm_input">
                            <option value="">선택</option>
                            <option value="구강외과 분과" <?php echo ($mb['mb_branch']=='구강외과 분과')?'selected':''; ?>>구강외과 분과</option>
                            <option value="보철 분과" <?php echo ($mb['mb_branch']=='보철 분과')?'selected':''; ?>>보철 분과</option>
                            <option value="치주 분과" <?php echo ($mb['mb_branch']=='치주 분과')?'selected':''; ?>>치주 분과</option>
                            <option value="연구 분과" <?php echo ($mb['mb_branch']=='연구 분과')?'selected':''; ?>>연구 분과</option>
                            <option value="영상 및 AI 분과" <?php echo ($mb['mb_branch']=='영상 및 AI 분과')?'selected':''; ?>>영상 및 AI 분과</option>
                            <option value="통합치의학 및 장애인치과 분과" <?php echo ($mb['mb_branch']=='통합치의학 및 장애인치과 분과')?'selected':''; ?>>통합치의학 및 장애인치과 분과</option>
                        </select>
                    </td>

                </tr>
                <tr>
                    <th scope="row">입학/졸업년도(학사)</th>
                    <td colspan="3">
                        입학년도: 
                        <select name="mb_bachelor_admission_year" class="frm_input">
                            <option value="">선택</option>
                            <?php for($year = 1980; $year <= date('Y'); $year++): ?>
                                <option value="<?=$year?>" <?php echo ($mb['mb_bachelor_admission_year']==$year)?'selected':''; ?>><?=$year?></option>
                            <?php endfor; ?>
                        </select>
                        &nbsp;&nbsp;졸업년도: 
                        <select name="mb_bachelor_graduation_year" class="frm_input">
                            <option value="">선택</option>
                            <?php for($year = 1984; $year <= date('Y')+6; $year++): ?>
                                <option value="<?=$year?>" <?php echo ($mb['mb_bachelor_graduation_year']==$year)?'selected':''; ?>><?=$year?></option>
                            <?php endfor; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">석/박사 졸업</th>
                    <td colspan="3">
                        <div style="border: 1px solid #ddd; padding: 10px; margin: 5px 0;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #f5f5f5;">
                                        <th style="border: 1px solid #ddd; padding: 5px;">석/박사</th>
                                        <th style="border: 1px solid #ddd; padding: 5px;">학교 및 전공</th>
                                        <th style="border: 1px solid #ddd; padding: 5px;">입학년도</th>
                                        <th style="border: 1px solid #ddd; padding: 5px;">졸업년도</th>
                                        <th style="border: 1px solid #ddd; padding: 5px;">관리</th>
                                    </tr>
                                </thead>
                                <tbody id="graduate_tbody">
                                    <?php 
                                    $existing_graduate_data = array();
                                    if (isset($mb['mb_graduate_data']) && $mb['mb_graduate_data']) {
                                        $existing_graduate_data = json_decode($mb['mb_graduate_data'], true);
                                    }
                                    
                                    if (empty($existing_graduate_data)) {
                                        $existing_graduate_data = array(
                                            array('degree' => '', 'school_major' => '', 'admission_year' => '', 'graduation_year' => '')
                                        );
                                    }
                                    
                                    foreach ($existing_graduate_data as $index => $data) {
                                    ?>
                                    <tr>
                                        <td style="border: 1px solid #ddd; padding: 3px; text-align: center;">
                                            <select name="graduate_degree[]" style="width: 80px;">
                                                <option value="">선택</option>
                                                <option value="석사" <?php echo ($data['degree']=='석사')?'selected':''; ?>>석사</option>
                                                <option value="박사" <?php echo ($data['degree']=='박사')?'selected':''; ?>>박사</option>
                                            </select>
                                        </td>
                                        <td style="border: 1px solid #ddd; padding: 3px;">
                                            <input type="text" name="graduate_school_major[]" value="<?php echo htmlspecialchars($data['school_major']); ?>" style="width: 200px;" placeholder="예) 하버대학교 치아보철과">
                                        </td>
                                        <td style="border: 1px solid #ddd; padding: 3px; text-align: center;">
                                            <select name="graduate_admission_year[]" style="width: 80px;">
                                                <option value="">선택</option>
                                                <?php for($year = 1980; $year <= date('Y'); $year++): ?>
                                                    <option value="<?=$year?>" <?php echo ($data['admission_year']==$year)?'selected':''; ?>><?=$year?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                        <td style="border: 1px solid #ddd; padding: 3px; text-align: center;">
                                            <select name="graduate_graduation_year[]" style="width: 80px;">
                                                <option value="">선택</option>
                                                <?php for($year = 1984; $year <= date('Y')+6; $year++): ?>
                                                    <option value="<?=$year?>" <?php echo ($data['graduation_year']==$year)?'selected':''; ?>><?=$year?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                        <td style="border: 1px solid #ddd; padding: 3px; text-align: center;">
                                            <?php if ($index == 0) { ?>
                                                <button type="button" onclick="addGraduateRow()" style="background: #007bff; color: white; border: none; padding: 3px 8px; cursor: pointer;">추가</button>
                                            <?php } else { ?>
                                                <button type="button" onclick="removeGraduateRow(this)" style="background: #dc3545; color: white; border: none; padding: 3px 8px; cursor: pointer;">삭제</button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">우편물 수령지</th>
                    <td>
                        <input type="radio" name="mb_mail_address_type" value="자택" <?php echo ($mb['mb_mail_address_type']=='자택')?'checked':''; ?>>
                        <label>자택</label>
                        <input type="radio" name="mb_mail_address_type" value="근무지" <?php echo ($mb['mb_mail_address_type']=='근무지')?'checked':''; ?>>
                        <label>근무지</label>
                    </td>
                    <th scope="row">재직여부</th>
                    <td>
                        <input type="checkbox" name="mb_employment_status" value="재직중" <?php echo ($mb['mb_employment_status']=='재직중')?'checked':''; ?>>
                        <label>재직중</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">근무지 명</th>
                    <td><input type="text" name="mb_work_name" value="<?php echo get_text($mb['mb_work_name']) ?>" class="frm_input" size="30"></td>
                    <th scope="row">근무지 전화번호</th>
                    <td><input type="text" name="mb_work_tel" value="<?php echo get_text($mb['mb_work_tel']) ?>" class="frm_input" size="20" placeholder="예) 02-1234-5678"></td>
                </tr>
                <tr>
                    <th scope="row">근무지 주소</th>
                    <td colspan="3">
                        <input type="text" name="mb_work_zip" value="<?php echo get_text($mb['mb_work_zip']) ?>" class="frm_input" size="6" maxlength="6" placeholder="우편번호">
                        <button type="button" onclick="win_zip('fmember', 'mb_work_zip', 'mb_work_addr1', 'mb_work_addr2', '', '');">주소 검색</button><br>
                        <input type="text" name="mb_work_addr1" value="<?php echo get_text($mb['mb_work_addr1']) ?>" class="frm_input" size="60" placeholder="기본주소"><br>
                        <input type="text" name="mb_work_addr2" value="<?php echo get_text($mb['mb_work_addr2']) ?>" class="frm_input" size="60" placeholder="상세주소">
                    </td>
                </tr>
                <tr>
                    <th scope="row">회원 검색 동의</th>
                    <td>
                        <input type="radio" name="mb_search_agree" value="동의" <?php echo ($mb['mb_search_agree']=='동의')?'checked':''; ?>>
                        <label>동의</label>
                        <input type="radio" name="mb_search_agree" value="동의하지 않음" <?php echo ($mb['mb_search_agree']=='동의하지 않음')?'checked':''; ?>>
                        <label>동의하지 않음</label>
                    </td>
                    <th scope="row">고유식별번호 수집 동의</th>
                    <td>
                        <input type="radio" name="mb_privacy_agree" value="동의" <?php echo ($mb['mb_privacy_agree']=='동의')?'checked':''; ?>>
                        <label>동의</label>
                        <input type="radio" name="mb_privacy_agree" value="동의하지 않음" <?php echo ($mb['mb_privacy_agree']=='동의하지 않음')?'checked':''; ?>>
                        <label>동의하지 않음</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">주소</th>
                    <td colspan="3" class="td_addr_line">
                        <label for="mb_zip" class="sound_only">우편번호</label>
                        <input type="text" name="mb_zip" value="<?php echo $mb['mb_zip1'] . $mb['mb_zip2']; ?>" id="mb_zip" class="frm_input readonly" size="5" maxlength="6">
                        <button type="button" class="btn_frmline" onclick="win_zip('fmember', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                        <input type="text" name="mb_addr1" value="<?php echo $mb['mb_addr1'] ?>" id="mb_addr1" class="frm_input readonly" size="60">
                        <label for="mb_addr1">기본주소</label><br>
                        <input type="text" name="mb_addr2" value="<?php echo $mb['mb_addr2'] ?>" id="mb_addr2" class="frm_input" size="60">
                        <label for="mb_addr2">상세주소</label>
                        <br>
                        <input type="text" name="mb_addr3" value="<?php echo $mb['mb_addr3'] ?>" id="mb_addr3" class="frm_input" size="60">
                        <label for="mb_addr3">참고항목</label>
                        <input type="hidden" name="mb_addr_jibeon" value="<?php echo $mb['mb_addr_jibeon']; ?>"><br>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_icon">회원아이콘</label></th>
                    <td colspan="3">
                        <?php echo help('이미지 크기는 <strong>넓이 ' . $config['cf_member_icon_width'] . '픽셀 높이 ' . $config['cf_member_icon_height'] . '픽셀</strong>로 해주세요.') ?>
                        <input type="file" name="mb_icon" id="mb_icon">
                        <?php
                        $mb_dir = substr($mb['mb_id'], 0, 2);
                        $icon_file = G5_DATA_PATH . '/member/' . $mb_dir . '/' . get_mb_icon_name($mb['mb_id']) . '.gif';
                        if (file_exists($icon_file)) {
                            $icon_url = str_replace(G5_DATA_PATH, G5_DATA_URL, $icon_file);
                            $icon_filemtile = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?' . filemtime($icon_file) : '';
                            echo '<img src="' . $icon_url . $icon_filemtile . '" alt="">';
                            echo '<input type="checkbox" id="del_mb_icon" name="del_mb_icon" value="1">삭제';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_img">회원이미지</label></th>
                    <td colspan="3">
                        <?php echo help('이미지 크기는 <strong>넓이 ' . $config['cf_member_img_width'] . '픽셀 높이 ' . $config['cf_member_img_height'] . '픽셀</strong>로 해주세요.') ?>
                        <input type="file" name="mb_img" id="mb_img">
                        <?php
                        $mb_dir = substr($mb['mb_id'], 0, 2);
                        $icon_file = G5_DATA_PATH . '/member_image/' . $mb_dir . '/' . get_mb_icon_name($mb['mb_id']) . '.gif';
                        if (file_exists($icon_file)) {
                            echo get_member_profile_img($mb['mb_id']);
                            echo '<input type="checkbox" id="del_mb_img" name="del_mb_img" value="1">삭제';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">메일 수신</th>
                    <td>
                        <input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?>>
                        <label for="mb_mailling_yes">예</label>
                        <input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?>>
                        <label for="mb_mailling_no">아니오</label>
                    </td>
                    <th scope="row"><label for="mb_sms_yes">SMS 수신</label></th>
                    <td>
                        <input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?>>
                        <label for="mb_sms_yes">예</label>
                        <input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?>>
                        <label for="mb_sms_no">아니오</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">정보 공개</th>
                    <td colspan="3">
                        <input type="radio" name="mb_open" value="1" id="mb_open_yes" <?php echo $mb_open_yes; ?>>
                        <label for="mb_open_yes">예</label>
                        <input type="radio" name="mb_open" value="0" id="mb_open_no" <?php echo $mb_open_no; ?>>
                        <label for="mb_open_no">아니오</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_signature">서명</label></th>
                    <td colspan="3"><textarea name="mb_signature" id="mb_signature"><?php echo $mb['mb_signature'] ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_profile">자기 소개</label></th>
                    <td colspan="3"><textarea name="mb_profile" id="mb_profile"><?php echo $mb['mb_profile'] ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_memo">메모</label></th>
                    <td colspan="3"><textarea name="mb_memo" id="mb_memo"><?php echo $mb['mb_memo'] ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_cert_history">본인인증 내역</label></th>
                    <td colspan="3">
                        <?php
                        $cnt = 0;
                        while ($row = sql_fetch_array($mb_cert_history)) {
                            $cnt++;
                            $cert_type = '';
                            switch ($row['ch_type']) {
                                case 'simple':
                                    $cert_type = '간편인증';
                                    break;
                                case 'hp':
                                    $cert_type = '휴대폰';
                                    break;
                                case 'ipin':
                                    $cert_type = '아이핀';
                                    break;
                            }
                        ?>
                            <div>
                                [<?php echo $row['ch_datetime']; ?>]
                                <?php echo $row['mb_id']; ?> /
                                <?php echo $row['ch_name']; ?> /
                                <?php echo $row['ch_hp']; ?> /
                                <?php echo $cert_type; ?>
                            </div>
                        <?php } ?>

                        <?php if ($cnt == 0) { ?>
                            본인인증 내역이 없습니다.
                        <?php } ?>
                    </td>
                </tr>

                <?php if ($w == 'u') { ?>
                    <tr>
                        <th scope="row">회원가입일</th>
                        <td><?php echo $mb['mb_datetime'] ?></td>
                        <th scope="row">최근접속일</th>
                        <td><?php echo $mb['mb_today_login'] ?></td>
                    </tr>
                    <tr>
                        <th scope="row">IP</th>
                        <td colspan="3"><?php echo $mb['mb_ip'] ?></td>
                    </tr>
                    <?php if ($config['cf_use_email_certify']) { ?>
                        <tr>
                            <th scope="row">인증일시</th>
                            <td colspan="3">
                                <?php if ($mb['mb_email_certify'] == '0000-00-00 00:00:00') { ?>
                                    <?php echo help('회원님이 메일을 수신할 수 없는 경우 등에 직접 인증처리를 하실 수 있습니다.') ?>
                                    <input type="checkbox" name="passive_certify" id="passive_certify">
                                    <label for="passive_certify">수동인증</label>
                                <?php } else { ?>
                                    <?php echo $mb['mb_email_certify'] ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

                <?php if ($config['cf_use_recommend']) { // 추천인 사용 ?>
                    <tr>
                        <th scope="row">추천인</th>
                        <td colspan="3"><?php echo ($mb['mb_recommend'] ? get_text($mb['mb_recommend']) : '없음'); // 081022 : CSRF 보안 결함으로 인한 코드 수정 ?></td>
                    </tr>
                <?php } ?>

                <tr>
                    <th scope="row"><label for="mb_leave_date">탈퇴일자</label></th>
                    <td>
                        <input type="text" name="mb_leave_date" value="<?php echo $mb['mb_leave_date'] ?>" id="mb_leave_date" class="frm_input" maxlength="8">
                        <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_leave_date_set_today" onclick="if (this.form.mb_leave_date.value==this.form.mb_leave_date.defaultValue) { this.form.mb_leave_date.value=this.value; } else { this.form.mb_leave_date.value=this.form.mb_leave_date.defaultValue; }">
                        <label for="mb_leave_date_set_today">탈퇴일을 오늘로 지정</label>
                    </td>
                    <th scope="row">접근차단일자</th>
                    <td>
                        <input type="text" name="mb_intercept_date" value="<?php echo $mb['mb_intercept_date'] ?>" id="mb_intercept_date" class="frm_input" maxlength="8">
                        <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_intercept_date_set_today" onclick="if (this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else { this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; }">
                        <label for="mb_intercept_date_set_today">접근차단일을 오늘로 지정</label>
                    </td>
                </tr>

                <?php
                //소셜계정이 있다면
                if (function_exists('social_login_link_account') && $mb['mb_id']) {
                    if ($my_social_accounts = social_login_link_account($mb['mb_id'], false, 'get_data')) { ?>
                        <tr>
                            <th>소셜계정목록</th>
                            <td colspan="3">
                                <ul class="social_link_box">
                                    <li class="social_login_container">
                                        <h4>연결된 소셜 계정 목록</h4>
                                        <?php foreach ($my_social_accounts as $account) {     //반복문
                                            if (empty($account)) {
                                                continue;
                                            }

                                            $provider = strtolower($account['provider']);
                                            $provider_name = social_get_provider_service_name($provider);
                                        ?>
                                            <div class="account_provider" data-mpno="social_<?php echo $account['mp_no']; ?>">
                                                <div class="sns-wrap-32 sns-wrap-over">
                                                    <span class="sns-icon sns-<?php echo $provider; ?>" title="<?php echo $provider_name; ?>">
                                                        <span class="ico"></span>
                                                        <span class="txt"><?php echo $provider_name; ?></span>
                                                    </span>

                                                    <span class="provider_name"><?php echo $provider_name;   //서비스이름 ?> ( <?php echo $account['displayname']; ?> )</span>
                                                    <span class="account_hidden" style="display:none"><?php echo $account['mb_id']; ?></span>
                                                </div>
                                                <div class="btn_info"><a href="<?php echo G5_SOCIAL_LOGIN_URL . '/unlink.php?mp_no=' . $account['mp_no'] ?>" class="social_unlink" data-provider="<?php echo $account['mp_no']; ?>">연동해제</a> <span class="sound_only"><?php echo substr($account['mp_register_day'], 2, 14); ?></span></div>
                                            </div>
                                        <?php } //end foreach ?>
                                    </li>
                                </ul>
                                <script>
                                    jQuery(function($) {
                                        $(".account_provider").on("click", ".social_unlink", function(e) {
                                            e.preventDefault();

                                            if (!confirm('정말 이 계정 연결을 삭제하시겠습니까?')) {
                                                return false;
                                            }

                                            var ajax_url = "<?php echo G5_SOCIAL_LOGIN_URL . '/unlink.php' ?>";
                                            var mb_id = '',
                                                mp_no = $(this).attr("data-provider"),
                                                $mp_el = $(this).parents(".account_provider");

                                            mb_id = $mp_el.find(".account_hidden").text();

                                            if (!mp_no) {
                                                alert('잘못된 요청! mp_no 값이 없습니다.');
                                                return;
                                            }

                                            $.ajax({
                                                url: ajax_url,
                                                type: 'POST',
                                                data: {
                                                    'mp_no': mp_no,
                                                    'mb_id': mb_id
                                                },
                                                dataType: 'json',
                                                async: false,
                                                success: function(data, textStatus) {
                                                    if (data.error) {
                                                        alert(data.error);
                                                        return false;
                                                    } else {
                                                        alert("연결이 해제 되었습니다.");
                                                        $mp_el.fadeOut("normal", function() {
                                                            $(this).remove();
                                                        });
                                                    }
                                                }
                                            });

                                            return;
                                        });
                                    });
                                </script>

                            </td>
                        </tr>

                <?php
                    }   //end if
                }   //end if

                run_event('admin_member_form_add', $mb, $w, 'table');
                ?>

                <?php for ($i = 2; $i <= 10; $i++) { ?>
                    <tr>
                        <th scope="row"><label for="mb_<?php echo $i ?>">여분 필드 <?php echo $i ?></label></th>
                        <td colspan="3"><input type="text" name="mb_<?php echo $i ?>" value="<?php echo $mb['mb_' . $i] ?>" id="mb_<?php echo $i ?>" class="frm_input" size="30" maxlength="255"></td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>

    <div class="btn_confirm01 btn_confirm">
        <a href="./member_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
        <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
    </div>
</form>

<script>
function addGraduateRow() {
    var tbody = document.getElementById('graduate_tbody');
    var newRow = document.createElement('tr');
    
    // 첫 번째 셀 - 석/박사 선택
    var cell1 = document.createElement('td');
    cell1.style.cssText = 'border: 1px solid #ddd; padding: 3px; text-align: center;';
    var degreeSelect = document.createElement('select');
    degreeSelect.name = 'graduate_degree[]';
    degreeSelect.style.width = '80px';
    degreeSelect.appendChild(new Option('선택', ''));
    degreeSelect.appendChild(new Option('석사', '석사'));
    degreeSelect.appendChild(new Option('박사', '박사'));
    cell1.appendChild(degreeSelect);
    
    // 두 번째 셀 - 학교 및 전공
    var cell2 = document.createElement('td');
    cell2.style.cssText = 'border: 1px solid #ddd; padding: 3px;';
    var schoolInput = document.createElement('input');
    schoolInput.type = 'text';
    schoolInput.name = 'graduate_school_major[]';
    schoolInput.style.width = '200px';
    schoolInput.placeholder = '예) 하버대학교 치아보철과';
    cell2.appendChild(schoolInput);
    
    // 세 번째 셀 - 입학년도
    var cell3 = document.createElement('td');
    cell3.style.cssText = 'border: 1px solid #ddd; padding: 3px; text-align: center;';
    var admissionSelect = document.createElement('select');
    admissionSelect.name = 'graduate_admission_year[]';
    admissionSelect.style.width = '80px';
    admissionSelect.appendChild(new Option('선택', ''));
    for (var year = 1980; year <= new Date().getFullYear(); year++) {
        admissionSelect.appendChild(new Option(year, year));
    }
    cell3.appendChild(admissionSelect);
    
    // 네 번째 셀 - 졸업년도
    var cell4 = document.createElement('td');
    cell4.style.cssText = 'border: 1px solid #ddd; padding: 3px; text-align: center;';
    var graduationSelect = document.createElement('select');
    graduationSelect.name = 'graduate_graduation_year[]';
    graduationSelect.style.width = '80px';
    graduationSelect.appendChild(new Option('선택', ''));
    for (var year = 1984; year <= new Date().getFullYear() + 6; year++) {
        graduationSelect.appendChild(new Option(year, year));
    }
    cell4.appendChild(graduationSelect);
    
    // 다섯 번째 셀 - 삭제 버튼
    var cell5 = document.createElement('td');
    cell5.style.cssText = 'border: 1px solid #ddd; padding: 3px; text-align: center;';
    var removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.style.cssText = 'background: #dc3545; color: white; border: none; padding: 3px 8px; cursor: pointer;';
    removeBtn.textContent = '삭제';
    removeBtn.onclick = function() { removeGraduateRow(this); };
    cell5.appendChild(removeBtn);
    
    // 모든 셀을 행에 추가
    newRow.appendChild(cell1);
    newRow.appendChild(cell2);
    newRow.appendChild(cell3);
    newRow.appendChild(cell4);
    newRow.appendChild(cell5);
    
    // 행을 tbody에 추가
    tbody.appendChild(newRow);
}

function removeGraduateRow(button) {
    var row = button.closest('tr');
    row.remove();
}

    function fmember_submit(f) {
        if (!f.mb_icon.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_icon.value) {
            alert('아이콘은 이미지 파일만 가능합니다.');
            return false;
        }

        if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
            alert('회원이미지는 이미지 파일만 가능합니다.');
            return false;
        }

        if( jQuery("#mb_password").val() ){
            <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함 ?>
        }

        return true;
    }

    jQuery(function($){
        $("#captcha_key").prop('required', false).removeAttr("required").removeClass("required");

        $("#mb_password").on("keyup", function(e) {
            var $warp = $("#mb_password_captcha_wrap"),
                tooptipid = "mp_captcha_tooltip",
                $span_text = $("<span>", {id:tooptipid, style:"font-size:0.95em;letter-spacing:-0.1em"}).html("비밀번호를 수정할 경우 캡챠를 입력해야 합니다."),
                $parent = $(this).parent(),
                is_invisible_recaptcha = $("#captcha").hasClass("invisible_recaptcha");

            if($(this).val()){
                $warp.show();
                if(! is_invisible_recaptcha) {
                    $warp.css("margin-top","1em");
                    if(! $("#"+tooptipid).length){ $parent.append($span_text) }
                }
            } else {
                $warp.hide();
                if($("#"+tooptipid).length && ! is_invisible_recaptcha){ $parent.find("#"+tooptipid).remove(); }
            }
        });
		$('input[name="mb_memclass"]').change(function() {
			var selectedValue = $(this).val();
			
			if (selectedValue === 'member') {
				$('#job_class_area').hide();
				$('#member_job_area').show();
				$('#student_job_area').hide();
			} else if (selectedValue === 'student') {
				$('#job_class_area').hide();
				$('#member_job_area').hide();
				$('#student_job_area').show();
			}
		});

    });
</script>
<?php
run_event('admin_member_form_after', $mb, $w);

require_once './admin.tail.php';
