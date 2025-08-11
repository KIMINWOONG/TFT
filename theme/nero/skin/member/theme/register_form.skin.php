<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/info.css">', 0);
?>
<style>
.graduate_table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.graduate_table th, .graduate_table td {  padding: 8px; text-align: center; }
.graduate_table th { background-color: #f8f8fa; font-weight: 500;font-size:1.8rem; height:56px;}
.graduate_table select { width: 100%; margin: 0 2px; }
.graduate_table input[type="text"] { width: 100%; border:1px solid #e5e5ec; border-radius:4px; padding:12px; }
.graduate_table input[type="text"]::placeholder{color:#999; font-size:1.8rem;}
.graduate_table .btn_add { background: #111111; color: white; border: none; padding: 10px 10px; cursor: pointer; border-radius: 3px;width: 60%; }
.graduate_table .btn_remove { background: #9b9b9bff; color: white; border: none; padding: 10px 10px; cursor: pointer; border-radius: 3px;width: 60%; }
/* .graduate_table .btn_add:hover { background: #0056b3; }
.graduate_table .btn_remove:hover { background: #c82333; } */
.employment_options { margin-bottom: 15px; }
.employment_detail_section { margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 5px; }
.employment_detail_section h6 { margin-bottom: 10px; color: #333; }
.workplace_info { margin-bottom: 15px; }
.workplace_info label { display: inline-block; width: 120px; margin-right: 10px; font-weight: bold; }
.workplace_info input[type="text"] { width: 300px; padding: 5px; }
.period_inputs { display: inline-block; }
.period_inputs select { width: 100px; margin: 0 5px; }
.period_inputs span { margin: 0 5px; }
.add_employment_btn { background: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; margin-left: 10px; }
.add_employment_btn:hover { background: #218838; }
</style>
<!-- 회원정보 입력/수정 시작 { -->

<div class="register width common">
    <h2>회원가입(Membership registration)</h2>
    <div class="register_step">
        <div class="on1">
        <div class="item">
            <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon1.png" alt=""> -->
            <p>STEP1. 약관 동의</p>
        </div>
        <div class="item">
            <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon4.png" alt=""> -->
            <p>STEP2. 회원 정보</p>
        </div>
        </div>
        <div class="item">
            <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon3.png" alt=""> -->
            <p>STEP3. 가입 완료</p>
        </div>
    </div>

<script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
<?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
<script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>
<?php } ?>

	<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="url" value="<?php echo $urlencode ?>">
	<input type="hidden" name="agree" value="<?php echo $agree ?>">
	<input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
	<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
	<input type="hidden" name="cert_no" value="">
	<?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
	<?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
	<input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
	<input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
	<?php }  ?>

	<div id="register_form" class="form_01">
        <h3>회원 정보</h3>
        <p>*표시된 부분은 반드시 기입해주시길 바랍니다.</p>
		<div class="register_form_inner">
			<ul>
				<li>
					<label for="">
						<span class="required_mark">*</span>회원구분 <span class="eng_title">(Membership Type)</span>
					</label>
					<div class="input_inner">
						<input type="radio" id="member" name="mb_memclass" value="member" <?=$member['mb_memclass']=="member"?"checked":"";?>>
						<label for="member">Licenced professional</label>
						<input type="radio" id="student" name="mb_memclass" value="student" class="margin" <?=$member['mb_memclass']=="student"?"checked":"";?>>
						<label for="student">Student, intern, or resident</label>
					</div>
				</li>
				<li>
					<label for="" class="padding">
						<span class="required_mark">*</span>성명<span class="eng_title">(Name)</span>
					</label>
					<div class="input_inner">
						<p>기존 Title : Dr.</p>
						<div class="radio_wrap">
						<input type="radio" id="dr" name="mb_title" value="dr" <?=$member['mb_title']=="dr"?"checked":"";?>>
						<label for="dr">Dr.</label>
						<input type="radio" id="prof" name="mb_title" value="prof" class="margin" <?=$member['mb_title']=="prof"?"checked":"";?>>
						<label for="prof">Prof.</label>
						<input type="radio" id="ms" name="mb_title" value="ms" class="margin" <?=$member['mb_title']=="ms"?"checked":"";?>>
						<label for="ms">Ms.</label>
						<input type="radio" id="mr" name="mb_title" value="mr" class="margin" <?=$member['mb_title']=="mr"?"checked":"";?>>
						<label for="mr">Mr.</label>
						</div>
						<div class="name_input_wrap">
                            <div>
                                <span>국문</span>
							    <input type="text" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" id="mb_name" class="required" minlength="2" maxlength="20" placeholder="예)홍길동" required>
                            </div>
                            <div>
                                <span>English</span>
							    <input type="text" name="mb_name_en" value="<?php echo get_text($member['mb_name_en']) ?>" id="mb_name_en" class="required" minlength="2" maxlength="50" placeholder="ex) KILDONG HONG" required>
                            </div>
						</div>
					</div>
				</li>
	            <li>
	                <label for="reg_mb_id"><span class="required_mark">*</span>
	                	아이디<span class="eng_title">(Id)</span>
	                </label>
					<div class="input_inner">
						<input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="frm_input full_input <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20" >
	                <span id="msg_mb_id"></span>
					<button type="button" class="id_check">중복 확인</button>
					</div>
	            </li>
	            <li class="">
	                <label for="reg_mb_password"><span class="required_mark">*</span>비밀번호<strong class="sound_only">필수</strong><span class="eng_title">(Password)</span></label>
					<div class="input_inner">
	                <input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" >
					</div>
				</li>
	            <li class="">
	                <label for="reg_mb_password_re"><span class="required_mark">*</span>비밀번호 확인<strong class="sound_only">필수</strong><span class="eng_title">(Confirm Password)</span></label>
					<div class="input_inner">
	                <input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" >
					</div>
	            </li>
				<li>
	                <label for="mb_birth"><span class="required_mark">*</span>생년월일<span class="eng_title">(Date of Birth)</span></label>
					<div class="input_inner">
                    <input type="text" name="mb_birth" id="mb_birth" value="<?php echo str_replace("-","",$member['mb_birth']) ?>" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" required placeholder="YYYYMMDD" maxlength=8>

	                <!-- <input type="date" name="mb_birth" id="mb_birth" value="<?php echo $member['mb_birth'] ?>" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" required> -->
					</div>
	            </li>
				<li>
					<label for="">
						<span class="required_mark">*</span>성별<span class="eng_title">(Gender)</span>
					</label>
					<div class="input_inner">
						<input type="radio" id="male" name="mb_sex" value="남자" <?php echo ($member['mb_sex']=='남자')?'checked':''; ?>>
						<label for="male">남자<span class="eng_title2">(Male)</span></label>
						<input type="radio" id="female" name="mb_sex" value="여자" class="margin" <?php echo ($member['mb_sex']=='여자')?'checked':''; ?>>
						<label for="female">여자<span class="eng_title2">(Female)</span></label>
					</div>
				</li>
				<li class="">
	                <label for="mb_license_no"><span class="required_mark">*</span>면허번호<span class="eng_title">(License Number)</span></label>
					<div class="input_inner">
	                <input type="text" name="mb_license_no" id="mb_license_no" value="<?php echo get_text($member['mb_license_no']) ?>" class="frm_input full_input" maxlength="50">
					<input type="checkbox" name="mb_license_none" id="mb_license_none" value="1" <?php echo ($member['mb_license_none'])?'checked':''; ?> class="check_box">
					<label for="mb_license_none">면허번호 없음<span class="eng_title2">(No License Number)</span></label>
					</div>
				</li>
				<li>
	                <label for="reg_mb_email"><span class="required_mark">*</span>이메일<span class="eng_title">(Email)</span></label>
					<div class="input_inner">
	                <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
	                <input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="frm_input email full_input required" size="70" maxlength="100" >
					</div>
	            </li>
				<li>
	                <label for="mb_hp"><span class="required_mark">*</span>휴대전화<span class="eng_title">(Mobile Number)</span></label>
					<div class="input_inner number_input3">
                        <div>
						<select name="mb_hp_country" id="mb_hp_country">
                            <option value="+82" <?php echo ($member['mb_hp_country']=='+82')?'selected':''; ?>>대한민국 (+82)</option>
                            <option value="+1" <?php echo ($member['mb_hp_country']=='+1')?'selected':''; ?>>미국 (+1)</option>
                            <option value="+81" <?php echo ($member['mb_hp_country']=='+81')?'selected':''; ?>>일본 (+81)</option>
                            <option value="+86" <?php echo ($member['mb_hp_country']=='+86')?'selected':''; ?>>중국 (+86)</option>
                            <option value="+46" <?php echo ($member['mb_hp_country']=='+46')?'selected':''; ?>>스웨덴 (+46)</option>
                            <option value="+32" <?php echo ($member['mb_hp_country']=='+32')?'selected':''; ?>>벨기에 (+32)</option>
                            <option value="+31" <?php echo ($member['mb_hp_country']=='+31')?'selected':''; ?>>네덜란드 (+31)</option>
                            <option value="+45" <?php echo ($member['mb_hp_country']=='+45')?'selected':''; ?>>덴마크 (+45)</option>
                            <option value="+49" <?php echo ($member['mb_hp_country']=='+49')?'selected':''; ?>>독일 (+49)</option>
                            <option value="+44" <?php echo ($member['mb_hp_country']=='+44')?'selected':''; ?>>영국 (+44)</option>
                            <option value="+33" <?php echo ($member['mb_hp_country']=='+33')?'selected':''; ?>>프랑스 (+33)</option>
                            <option value="+34" <?php echo ($member['mb_hp_country']=='+34')?'selected':''; ?>>스페인 (+34)</option>
                            <option value="+351" <?php echo ($member['mb_hp_country']=='+351')?'selected':''; ?>>포르투갈 (+351)</option>
                            <option value="+39" <?php echo ($member['mb_hp_country']=='+39')?'selected':''; ?>>이탈리아 (+39)</option>
                            <option value="+39" <?php echo ($member['mb_hp_country']=='+39')?'selected':''; ?>>로마 (+39)</option>
                            <option value="+90" <?php echo ($member['mb_hp_country']=='+90')?'selected':''; ?>>터키 (+90)</option>
                            <option value="+377" <?php echo ($member['mb_hp_country']=='+377')?'selected':''; ?>>모나코 (+377)</option>
                            <option value="+213" <?php echo ($member['mb_hp_country']=='+213')?'selected':''; ?>>알제리 (+213)</option>
                            <option value="+218" <?php echo ($member['mb_hp_country']=='+218')?'selected':''; ?>>리비아 (+218)</option>
                            <option value="+212" <?php echo ($member['mb_hp_country']=='+212')?'selected':''; ?>>모로코 (+212)</option>
                            <option value="+20" <?php echo ($member['mb_hp_country']=='+20')?'selected':''; ?>>이집트 (+20)</option>
                            <option value="+254" <?php echo ($member['mb_hp_country']=='+254')?'selected':''; ?>>케냐 (+254)</option>
                            <option value="+234" <?php echo ($member['mb_hp_country']=='+234')?'selected':''; ?>>나이지리아 (+234)</option>
                            <option value="+233" <?php echo ($member['mb_hp_country']=='+233')?'selected':''; ?>>가나 (+233)</option>
                            <option value="+27" <?php echo ($member['mb_hp_country']=='+27')?'selected':''; ?>>남아공 (+27)</option>
                            <option value="+380" <?php echo ($member['mb_hp_country']=='+380')?'selected':''; ?>>우크라이나 (+380)</option>
                            <option value="+36" <?php echo ($member['mb_hp_country']=='+36')?'selected':''; ?>>헝가리 (+36)</option>
                            <option value="+420" <?php echo ($member['mb_hp_country']=='+420')?'selected':''; ?>>체코 (+420)</option>
                            <option value="+386" <?php echo ($member['mb_hp_country']=='+386')?'selected':''; ?>>슬로베니아 (+386)</option>
                            <option value="+375" <?php echo ($member['mb_hp_country']=='+375')?'selected':''; ?>>벨라루스 (+375)</option>
                            <option value="+48" <?php echo ($member['mb_hp_country']=='+48')?'selected':''; ?>>폴란드 (+48)</option>
                            <option value="+371" <?php echo ($member['mb_hp_country']=='+371')?'selected':''; ?>>라트비아 (+371)</option>
                            <option value="+372" <?php echo ($member['mb_hp_country']=='+372')?'selected':''; ?>>에스토니아 (+372)</option>
                            <option value="+359" <?php echo ($member['mb_hp_country']=='+359')?'selected':''; ?>>불가리아 (+359)</option>
                            <option value="+373" <?php echo ($member['mb_hp_country']=='+373')?'selected':''; ?>>몰도바 (+373)</option>
                            <option value="+7" <?php echo ($member['mb_hp_country']=='+7')?'selected':''; ?>>러시아 (+7)</option>
                        </select>

						<input type="text" name="mb_hp" id="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" required class="frm_input required" >
                        </div>
					</div>
	            </li>

				<?php
				if($w==""){
					$member_job_area_class="none";
					$student_job_area_class="none";
				}else{
					if($member['mb_memclass']=="member"){
						$member_job_area_class="block";
						$student_job_area_class="none";
					}else{
						$member_job_area_class="none";
						$student_job_area_class="block";
					}
				}
				?>
				<li>
	                <label for=""><span class="required_mark">*</span>직군<span class="eng_title">(Occupation)</span></label>
					<?php if($w==""){?>
					<div class="input_inner basic" id="job_class_area">
						<label for="memberType">회원구분을 선택해 주세요<span class="eng_title2">Please select your membership type.</span></label>
						<!-- <h5>직군에 따라 회비 금액이 상이하게 책정됩니다.<span class="eng_title2">Membership fees vary by occupation.</span></h5> -->
					</div>
					<?php }?>
					<!-- member선택했을때 -->
					<div class="input_inner sel_mem" id="member_job_area" style="display:<?=$member_job_area_class?>;">
						<input type="radio" id="dentist" name="mb_job_class" value="치과의사" <?php echo ($member['mb_job_class']=='치과의사')?'checked':''; ?>>
						<label for="dentist">치과의사<span class="eng_title2">(Dentist)</span></label>

						<input type="radio" id="hygienist" name="mb_job_class" value="치위생사"class="margin" <?php echo ($member['mb_job_class']=='치위생사')?'checked':''; ?>>
						<label for="hygienist">치위생사<span class="eng_title2">(Dental Hygienist)</span></label>
						
						<input type="radio" id="technician" name="mb_job_class" value="기공사" class="margin" <?php echo ($member['mb_job_class']=='기공사')?'checked':''; ?>>
						<label for="technician">기공사<span class="eng_title2">(Dental Technician)</span></label>
						<input type="radio" id="nurse" name="mb_job_class" value="간호사" class="margin" <?php echo ($member['mb_job_class']=='간호사')?'checked':''; ?>>
						<label for="nurse">간호사<span class="eng_title2">(Nurse)</span></label>
						<!-- <h5>직군에 따라 회비 금액이 상이하게 책정됩니다.<span class="eng_title2">Membership fees vary by occupation.</span></h5> -->
					</div>
					<!-- student선택했을때 -->
					<div class="input_inner sel_stu" id="student_job_area" style="display:<?=$student_job_area_class?>;">
						<input type="radio" id="student_type" name="mb_student_class" value="학생" <?php echo ($member['mb_student_class']=='학생')?'checked':''; ?>>
						<label for="student_type">학생<span class="eng_title2">(Student)</span></label>
						<input type="radio" id="resident" name="mb_student_class" value="전공의" class="margin" <?php echo ($member['mb_student_class']=='전공의')?'checked':''; ?>>
						<label for="resident">전공의<span class="eng_title2">(Intern or Resident)</span></label>
						<input type="radio" id="student_etc" name="mb_student_class" value="기타" class="margin" <?php echo ($member['mb_student_class']=='기타')?'checked':''; ?>>
						<label for="student_etc">기타<span class="eng_title2">(Etc)</span></label>
						<!-- <h5>직군에 따라 회비 금액이 상이하게 책정됩니다.<span class="eng_title2">Membership fees vary by occupation. </span></h5>-->


					</div>
	            </li>
			</ul>
	    </div>

	<div id="register_form" class="form_01">
	    <div class="tbl_frm01 tbl_wrap register_form_inner">
	        <ul>

            <li>
                <label for=""><span class="required_mark">*</span>출신학교(학사)<span class="eng_title">(Institution Attended)</span></label>
					<div class="input_inner number_input number_input2">
	      				<select name="mb_location_type" id="mb_location_type" class="custom-select" required>
                            <option value="" disabled selected hidden>선택</option>
                            <option value="domestic" <?=$member['mb_location_type']=="domestic"?"selected":"";?>>국내</option>
                            <option value="international" <?=$member['mb_location_type']=="international"?"selected":"";?>>국외</option>
                        </select>
					</div>
            </li>
	            
				<li class="domestic">
	                <label for="mb_school"><span class="required_mark">*</span>국내학교(학사)<span class="eng_title">(Domestic School)</span></label>
					<div class="input_inner number_input number_input2">
	      				<select name="mb_school" id="mb_school" class="custom-select" >
                            <option value="" disabled selected hidden>선택</option>
                            <option value="강릉원주대학교 치과대학" <?php echo ($member['mb_school']=='강릉원주대학교 치과대학')?'selected':''; ?>>강릉원주대학교 치과대학</option>
                            <option value="경북대학교 치과대학/치의학전문대학원" <?php echo ($member['mb_school']=='경북대학교 치과대학/치의학전문대학원')?'selected':''; ?>>경북대학교 치과대학/치의학전문대학원</option>
                            <option value="경희대학교 치과대학/치의학전문대학원" <?php echo ($member['mb_school']=='경희대학교 치과대학/치의학전문대학원')?'selected':''; ?>>경희대학교 치과대학/치의학전문대학원</option>
                            <option value="단국대학교 치과대학" <?php echo ($member['mb_school']=='단국대학교 치과대학')?'selected':''; ?>>단국대학교 치과대학</option>
                            <option value="부산대학교 치과대학/치의학전문대학원" <?php echo ($member['mb_school']=='부산대학교 치과대학/치의학전문대학원')?'selected':''; ?>>부산대학교 치과대학/치의학전문대학원</option>
                            <option value="서울대학교 치과대학/치의학대학원" <?php echo ($member['mb_school']=='서울대학교 치과대학/치의학대학원')?'selected':''; ?>>서울대학교 치과대학/치의학대학원</option>
                            <option value="연세대학교 치과대학/치의학전문대학원" <?php echo ($member['mb_school']=='연세대학교 치과대학/치의학전문대학원')?'selected':''; ?>>연세대학교 치과대학/치의학전문대학원</option>
                            <option value="원광대학교 치과대학" <?php echo ($member['mb_school']=='원광대학교 치과대학')?'selected':''; ?>>원광대학교 치과대학</option>
                            <option value="전남대학교 치과대학/치의학전문대학원" <?php echo ($member['mb_school']=='전남대학교 치과대학/치의학전문대학원')?'selected':''; ?>>전남대학교 치과대학/치의학전문대학원</option>
                            <option value="전북대학교 치과대학/치의학전문대학원" <?php echo ($member['mb_school']=='전북대학교 치과대학/치의학전문대학원')?'selected':''; ?>>전북대학교 치과대학/치의학전문대학원</option>
                            <option value="조선대학교 치과대학/치의학전문대학원" <?php echo ($member['mb_school']=='조선대학교 치과대학/치의학전문대학원')?'selected':''; ?>>조선대학교 치과대학/치의학전문대학원</option>
                            <option value="기타" <?php echo ($member['mb_school']=='기타')?'selected':''; ?>>기타</option>
                        </select>
                        <input type="text" name="mb_school_domestic_etc" id="mb_school_domestic_etc" value="<?php echo get_text($member['mb_school_domestic_etc']) ?>" placeholder="기타" disabled>
					</div>
	            </li>

                
                <li class="international">
	                <label for="mb_school_etc"><span class="required_mark">*</span>국외학교(학사)<span class="eng_title">(Overseas School)</span></label>
					<div class="input_inner number_input number_input2">
	      				<select name="mb_school_etc" class="custom-select width_100" id="mb_school_etc" >
                            <option value="" disabled selected hidden>선택</option>
							<option value="University of Michigan-Ann Arbor" <?php echo ($member['mb_school_etc']=='University of Michigan-Ann Arbor')?'selected':''; ?>>University of Michigan-Ann Arbor</option>
							<option value="Academic Centre for Dentistry Amsterdam (ACTA)" <?php echo ($member['mb_school_etc']=='Academic Centre for Dentistry Amsterdam (ACTA)')?'selected':''; ?>>Academic Centre for Dentistry Amsterdam (ACTA)</option>
							<option value="The University of Hong Kong" <?php echo ($member['mb_school_etc']=='The University of Hong Kong')?'selected':''; ?>>The University of Hong Kong</option>
							<option value="King's College London" <?php echo ($member['mb_school_etc']=='King\'s College London')?'selected':''; ?>>King's College London</option>
							<option value="Tokyo Medical and Dental University" <?php echo ($member['mb_school_etc']=='Tokyo Medical and Dental University')?'selected':''; ?>>Tokyo Medical and Dental University</option>
							<option value="University of Gothenburg" <?php echo ($member['mb_school_etc']=='University of Gothenburg')?'selected':''; ?>>University of Gothenburg</option>
							<option value="Harvard University" <?php echo ($member['mb_school_etc']=='Harvard University')?'selected':''; ?>>Harvard University</option>
							<option value="University of Bern" <?php echo ($member['mb_school_etc']=='University of Bern')?'selected':''; ?>>University of Bern</option>
							<option value="University of São Paulo" <?php echo ($member['mb_school_etc']=='University of São Paulo')?'selected':''; ?>>University of São Paulo</option>
							<option value="University of British Columbia" <?php echo ($member['mb_school_etc']=='University of British Columbia')?'selected':''; ?>>University of British Columbia</option>
							<option value="University of North Carolina, Chapel Hill" <?php echo ($member['mb_school_etc']=='University of North Carolina, Chapel Hill')?'selected':''; ?>>University of North Carolina, Chapel Hill</option>
							<option value="University of Zurich" <?php echo ($member['mb_school_etc']=='University of Zurich')?'selected':''; ?>>University of Zurich</option>
							<option value="University of Oslo" <?php echo ($member['mb_school_etc']=='University of Oslo')?'selected':''; ?>>University of Oslo</option>
							<option value="University of Otago" <?php echo ($member['mb_school_etc']=='University of Otago')?'selected':''; ?>>University of Otago</option>
							<option value="University of Copenhagen" <?php echo ($member['mb_school_etc']=='University of Copenhagen')?'selected':''; ?>>University of Copenhagen</option>
							<option value="University of California, San Francisco" <?php echo ($member['mb_school_etc']=='University of California, San Francisco')?'selected':''; ?>>University of California, San Francisco</option>
							<option value="University of Leeds" <?php echo ($member['mb_school_etc']=='University of Leeds')?'selected':''; ?>>University of Leeds</option>
							<option value="University of Birmingham" <?php echo ($member['mb_school_etc']=='University of Birmingham')?'selected':''; ?>>University of Birmingham</option>
							<option value="University of Minnesota" <?php echo ($member['mb_school_etc']=='University of Minnesota')?'selected':''; ?>>University of Minnesota</option>
							<option value="University of Basel" <?php echo ($member['mb_school_etc']=='University of Basel')?'selected':''; ?>>University of Basel</option>
							<option value="University of Glasgow" <?php echo ($member['mb_school_etc']=='University of Glasgow')?'selected':''; ?>>University of Glasgow</option>
							<option value="Seoul National University" <?php echo ($member['mb_school_etc']=='Seoul National University')?'selected':''; ?>>Seoul National University</option>
							<option value="University of Alberta" <?php echo ($member['mb_school_etc']=='University of Alberta')?'selected':''; ?>>University of Alberta</option>
							<option value="University of Melbourne" <?php echo ($member['mb_school_etc']=='University of Melbourne')?'selected':''; ?>>University of Melbourne</option>
							<option value="Universidade Estadual de Campinas (Unicamp)" <?php echo ($member['mb_school_etc']=='Universidade Estadual de Campinas (Unicamp)')?'selected':''; ?>>Universidade Estadual de Campinas (Unicamp)</option>
							<option value="University of Dundee" <?php echo ($member['mb_school_etc']=='University of Dundee')?'selected':''; ?>>University of Dundee</option>
							<option value="McGill University" <?php echo ($member['mb_school_etc']=='McGill University')?'selected':''; ?>>McGill University</option>
							<option value="University of Sydney" <?php echo ($member['mb_school_etc']=='University of Sydney')?'selected':''; ?>>University of Sydney</option>
							<option value="University of Adelaide" <?php echo ($member['mb_school_etc']=='University of Adelaide')?'selected':''; ?>>University of Adelaide</option>
							<option value="University of Iowa" <?php echo ($member['mb_school_etc']=='University of Iowa')?'selected':''; ?>>University of Iowa</option>
							<option value="University of Manchester" <?php echo ($member['mb_school_etc']=='University of Manchester')?'selected':''; ?>>University of Manchester</option>
							<option value="University of Helsinki" <?php echo ($member['mb_school_etc']=='University of Helsinki')?'selected':''; ?>>University of Helsinki</option>
							<option value="University of Sheffield" <?php echo ($member['mb_school_etc']=='University of Sheffield')?'selected':''; ?>>University of Sheffield</option>
							<option value="University of Milan" <?php echo ($member['mb_school_etc']=='University of Milan')?'selected':''; ?>>University of Milan</option>
							<option value="University of Pennsylvania" <?php echo ($member['mb_school_etc']=='University of Pennsylvania')?'selected':''; ?>>University of Pennsylvania</option>
							<option value="University of Lisbon" <?php echo ($member['mb_school_etc']=='University of Lisbon')?'selected':''; ?>>University of Lisbon</option>
							<option value="University of Florida" <?php echo ($member['mb_school_etc']=='University of Florida')?'selected':''; ?>>University of Florida</option>
							<option value="University of Geneva" <?php echo ($member['mb_school_etc']=='University of Geneva')?'selected':''; ?>>University of Geneva</option>
							<option value="University of Toronto" <?php echo ($member['mb_school_etc']=='University of Toronto')?'selected':''; ?>>University of Toronto</option>
							<option value="Universidad Complutense de Madrid" <?php echo ($member['mb_school_etc']=='Universidad Complutense de Madrid')?'selected':''; ?>>Universidad Complutense de Madrid</option>
							<option value="New York University (NYU)" <?php echo ($member['mb_school_etc']=='New York University (NYU)')?'selected':''; ?>>New York University (NYU)</option>
                            <option value="기타" <?php echo ($member['mb_school_etc']=='기타')?'selected':''; ?>>etc</option>
						</select>
                        <input type="text" name="mb_school_foreign_etc" id="mb_school_foreign_etc" value="<?php echo get_text($member['mb_school_foreign_etc']) ?>" placeholder="기타" disabled>
					</div>
	            </li>

				<li>
  <label for="mb_major"><span class="required_mark">*</span>전공과목<span class="eng_title">(Major)</span></label>
  <div class="input_inner number_input number_input2">
    <select name="mb_major" id="mb_major" class="custom-select" required>
        <option value="" disabled selected hidden>선택</option>
        <option value="구강내과학" <?php echo ($member['mb_major']=='구강내과학')?'selected':''; ?>>구강내과학</option>
        <option value="구강병리학" <?php echo ($member['mb_major']=='구강병리학')?'selected':''; ?>>구강병리학</option>
        <option value="구강보건학" <?php echo ($member['mb_major']=='구강보건학')?'selected':''; ?>>구강보건학</option>
        <option value="구강생물학" <?php echo ($member['mb_major']=='구강생물학')?'selected':''; ?>>구강생물학</option>
        <option value="구강악안면외과학" <?php echo ($member['mb_major']=='구강악안면외과학')?'selected':''; ?>>구강악안면외과학</option>
        <option value="소아치과학" <?php echo ($member['mb_major']=='소아치과학')?'selected':''; ?>>소아치과학</option>
        <option value="영상치의학" <?php echo ($member['mb_major']=='영상치의학')?'selected':''; ?>>영상치의학</option>
        <option value="전공분야없음" <?php echo ($member['mb_major']=='전공분야없음')?'selected':''; ?>>전공분야없음</option>
        <option value="치과교정학" <?php echo ($member['mb_major']=='치과교정학')?'selected':''; ?>>치과교정학</option>
        <option value="치과보존학" <?php echo ($member['mb_major']=='치과보존학')?'selected':''; ?>>치과보존학</option>
        <option value="치과보철학" <?php echo ($member['mb_major']=='치과보철학')?'selected':''; ?>>치과보철학</option>
        <option value="치과재료학" <?php echo ($member['mb_major']=='치과재료학')?'selected':''; ?>>치과재료학</option>
        <option value="치주과학" <?php echo ($member['mb_major']=='치주과학')?'selected':''; ?>>치주과학</option>
        <option value="통합치의학" <?php echo ($member['mb_major']=='통합치의학')?'selected':''; ?>>통합치의학</option>
        <option value="기타(전공)" <?php echo ($member['mb_major']=='기타(전공)')?'selected':''; ?>>기타(전공)</option>
    </select>

    <input type="text" name="mb_major_etc" id="mb_major_etc" value="<?php echo get_text($member['mb_major_etc']) ?>" placeholder="기타" disabled>
  </div>
</li>

				<li>
	                <label for="mb_branch"><span class="required_mark">*</span>활동희망분과<span class="eng_title">(Division)</span></label>
					<div class="input_inner number_input number_input2">
	      				<select name="mb_branch" class="custom-select" id="mb_branch" required>
                            <option value="" disabled selected hidden>선택</option>
							<option value="구강외과 분과" <?php echo ($member['mb_branch']=='구강외과 분과')?'selected':''; ?>>구강외과 분과</option>
							<option value="보철 분과" <?php echo ($member['mb_branch']=='보철 분과')?'selected':''; ?>>보철 분과</option>
							<option value="치주 분과" <?php echo ($member['mb_branch']=='치주 분과')?'selected':''; ?>>치주 분과</option>
							<option value="연구 분과" <?php echo ($member['mb_branch']=='연구 분과')?'selected':''; ?>>연구 분과</option>
							<option value="영상 및 AI 분과" <?php echo ($member['mb_branch']=='영상 및 AI 분과')?'selected':''; ?>>영상 및 AI 분과</option>
							<option value="통합치의학 및 장애인치과 분과" <?php echo ($member['mb_branch']=='통합치의학 및 장애인치과 분과')?'selected':''; ?>>통합치의학 및 장애인치과 분과</option>
						</select>
					</div>
	            </li>
 
				

				<!-- 새로 추가: 입학/졸업년도(학사) -->
				<li>
					<label for=""><span class="required_mark">*</span>입학/졸업년도(학사)<span class="eng_title">(Undergraduate Admission/Graduation Year)</span></label>
					<div class="input_inner number_input4 input_inner2" >
                        <div>
                            <label>입학년도</label>
						<select name="mb_bachelor_admission_year" id="mb_bachelor_admission_year" class="custom-select" required>
							<option value="" disabled selected hidden>선택</option>
							<?php for($year = 1960; $year <= date('Y'); $year++): ?>
								<option value="<?=$year?>" <?php echo ($member['mb_bachelor_admission_year']==$year)?'selected':''; ?>><?=$year?></option>
							<?php endfor; ?>
						</select>
                        </div>
						
						<span>-</span>
                        <div>
                            <label>졸업년도</label>
						<select name="mb_bachelor_graduation_year" id="mb_bachelor_graduation_year" class="custom-select" required>
							<option value="" disabled selected hidden>선택</option>
							<?php for($year = 1964; $year <= date('Y')+6; $year++): ?>
								<option value="<?=$year?>" <?php echo ($member['mb_bachelor_graduation_year']==$year)?'selected':''; ?>><?=$year?></option>
							<?php endfor; ?>
						</select>

                        </div>
						
					</div>
				</li>

				<!-- 완성된 석/박사 졸업년도 섹션 -->
				<li class="align">
					<label for="">석/박사 졸업<span class="eng_title">(Master's/PhD Graduation)</span></label>
					<div id="graduate_tbody" class="input_inner number_input5">
						<?php 
						// 기존 데이터가 있는 경우 표시
						$existing_graduate_data = array();
						if (isset($member['mb_graduate_data'])) {
							$existing_graduate_data = json_decode($member['mb_graduate_data'], true);
						}
							
						if (empty($existing_graduate_data)) {
							// 기본 행 하나 추가
							$existing_graduate_data = array(
								array('degree' => '', 'school_major' => '', 'admission_year' => '', 'graduation_year' => '')
							);
						}
						$zz=0;	
						foreach ($existing_graduate_data as $index => $data) {
						?>
                       <div class="items">
                            <div class="item">
								<div class="item_title right_border"><h2>학위</h2></div>
                                <div class="item_contents right_border">
                                    <select name="graduate_degree[]" class="custom-select">
										<option value="">선택</option>
										<option value="석사" <?php echo ($data['degree']=='석사')?'selected':''; ?>>석사</option>
										<option value="박사" <?php echo ($data['degree']=='박사')?'selected':''; ?>>박사</option>
								    </select>
                                </div>
                            </div>
                            <div class="item">
								<div class="item_title right_border"><h2>학교 및 전공</h2></div>
                                <div class="item_contents right_border">
                                    <input type="text" name="graduate_school_major[]" value="<?php echo htmlspecialchars($data['school_major']); ?>" class="frm_input" placeholder="예) 하버대학교 치아보철과">
                                </div>
                            </div>

                            <div class="item">
								<div class="item_title right_border"><h2>입학년도</h2></div>
                                <div class="item_contents right_border" >
                                    <select name="graduate_admission_year[]" class="custom-select">
										<option value="">선택</option>
										<?php for($year = 1960; $year <= date('Y'); $year++): ?>
											<option value="<?=$year?>" <?php echo ($data['admission_year']==$year)?'selected':''; ?>><?=$year?></option>
										<?php endfor; ?>
									</select>
                                </div>
                            </div>

                            <div class="item">
								<div class="item_title right_border"><h2>졸업년도</h2></div>
                                <div class="item_contents right_border">
                                    <select name="graduate_graduation_year[]" class="custom-select">
										<option value="">선택</option>
										<?php for($year = 1964; $year <= date('Y')+6; $year++): ?>
											<option value="<?=$year?>" <?php echo ($data['graduation_year']==$year)?'selected':''; ?>><?=$year?></option>
										<?php endfor; ?>
									</select>
                                </div>
                            </div>

                            <div class="item border_coustom">
									<div class="item_title "><h2>관리</h2></div>
                                    <div class="item_contents">
                                        <?php if ($index == 0) { ?>
										<button type="button" class="btn_add" onclick="addGraduateRow()">추가</button>
									<?php } else { ?>
										<button type="button" class="btn_remove" onclick="removeGraduateRow(this)">삭제</button>
									<?php } ?>
                                    </div>
                            </div>
						</div>
						<?php
							$zz++;
						}
						if($zz==0){
						?>
                        <div class="items">
                            <div class="item">
                                <div class="item_title right_border"><h2>학위</h2></div>
                                <div class="item_contents right_border">
                                    <select name="graduate_degree[]" class="custom-select">
										<option value="">선택</option>
										<option value="석사" <?php echo ($data['degree']=='석사')?'selected':''; ?>>석사</option>
										<option value="박사" <?php echo ($data['degree']=='박사')?'selected':''; ?>>박사</option>
								    </select>
                                </div>
                            </div>
                            <div class="item">
                                <div class="item_title right_border"><h2>학교 및 전공</h2></div>
                                <div class="item_contents right_border">
                                    <input type="text" name="graduate_school_major[]" value="<?php echo htmlspecialchars($data['school_major']); ?>" class="frm_input" placeholder="예) 하버대학교 치아보철과">
                                </div>
                            </div>

                            <div class="item">
                                <div class="item_title right_border"><h2>입학년도</h2></div>
                                <div class="item_contents right_border" >
                                    <select name="graduate_admission_year[]" class="custom-select">
										<option value="">선택</option>
										<?php for($year = 1960; $year <= date('Y'); $year++): ?>
											<option value="<?=$year?>" <?php echo ($data['admission_year']==$year)?'selected':''; ?>><?=$year?></option>
										<?php endfor; ?>
									</select>
                                </div>
                            </div>

                            <div class="item">
                                <div class="item_title right_border"><h2>졸업년도</h2></div>
                                <div class="item_contents right_border">
                                    <select name="graduate_graduation_year[]" class="custom-select">
										<option value="">선택</option>
										<?php for($year = 1964; $year <= date('Y')+6; $year++): ?>
											<option value="<?=$year?>" <?php echo ($data['graduation_year']==$year)?'selected':''; ?>><?=$year?></option>
										<?php endfor; ?>
									</select>
                                </div>
                            </div>

                            <div class="item border_coustom">
                                    <div class="item_title "><h2>관리</h2></div>
                                    <div class="item_contents">
                                        <?php if ($index == 0) { ?>
										<button type="button" class="btn_add" onclick="addGraduateRow()">추가</button>
									<?php } else { ?>
										<button type="button" class="btn_remove" onclick="removeGraduateRow(this)">삭제</button>
									<?php } ?>
                                    </div>
                            </div>
						</div>
						<?php
						}
						?>

					</div>
				</li>


				<!-- 새로 추가: 우편물 수령지 -->
				<!-- <li>
					<label for=""><span class="required_mark">*</span>우편물 수령지<span class="eng_title">(Preferred mailing address)</span></label>
					<div class="input_inner">
						<input type="radio" id="mail_to_home" name="mb_mail_address_type" value="자택" <?php echo ($member['mb_mail_address_type']=='자택')?'checked':''; ?>>
						<label for="mail_to_home">자택<span class="eng_title2">(Home)</span></label>
						<input type="radio" id="mail_to_work" name="mb_mail_address_type" value="근무지" class="margin" <?php echo ($member['mb_mail_address_type']=='근무지')?'checked':''; ?>>
						<label for="mail_to_work">근무지<span class="eng_title2">(Workplace)</span></label>
					</div>
				</li> -->

				<!-- 새로 추가: 재직여부 -->
				<!-- <li>
					<label for=""><span class="required_mark">*</span>재직여부<span class="eng_title">(Employment Status)</span></label>
					<div class="input_inner">
						<input type="checkbox" id="self_employed" name="mb_employment_status" value="재직중" class="margin" <?php echo ($member['mb_employment_status']=='재직중')?'checked':''; ?>> 재직중
					</div>
				</li> -->




				<li>
	                <label for="mb_work_name"><span class="required_mark">*</span>근무지 명<span class="eng_title">(Workplace Name)</span></label>
					<div class="input_inner">
	      				<input type="text" name="mb_work_name" id="mb_work_name" value="<?php echo get_text($member['mb_work_name']) ?>" class="full_input" required>
					</div>
	            </li>
				<li class="company_address">
	                <label for="" class="address"><span class="required_mark">*</span>근무지 주소<span class="eng_title">(Workplace Address)</span></label>
					<div class="input_inner">
	      				<label for="mb_work_zip" class="sound_only">우편번호<strong class="sound_only"> 필수</strong></label>
	                <input type="text" name="mb_work_zip" value="<?php echo get_text($member['mb_work_zip']) ?>" id="mb_work_zip" required class="frm_input twopart_input full_input required" size="5" maxlength="6">
	                <button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_work_zip', 'mb_work_addr1', 'mb_work_addr2', '', '');">우편번호 검색</button><br>
	                <input type="text" name="mb_work_addr1" value="<?php echo get_text($member['mb_work_addr1']) ?>" id="mb_work_addr1" required class="frm_input frm_address full_input required" size="50">
	                <label for="mb_work_addr1" class="sound_only">기본주소<strong> 필수</strong></label><br>
	                <input type="text" name="mb_work_addr2" value="<?php echo get_text($member['mb_work_addr2']) ?>" id="mb_work_addr2" class="frm_input frm_address full_input" size="50">
	                <label for="mb_work_addr2" class="sound_only">상세주소</label>
					</div>
	            </li>
				<li>
	                <label for="mb_work_tel"><span class="required_mark">*</span>근무지 전화번호<span class="eng_title">(Workplace Phone Number)</span></label>
					<div class="input_inner number_input">
						<input type="text" name="mb_work_tel1" id="mb_work_tel1" value="<?php echo explode('-', get_text($member['mb_work_tel']))[0] ?? '' ?>" maxlength="4" required>
						<span>-</span>
						<input type="text" name="mb_work_tel2" id="mb_work_tel2" value="<?php echo explode('-', get_text($member['mb_work_tel']))[1] ?? '' ?>" maxlength="4" required>
						<span>-</span>
						<input type="text" name="mb_work_tel3" id="mb_work_tel3" value="<?php echo explode('-', get_text($member['mb_work_tel']))[2] ?? '' ?>" maxlength="4" required>
						<input type="hidden" name="mb_work_tel" id="mb_work_tel">
					</div>
	            </li>
                <li class="align">
	                <label for="">재직증명서<span class="eng_title">(Certificate of Employment)</span></label>
					<div class="input_inner flex">


<?php
  // 기존 파일들 표시
$existing_files = array();
if ($w == 'u') {
      for ($i = 1; $i <= 3; $i++) {
          $file_field = "mb_certi_file{$i}";
          if (!empty($member[$file_field])) {
              $existing_files[$i] = $member[$file_field];
          }
      }
}
?>
<div class="file-upload-container" id="fileUploadContainer">
<?php
for ($zz = 1; $zz <= count($existing_files); $zz++) {
?>
  <div class="file-upload-group">
    <label class="custom-file-button">
      File
      <input type="file" name="mb_certi_file<?=$zz?>" onchange="updateFileName(this)" accept=".jpg,.jpeg,.png,.pdf" />
    </label>

    <?php if (empty($existing_files[$zz])) { ?>
      <span class="file-display">No file selected</span>
    <?php } else { ?>
      <!-- 파일명만 표시 -->
      <span class="file-display"><?= basename($existing_files[$zz]) ?></span>
      <!-- '삭제'만 표시되는 체크박스 -->
      <label class="delete-check">
        Delete <input type="checkbox" name="mb_certi_file<?=$zz?>_del" value="1">
      </label>
    <?php } ?>
  </div>
<?php
}
?>

<?php if ($w == "") { ?>
  <div class="file-upload-group">
    <label class="custom-file-button">
      File
      <input type="file" name="mb_certi_file1" onchange="updateFileName(this)" accept=".jpg,.jpeg,.png,.pdf" />
    </label>
    <span class="file-display">No file selected</span>
  </div>
<?php } ?>
</div>

<button type="button" class="add-button" id="addFileButton" onclick="addFileInput()">+</button>
						<span>5MB 이하의 jpg, png, PDF 파일 3개까지 업로드 가능합니다.</span>
					</div>
	            </li>
	        </ul>
	    </div>

		<h3>동의 여부<span class="eng_title3">(Consent)</span></h3>

	    <?php
		if($w==""){
		?>
		<div class="tbl_frm01 tbl_wrap register_form_inner">
	        <ul>
				<li class="chk_box">
		            <label for="">
						<span class="required_mark">*</span>
						회원 검색 동의<span class="eng_title">(Consent to Member Search)</span>
		            </label>
					<div class="input_inner">
						<h6>회원 성명, 소속, 회원등급, 근무처 주소가 회원병원 검색 메뉴에서 노출됩니다.<span class="eng_title">(Your name, affiliation, membership level, and workplace address will appear in the member hospital search.)</span></h6>
						<input type="radio" id="search_agree_yes" name="mb_search_agree" value="동의" <?php echo ($member['mb_search_agree']=='동의')?'checked':''; ?> required>
						<label for="search_agree_yes">동의<span class="eng_title2">(Agree)</span></label>
						<input type="radio" id="search_agree_no" name="mb_search_agree" value="동의하지 않음" class="margin" <?php echo ($member['mb_search_agree']=='동의하지 않음')?'checked':''; ?>>
						<label for="search_agree_no">동의하지 않음<span class="eng_title2">(Disagree)</span></label>
					</div>
		        </li>
	            <li class="chk_box">
		            <label for="">
						<span class="required_mark">*</span>
						안내 메일 수신 동의<span class="eng_title">(Consent to Receive Email Notifications)</span>
		            </label>
					<div class="input_inner">
						<h6>대한치과이식임플란트학회에서 발생하는 안내 메일을 수신합니다.<br> 수신 거부가 가능하며, 거부 시 학회에서 발송하는 메일을 수신할 수 없습니다.
                    <span class="eng_title">(I agree to receive email updates from the Korean Academy of Implant Dentistry.<br>  You can opt out at any time, but will no longer receive emails from the academy.)</span></h6>
						<input type="radio" id="mail_agree_yes" name="mb_mailling" value="1" <?php echo ($member['mb_mailling']=='1')?'checked':''; ?> required>
						<label for="mail_agree_yes">동의<span class="eng_title2">(Agree)</span></label>
						<input type="radio" id="mail_agree_no" name="mb_mailling" value="0" class="margin" <?php echo ($member['mb_mailling']=='0')?'checked':''; ?>>
						<label for="mail_agree_no">동의하지 않음<span class="eng_title2">(Disagree)</span></label>
					</div>
		        </li>

				<li class="chk_box">
		            <label for="">
						<span class="required_mark">*</span>
						SMS 수신 동의<span class="eng_title">(Consent to Receive SMS)</span>
		            </label>
					<div class="input_inner">
						<h6>대한치과이식임플란트학회에서 발생하는 SMS를 수신합니다.<br> 수신 거부가 가능하며, 거부 시 학회에서 발송하는 SMS를 수신할 수 없습니다. <span class="eng_title">(I agree to receive SMS messages from the Korean Academy of Implant Dentistry. <br> You may opt out, but will not receive any SMS from the Academy.)</span></h6>
						<input type="radio" id="sms_agree_yes" name="mb_sms" value="1" <?php echo ($member['mb_sms']=='1')?'checked':''; ?> required>
						<label for="sms_agree_yes">동의<span class="eng_title2">(Agree)</span></label>
						<input type="radio" id="sms_agree_no" name="mb_sms" value="0" class="margin" <?php echo ($member['mb_sms']=='0')?'checked':''; ?>>
						<label for="sms_agree_no">동의하지 않음<span class="eng_title2">(Disagree)</span></label>
					</div>
		        </li>

				<li class="chk_box">
		            <label for="">
						<span class="required_mark">*</span>
						고유식별번호 수집 동의<span class="eng_title">(Consent to Collection of Unique Identifier)</span>
		            </label>
					<div class="input_inner">
						<h6>대한치과이식임플란트학회가 회비관리 및 학회 업무 처리를 위해 본인의 고유식별정보를 수집 이용하는 것에 동의합니다.
                        <span class="eng_title">(I agree to the collection and use of my personal identifier by the Academy for membership and administrative purposes.)</span>
                        </h6>
						<input type="radio" id="privacy_agree_yes" name="mb_privacy_agree" value="동의" <?php echo ($member['mb_privacy_agree']=='동의')?'checked':''; ?> required>
						<label for="privacy_agree_yes">동의<span class="eng_title2">(Agree)</span></label>
						<input type="radio" id="privacy_agree_no" name="mb_privacy_agree" value="동의하지 않음" class="margin" <?php echo ($member['mb_privacy_agree']=='동의하지 않음')?'checked':''; ?>>
						<label for="privacy_agree_no">동의하지 않음<span class="eng_title2">(Disagree)</span></label>
						<span class="color_r">*동의하지 않을 경우 회원가입이 불가합니다.<span class="eng_title">(Membership registration is not possible without consent.)</span></span>
					</div>
		        </li>
			</ul>
			<div class="agree_all">
				 <input type="checkbox" id="agree_all" name="agree_all" value="1">
				 <label for="agree_all">필수동의 항목 및 광고성 정보 수신(선택)에 모두 동의합니다. <span class="eng_title">(I agree to all required terms and optional promotional communications.)</span></label>
			</div>
	    </div>
		<?php 
		}
		?>
	       
	    <div class="tbl_frm01 tbl_wrap register_form_inner">
	        <ul>
	            <li class="is_captcha_use">
					<label for=""><span class="required_mark">*</span>자동화 프로그램<br> 입력 방지<span class="eng_title">(CAPTCHA / Anti-bot Verification)</span></label>
					<div class="input_inner">
						<?php echo captcha_html(); ?>
					</div>
	            </li>
	        </ul>
	    </div>
		<?php if($w==""){?>
		<h3>회비 금액 안내<span class="eng_title3">(Membership Fee Information)</span></h3>
		<div class="tbl_frm01 tbl_wrap register_form_inner">
	        <ul>
	            <li class="">
					<label for="">학회 회비<span class="eng_title">(Academy Membership Fees)</span></label>
					<div class="input_inner">
						<h4>치과의사, 기공사, 간호사 120,000원 (※ 비용 동일) / 학생 및 전공의 60,000원<span class="eng_title">(Dentists, Technicians, Nurses: 120,000 KRW (Same Fee) / Students & Residents: 60,000 KRW)</span></h4>
					</div>
	            </li>
	            <li class="">
					<label for="">안내<span class="eng_title">(Notice)</span></label>
					<div class="input_inner">
						<h4>가입 완료 후 마이페이지에서 결제 하셔야 정상적으로 홈페이지 이용이 가능합니다.<span class="eng_title">(You must complete payment via My Page after registration to access full website features.)</span></h4>
					</div>
	            </li>
	        </ul>
	    </div>
		<?php }?>

	</div>
	<div class="btn_confirm">
	    <a href="<?php echo G5_URL ?>" class="btn_close">취소(Cancel)</a>
	    <button type="submit" id="btn_submit" class="btn_submit" accesskey="s"><?php echo $w==''?'회원가입(Sign Up)':'정보수정(Edit Info)'; ?></button>
	</div>
	</form>
</div>
</div>

<script>
$(function() {
    $("#reg_zip_find").css("display", "inline-block");
});

// 회원구분에 따른 직군 선택 영역 표시/숨김
$(document).ready(function() {
	$(".id_check").click(function(){
		var msg = reg_mb_id_check();

		if(msg == "" || msg == null){
			// 중복된 아이디가 존재하지 않는다.
			if(!confirm("가입할 수 있는 아이디입니다.\n현재 아이디를 사용하시겠습니까?")){
				document.getElementById("reg_mb_id").value = "";
			}
		}
		else
		{
			// 중복된 아이디가 존재한다.
			alert(msg);
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
    
    // 면허번호 없음 체크시 면허번호 입력칸 비활성화
    $('#mb_license_none').change(function() {
        if ($(this).is(':checked')) {
            $('#mb_license_no').val('').prop('disabled', true);
        } else {
            $('#mb_license_no').prop('disabled', false);
        }
    });
    
    // 근무지 전화번호 합치기
    $('input[name^="mb_work_tel"]').blur(function() {
        var tel1 = $('#mb_work_tel1').val();
        var tel2 = $('#mb_work_tel2').val();
        var tel3 = $('#mb_work_tel3').val();
        
        if (tel1 && tel2 && tel3) {
            $('#mb_work_tel').val(tel1 + '-' + tel2 + '-' + tel3);
        }
    });
	$("#mb_location_type").on("change", function(){
		if($(this).val()=="domestic"){
			$(".domestic").show();
			$(".international").hide();
		}else if($(this).val()=="international"){
			$(".domestic").hide();
			$(".international").show();
		}
	});
<?php
if($w==""){
?>
$(".domestic").hide();
$(".international").hide();
<?php
}else{
	if($member['mb_location_type']=="domestic"){
?>
$(".domestic").show();
$(".international").hide();
<?
	}else if($member['mb_location_type']=="international"){
?>
$(".domestic").hide();
$(".international").show();
<?
	}
}
?>
});

// 파일 업로드 관련 스크립트
let fileCount = 1;
const maxFiles = 3;

function updateFileName(input) {
    const displaySpan = input.closest('.file-upload-group').querySelector('.file-display');
    if (input.files.length > 0) {
        displaySpan.textContent = input.files[0].name;
    } else {
        displaySpan.textContent = "선택된 파일이 없음";
    }
}

function addFileInput() {
    if (fileCount >= maxFiles) return;

    const container = document.getElementById('fileUploadContainer');
    const group = document.createElement('div');
    group.className = 'file-upload-group';
    
    fileCount++;
    group.innerHTML = `
        <label class="custom-file-button">
            File
            <input type="file" name="mb_certi_file${fileCount}" onchange="updateFileName(this)" accept=".jpg,.jpeg,.png,.pdf" />
        </label>
        <span class="file-display">No file selected</span>
    `;
    container.appendChild(group);

    if (fileCount >= maxFiles) {
        document.getElementById('addFileButton').disabled = true;
    }
}

// 모두 동의 체크박스
document.addEventListener('DOMContentLoaded', function () {
    const agreeAllCheckbox = document.getElementById('agree_all');

    // 각 동의 항목의 라디오 버튼 (value=동의)
    const requiredRadios = [
        document.getElementById('search_agree_yes'),
        document.getElementById('mail_agree_yes'),
        document.getElementById('sms_agree_yes'),
        document.getElementById('privacy_agree_yes')
    ];

    const allRadioNames = [
        'mb_search_agree',
        'mb_mailling',
        'mb_sms',
        'mb_privacy_agree'
    ];

    // 모두 동의 체크 시 각 항목을 '동의'로 설정
    agreeAllCheckbox.addEventListener('change', function () {
        if (agreeAllCheckbox.checked) {
            requiredRadios.forEach(radio => {
                radio.checked = true;
            });
        }
    });

    // 개별 동의 항목 변경 시 전체 동의 상태 갱신
    allRadioNames.forEach(name => {
        const radios = document.querySelectorAll(`input[name="${name}"]`);
        radios.forEach(radio => {
            radio.addEventListener('change', updateAgreeAllStatus);
        });
    });

    function updateAgreeAllStatus() {
        const allAgreed = requiredRadios.every(radio => radio.checked);
        agreeAllCheckbox.checked = allAgreed;
    }

    // 초기 로드시 상태 맞추기
    updateAgreeAllStatus();
});


// 각 동의 항목 변경시 모두 동의 체크박스 상태 업데이트
$('input[name^="mb_"][name$="_agree"]').change(function() {
    const agreeGroups = ['mb_search_agree', 'mb_mail_agree', 'mb_sms_agree', 'mb_privacy_agree'];
    const allAgreed = agreeGroups.every(groupName => {
        const yesRadio = document.querySelector(`input[name="${groupName}"][value="동의"]`);
        return yesRadio && yesRadio.checked;
    });
    
    document.getElementById('agree_all').checked = allAgreed;
});

// select box 상태 관리

document.addEventListener('DOMContentLoaded', function () {
    // 전공 기타 처리
    const majorSelect = document.getElementById('mb_major');
    const majorEtcInput = document.getElementById('mb_major_etc');

    if (majorSelect && majorEtcInput) {
        majorSelect.addEventListener('change', function () {
            if (majorSelect.value === '기타(전공)') {
                majorEtcInput.disabled = false;
                majorEtcInput.focus();
            } else {
                majorEtcInput.disabled = true;
                majorEtcInput.value = '';
            }
        });

        // 페이지 로드시 기타(전공)이 선택되어 있으면 활성화
        if (majorSelect.value === '기타(전공)') {
            majorEtcInput.disabled = false;
        }
    }

    // 국내 학교 기타 처리
    const schoolDomesticSelect = document.getElementById('mb_school');
    const schoolDomesticEtcInput = document.getElementById('mb_school_domestic_etc');

    if (schoolDomesticSelect && schoolDomesticEtcInput) {
        schoolDomesticSelect.addEventListener('change', function () {
            if (schoolDomesticSelect.value === '기타') {
                schoolDomesticEtcInput.disabled = false;
                schoolDomesticEtcInput.focus();
            } else {
                schoolDomesticEtcInput.disabled = true;
                schoolDomesticEtcInput.value = '';
            }
        });

        if (schoolDomesticSelect.value === '기타') {
            schoolDomesticEtcInput.disabled = false;
        }
    }

    // 국외 학교 기타 처리
    const schoolForeignSelect = document.getElementById('mb_school_etc');
    const schoolForeignEtcInput = document.getElementById('mb_school_foreign_etc');

    if (schoolForeignSelect && schoolForeignEtcInput) {
        schoolForeignSelect.addEventListener('change', function () {
            if (schoolForeignSelect.value === '기타') {
                schoolForeignEtcInput.disabled = false;
                schoolForeignEtcInput.focus();
            } else {
                schoolForeignEtcInput.disabled = true;
                schoolForeignEtcInput.value = '';
            }
        });

        if (schoolForeignSelect.value === '기타') {
            schoolForeignEtcInput.disabled = false;
        }
    }
});


// $(document).ready(function () {
//     function toggleSchoolSelects() {
//         const type = $('#mb_location_type').val();

//         // 모든 항목 초기화
//         $('.domestic, .international').hide();
//         $('#mb_school, #mb_school_etc').prop('disabled', true).prop('required', false);

//         if (type === 'domestic') {
//             $('.domestic').show();
//             $('#mb_school').prop('disabled', false).prop('required', true); // ✅ 국내 필수
//         } else if (type === 'international') {
//             $('.international').show();
//             $('#mb_school_etc').prop('disabled', false).prop('required', true); // ✅ 국외 필수
//         }
//     }

//     // 기타 항목 입력란 토글
//     $('#mb_school').on('change', function () {
//         $('#mb_school_domestic_etc').prop('disabled', $(this).val() !== '기타');
//     });

//     $('#mb_school_etc').on('change', function () {
//         $('#mb_school_foreign_etc').prop('disabled', $(this).val() !== '기타');
//     });

//     // location 선택 시 학교 필드 토글
//     $('#mb_location_type').on('change', function () {
//         toggleSchoolSelects();
//     });

//     // 페이지 로딩 시 초기 세팅 (수정 화면 대비)
//     toggleSchoolSelects();
// });







// 석/박사 졸업 행 추가 함수
function addGraduateRow() {
    var tbody = $('#graduate_tbody');
	var newRow = `
                        <div class="items">
                            <div class="item">
								<div class="item_title right_border"><h2>학위</h2></div>
                                <div class="item_contents right_border">
                                    <select name="graduate_degree[]" class="custom-select">
										<option value="">선택</option>
										<option value="석사" >석사</option>
										<option value="박사" >박사</option>
								    </select>
                                </div>
                            </div>
                            <div class="item">
								<div class="item_title right_border"><h2>학교 및 전공</h2></div>
                                <div class="item_contents right_border">
                                    <input type="text" name="graduate_school_major[]" value="" class="frm_input" placeholder="예) 하버대학교 치아보철과">
                                </div>
                            </div>

                            <div class="item">
								<div class="item_title right_border"><h2>입학년도</h2></div>
                                <div class="item_contents right_border" >
                                    <select name="graduate_admission_year[]" class="custom-select">
										<option value="">선택</option>`;
    for (var year = 1960; year <= new Date().getFullYear(); year++) {
        newRow += `<option value="${year}">${year}</option>`;
    }
	newRow += `
									</select>
                                </div>
                            </div>

                            <div class="item">
								<div class="item_title right_border"><h2>졸업년도</h2></div>
                                <div class="item_contents right_border">
                                    <select name="graduate_graduation_year[]" class="custom-select">
										<option value="">선택</option>`;
    for (var year = 1964; year <= new Date().getFullYear() + 6; year++) {
        newRow += `<option value="${year}">${year}</option>`;
    }
	newRow += `
									</select>
                                </div>
                            </div>

                            <div class="item border_coustom">
								<div class="item_title "><h2>관리</h2></div>
                                    <div class="item_contents">
										<button type="button" class="btn_remove" onclick="removeGraduateRow(this)">삭제</button>
                                    </div>
                            </div>
						</div>
						`;
    
    tbody.append(newRow);
}

// 석/박사 졸업 행 삭제 함수
function removeGraduateRow(button) {
    $(button).closest('.items').remove();
}

// submit 최종 폼체크
function fregisterform_submit(f) {
    // 회원구분 체크
    if (!f.mb_memclass.value) {
        alert("회원구분을 선택해주세요.");
        return false;
    }
    
    // 호칭 체크
    if (!f.mb_title.value) {
        alert("호칭을 선택해주세요.");
        return false;
    }
    
    // 국문 성명 체크
    if (f.mb_name.value.length < 2) {
        alert("국문 성명을 2글자 이상 입력해주세요.");
        f.mb_name.focus();
        return false;
    }
    
    // 영문 성명 체크
    if (f.mb_name_en.value.length < 2) {
        alert("영문 성명을 2글자 이상 입력해주세요.");
        f.mb_name_en.focus();
        return false;
    }

    // 회원아이디 검사
	/**
    if (f.w.value == "") {
        var msg = reg_mb_id_check();
        if (msg) {
            alert(msg);
            f.mb_id.select();
            return false;
        }
    }
	*/

    if (f.w.value == "") {
        if (f.mb_password.value.length < 3) {
            alert("비밀번호를 3글자 이상 입력하십시오.");
            f.mb_password.focus();
            return false;
        }
    }

    if (f.mb_password.value != f.mb_password_re.value) {
        alert("비밀번호가 같지 않습니다.");
        f.mb_password_re.focus();
        return false;
    }

    // 생년월일 체크
    if (!f.mb_birth.value) {
        alert("생년월일을 입력해주세요.");
        f.mb_birth.focus();
        return false;
    }
    
    // 성별 체크
    if (!f.mb_sex.value) {
        alert("성별을 선택해주세요.");
        return false;
    }
    
    // 면허번호 체크
    if (!f.mb_license_none.checked && !f.mb_license_no.value) {
        alert("면허번호를 입력하거나 '면허번호 없음'을 체크해주세요.");
        f.mb_license_no.focus();
        return false;
    }

    // E-mail 검사
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
        var msg = reg_mb_email_check();
        if (msg) {
            alert(msg);
            f.reg_mb_email.select();
            return false;
        }
    }
    
    // 휴대전화 체크
    if (!f.mb_hp.value) {
        alert("휴대전화 번호를 입력해주세요.");
        f.mb_hp.focus();
        return false;
    }
    
    // 직군 체크
    if (f.mb_memclass.value === 'member' && !f.mb_job_class.value) {
        alert("직군을 선택해주세요.");
        return false;
    }
    
    if (f.mb_memclass.value === 'student' && !f.mb_student_class.value) {
        alert("학생구분을 선택해주세요.");
        return false;
    }
    
    // 출신학교 체크
    // if (!f.mb_school.value) {
    //     alert("출신학교를 선택해주세요.");
    //     f.mb_school.focus();
    //     return false;
    // }
	if(f.mb_location_type.value==""){
		alert("출신학교(학사)를 선택해 주세요.");
		return false;
	}
	if(f.mb_location_type.value=="domestic"){
		if(f.mb_school.value==""){
			alert("국내학교(학사)를 선택해 주세요.");
			return false;
		}
	}else if(f.mb_location_type.value=="international"){
		if(f.mb_school_etc.value==""){
			alert("국외학교(학사)를 선택해 주세요.");
			return false;
		}
	}

    
    // 전공과목 체크
    if (!f.mb_major.value) {
        alert("전공과목을 선택해주세요.");
        f.mb_major.focus();
        return false;
    }
    
    // 전공과목 기타 입력 체크
    if (f.mb_major.value === '기타(전공)' && !f.mb_major_etc.value) {
        alert("전공과목 기타 내용을 입력해주세요.");
        f.mb_major_etc.focus();
        return false;
    }
    
    // 분과 체크
    if (!f.mb_branch.value) {
        alert("분과를 선택해주세요.");
        f.mb_branch.focus();
        return false;
    }
    
    // 근무지명 체크
    if (!f.mb_work_name.value) {
        alert("근무지명을 입력해주세요.");
        f.mb_work_name.focus();
        return false;
    }
    
    // 근무지 주소 체크
    if (!f.mb_work_zip.value) {
        alert("근무지 우편번호를 입력해주세요.");
        f.mb_work_zip.focus();
        return false;
    }
    
    if (!f.mb_work_addr1.value) {
        alert("근무지 기본주소를 입력해주세요.");
        f.mb_work_addr1.focus();
        return false;
    }
    
    // 근무지 전화번호 체크
    if (!f.mb_work_tel1.value || !f.mb_work_tel2.value || !f.mb_work_tel3.value) {
        alert("근무지 전화번호를 모두 입력해주세요.");
        return false;
    }
    
    if(f.w.value == ""){
		// 동의 여부 체크
		if (!f.mb_search_agree.value) {
			alert("회원 검색 동의 여부를 선택해주세요.");
			return false;
		}
		
		if (!f.mb_mailling.value) {
			alert("안내 메일 수신 동의 여부를 선택해주세요.");
			return false;
		}
		
		if (!f.mb_sms.value) {
			alert("SMS 수신 동의 여부를 선택해주세요.");
			return false;
		}
		
		if (!f.mb_privacy_agree.value) {
			alert("고유식별번호 수집 동의 여부를 선택해주세요.");
			return false;
		}

    
		if (f.mb_privacy_agree.value === '동의하지 않음') {
			alert("고유식별번호 수집에 동의하지 않으면 회원가입이 불가합니다.");
			return false;
		}
	}

    <?php echo chk_captcha_js(); ?>

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

jQuery(function($){
    //tooltip
    $(document).on("click", ".tooltip_icon", function(e){
        $(this).next(".tooltip").fadeIn(400).css("display","inline-block");
    }).on("mouseout", ".tooltip_icon", function(e){
        $(this).next(".tooltip").fadeOut();
    });
});
</script>

<script>
/**
  document.addEventListener('DOMContentLoaded', function () {
    const schoolSelect = document.getElementById('school_select');
    const schoolSelect2 = document.getElementById('school_select2');
    const departmentSelect = document.getElementById('department_select');
    const etcInput = document.getElementById('department_etc_input');
    const branchSelect = document.getElementById('branch_select');

    function handleChange(e) {
      if (e.target.value) {
        e.target.classList.add('has-value');
      } else {
        e.target.classList.remove('has-value');
      }

      if (e.target.id === 'department_select') {
        if (e.target.value === '기타(전공)') {
          etcInput.disabled = false;
          etcInput.focus();
        } else {
          etcInput.disabled = true;
          etcInput.value = '';
        }
      }
    }

    schoolSelect.addEventListener('change', handleChange);
    schoolSelect2.addEventListener('change', handleChange);
    departmentSelect.addEventListener('change', handleChange);
    branchSelect.addEventListener('change', handleChange);
  });
*/
</script>


<!-- } 회원정보 입력/수정 끝 -->
