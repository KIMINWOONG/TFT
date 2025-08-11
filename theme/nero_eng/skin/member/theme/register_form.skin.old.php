<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/info.css">', 0);
?>

<!-- 회원정보 입력/수정 시작 { -->

<div class="register width common">
        <h2>회원가입</h2>
    <div class="register_step">
        <div class="on1">
        <div class="item">
            <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon1.png" alt="">
            <p>약관 동의</p>
        </div>
        <div class="item">
            <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon4.png" alt="">
            <p>회원 정보</p>
        </div>
        </div>
        <div class="item">
            <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon3.png" alt="">
            <p>가입 완료</p>
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
						<span class="required_mark">*</span>회원구분
					</label>
					<div class="input_inner">
						<input type="radio" id="member" name="memclass" value="member">
						<label for="member">Member</label>
						<input type="radio" id="student" name="memclass" value="student" class="margin">
						<label for="student">Student</label>
					</div>
					
				</li>
				<li>
					<label for="" class="padding">
						<span class="required_mark">*</span>성명
					</label>
					<div class="input_inner">
						<p>기존 Title : Dr.</p>
						<div class="radio_wrap">
						<input type="radio" id="dr" name="title" value="dr">
						<label for="dr">Dr.</label>
						<input type="radio" id="prof" name="title" value="prof" class="margin">
						<label for="prof">Prof.</label>
						<input type="radio" id="ms" name="title" value="ms" class="margin">
						<label for="ms">Ms.</label>
						<input type="radio" id="mr" name="title" value="mr" class="margin">
						<label for="mr">Mr.</label>
						</div>
						<div class="name_input_wrap">
							<span>국문</span>
							<input type="text" name="" value="" id="" class="" minlength="3" maxlength="20" placeholder="예)홍길동">
							<span>영문</span>
							<input type="text" name="" value="" id="" class="" minlength="3" maxlength="20" placeholder="예) KILDONG HONG">
						</div>
						
					</div>
					
				</li>
	            <li>
	                <label for="reg_mb_id"><span class="required_mark">*</span>
	                	아이디
	                </label>
					<div class="input_inner">
						<input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="frm_input full_input <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20" >
	                <span id="msg_mb_id"></span>

					<button class="id_check">중복 확인</button>
					</div>
	                
	            </li>
	            <li class="">
	                <label for="reg_mb_password"><span class="required_mark">*</span>비밀번호<strong class="sound_only">필수</strong></label>
					<div class="input_inner">
	                <input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" >
					</div>
				</li>
	            <li class="">
	                <label for="reg_mb_password_re"><span class="required_mark">*</span>비밀번호 확인<strong class="sound_only">필수</strong></label>
					<div class="input_inner">
	                <input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" >
					</div>
	            </li>
				<li>
	                <label for=""><span class="required_mark">*</span>생년월일</label>
					<div class="input_inner">
	                <input type="date" name="" id="" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" >
					</div>
	            </li>
				<li>
					<label for="">
						<span class="required_mark">*</span>성별
					</label>
					<div class="input_inner">
						<input type="radio" id="male" name="gender" value="남자">
						<label for="male">남자</label>
						<input type="radio" id="female" name="gender" value="여자" class="margin">
						<label for="female">여자</label>
					</div>
					
				</li>
				<li class="">
	                <label for="reg_mb_password"><span class="required_mark">*</span>면허번호</label>
					<div class="input_inner">
	                <input type="text" name="" id=""  class="frm_input full_input" minlength="3" maxlength="20" >
					<input type="checkbox" name="" id="" minlength="3" maxlength="20" class="check_box">면허번호 없음
					</div>
				</li>
				<li>
	                <label for="reg_mb_email"><span class="required_mark">*</span>이메일</label>
					<div class="input_inner">
	                <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
	                <input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="frm_input email full_input required" size="70" maxlength="100" >
					</div>
	            </li>
				<li>
	                <label for=""><span class="required_mark">*</span>휴대전화</label>
					<div class="input_inner number_input">
						<select name="" id="">
							<option value="">국가선택</option>
							<option value="">국가선택</option>
							<option value="">국가선택</option>
							<option value="">국가선택</option>
						</select>
						<select name="" id="">
							<option value="">선택</option>
						</select>
						<span>-</span>
						<input type="text"><span>-</span><input type="text">
					</div>
	            </li>
				<li>
	                <label for=""><span class="required_mark">*</span>직군</label>

                    <!-- 초기 상태 -->
					<div class="input_inner basic">
                        
						<!-- <input type="checkbox" id="memberType"> -->
						<label for="memberType">회원구분을 선택해 주세요</label>
						
						<h5>직군에 따라 회비 금액이 상이하게 책정됩니다.</h5>
					</div>

                    <!-- member선택했을때 -->
					<!-- <div class="input_inner sel_mem">
                        
						<input type="radio" id="" name="mem_class" value="치과의사">
						<label for="">치과의사</label>
						<input type="radio" id="" name="mem_class" value="기공사" class="margin">
						<label for="">기공사</label>
						<input type="radio" id="" name="mem_class" value="간호사" class="margin">
						<label for="">간호사</label>
						
						<h5>직군에 따라 회비 금액이 상이하게 책정됩니다.</h5>
					</div> -->

                    <!-- student선택했을때 -->
					<!-- <div class="input_inner sel_stu">
                        
						<input type="radio" id="" name="stu_class" value="학생">
						<label for="">학생</label>
						<input type="radio" id="" name="stu_class" value="전공의" class="margin">
						<label for="">전공의</label>
						
						<h5>직군에 따라 회비 금액이 상이하게 책정됩니다.</h5>
					</div> -->
	            </li>
			</ul>
	    </div>

				
	<div id="register_form" class="form_01">

	    <div class="tbl_frm01 tbl_wrap register_form_inner">

	        <ul>
	            <li class="align">
	                <label for=""><span class="required_mark">*</span>재직증명서</label>
					<div class="input_inner flex">
<div class="file-upload-container" id="fileUploadContainer">
  <!-- 최초 파일 입력 그룹 -->
  <div class="file-upload-group">
    <label class="custom-file-button">
      파일 선택
      <input type="file" onchange="updateFileName(this)" />
    </label>
    <span class="file-display">선택된 파일이 없음</span>
  </div>
</div>

<button type="button" class="add-button" id="addFileButton" onclick="addFileInput()">+</button>


						<span>5MB 이하의 jpg, png, PDF 파일 3까지 업로드 가능합니다.</span>

					</div>


					 <script>
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
    group.innerHTML = `
      <label class="custom-file-button">
        파일 선택
        <input type="file" onchange="updateFileName(this)" />
      </label>
      <span class="file-display">선택된 파일이 없음</span>
    `;
    container.appendChild(group);

    fileCount++;
    if (fileCount >= maxFiles) {
      document.getElementById('addFileButton').disabled = true;
    }
  }
</script>


	            </li>
				<li>
	                <label for="school_select"><span class="required_mark">*</span>출신학교(학사)</label>
					<div class="input_inner number_input number_input2">
	      				<select id="school_select" id=""class="custom-select">
                            <option value="" disabled selected hidden>선택</option>
							<option value="">선택</option>
							<option value="경희대학교 치과대학">경희대학교 치과대학</option>
							<option value="서울대학교 치의학대학원">서울대학교 치의학대학원</option>
							<option value="연세대학교 치과대학">연세대학교 치과대학</option>
							<option value="강릉원주대학교 치과대학">강릉원주대학교 치과대학</option>
							<option value="단국대학교 치과대학">단국대학교 치과대학</option>
							<option value="경북대학교 치과대학">경북대학교 치과대학</option>
							<option value="부산대학교 치의학전문대학원">부산대학교 치의학전문대학원</option>
							<option value="전남대학교 치의학전문대학원">전남대학교 치의학전문대학원</option>
							<option value="조선대학교 치과대학">조선대학교 치과대학</option>
							<option value="원광대학교 치과대학">원광대학교 치과대학</option>
							<option value="전북대학교 치과대학">전북대학교 치과대학</option>
						</select>
					</div>
	            </li>

				<li>
  <label for="department_select"><span class="required_mark">*</span>전공과목</label>
  <div class="input_inner number_input number_input2">
    <select id="department_select" class="custom-select">
      <option value="" disabled selected hidden>선택</option>
      <option value="치과보철학">치과보철학</option>
      <option value="소아치과학">소아치과학</option>
      <option value="치과교정학">치과교정학</option>
      <option value="치과재료학">치과재료학</option>
      <option value="구강생물학">구강생물학</option>
      <option value="영상치의학">영상치의학</option>
      <option value="구강내과학">구강내과학</option>
      <option value="구강병리학">구강병리학</option>
      <option value="치주과학">치주과학</option>
      <option value="구강보건학">구강보건학</option>
      <option value="구강악안면외과학">구강악안면외과학</option>
      <option value="치과보존학">치과보존학</option>
      <option value="통합치의학">통합치의학</option>
      <option value="전공분야없음">전공분야없음</option>
      <option value="기타(전공)">기타(전공)</option>
    </select>
    <input type="text" id="department_etc_input" placeholder="기타" disabled>
  </div>
</li>




				<li>
	                <label for="branch_select"><span class="required_mark">*</span>분과</label>
					<div class="input_inner number_input number_input2">
	      				<select  class="custom-select" id="branch_select">
                            <option value="" disabled selected hidden>선택</option>
							<option value="구강외과 분과">구강외과 분과</option>
							<option value="보철 분과">보철 분과</option>
							<option value="치주 분과">치주 분과</option>
							<option value="연구 분과">연구 분과</option>
							<option value="영상 및 AI 분과">영상 및 AI 분과</option>
							<option value="통합치의학 및 장애인치과 분과">통합치의학 및 장애인치과 분과</option>
						</select>
					</div>
	            </li>
 
				<li>
	                <label for="school_select2"><span class="required_mark">*</span>기타학교(학사)</label>
					<div class="input_inner number_input number_input2">
	      				<select  class="custom-select" id="school_select2">
                            <option value="" disabled selected hidden>선택</option>
							<option value="University of Michigan-Ann Arbor">University of Michigan-Ann Arbor</option>
							<option value="Academic Centre for Dentistry Amsterdam (ACTA)">Academic Centre for Dentistry Amsterdam (ACTA)</option>
							<option value="The University of Hong Kong">The University of Hong Kong</option>
							<option value="King's College London">King's College London</option>
							<option value="Tokyo Medical and Dental University">Tokyo Medical and Dental University</option>
							<option value="University of Gothenburg">University of Gothenburg</option>
							<option value="Harvard University">Harvard University</option>
							<option value="University of Bern">University of Bern</option>
							<option value="University of São Paulo">University of São Paulo</option>
							<option value="University of British Columbia">University of British Columbia</option>
							<option value="University of North Carolina, Chapel Hill">University of North Carolina, Chapel Hill</option>
							<option value="University of Zurich">University of Zurich</option>
							<option value="University of Oslo">University of Oslo</option>
							<option value="University of Otago">University of Otago</option>
							<option value="University of Copenhagen">University of Copenhagen</option>
							<option value="University of California, San Francisco">University of California, San Francisco</option>
							<option value="University of Leeds">University of Leeds</option>
							<option value="University of Birmingham">University of Birmingham</option>
							<option value="University of Minnesota">University of Minnesota</option>
							<option value="University of Basel">University of Basel</option>
							<option value="University of Glasgow">University of Glasgow</option>
							<option value="Seoul National University">Seoul National University</option>
							<option value="University of Alberta">University of Alberta</option>
							<option value="University of Melbourne">University of Melbourne</option>
							<option value="Universidade Estadual de Campinas (Unicamp)">Universidade Estadual de Campinas (Unicamp)</option>
							<option value="University of Dundee">University of Dundee</option>
							<option value="McGill University">McGill University</option>
							<option value="University of Sydney">University of Sydney</option>
							<option value="University of Adelaide">University of Adelaide</option>
							<option value="University of Iowa">University of Iowa</option>
							<option value="University of Manchester">University of Manchester</option>
							<option value="University of Helsinki">University of Helsinki</option>
							<option value="University of Sheffield">University of Sheffield</option>
							<option value="University of Milan">University of Milan</option>
							<option value="University of Pennsylvania">University of Pennsylvania</option>
							<option value="University of Lisbon">University of Lisbon</option>
							<option value="University of Florida">University of Florida</option>
							<option value="University of Geneva">University of Geneva</option>
							<option value="University of Toronto">University of Toronto</option>
							<option value="Universidad Complutense de Madrid">Universidad Complutense de Madrid</option>
							<option value="New York University (NYU)">New York University (NYU)</option>
							<option value="Universidade Federal do Rio Grande do Sul (UFRGS)">Universidade Federal do Rio Grande do Sul (UFRGS)</option>
							<option value="Universidade Federal de Minas Gerais">Universidade Federal de Minas Gerais</option>
							<option value="University of Alberta">University of Alberta</option>
							<option value="University of Bristol">University of Bristol</option>
							<option value="University of Western Australia">University of Western Australia</option>
							<option value="Università di Bologna">Università di Bologna</option>
							<option value="University of Naples Federico II">University of Naples Federico II</option>
							<option value="University of Athens">University of Athens</option>
							<option value="University of Padua">University of Padua</option>
							<option value="Yonsei University">Yonsei University</option>
							<option value="University of Frankfurt">University of Frankfurt</option>
							<option value="University of Barcelona">University of Barcelona</option>
							<option value="University of Ankara">University of Ankara</option>
							<option value="Peking University">Peking University</option>
							<option value="University of Belgrade">University of Belgrade</option>
							<option value="University of Malaya">University of Malaya</option>
							<option value="Kyoto University">Kyoto University</option>
							<option value="University of Jordan">University of Jordan</option>
							<option value="University of Tehran">University of Tehran</option>
							<option value="University of Nairobi">University of Nairobi</option>
							<option value="Cairo University">Cairo University</option>
							<option value="University of Tartu">University of Tartu</option>
							<option value="University of Debrecen">University of Debrecen</option>
							<option value="University of Buenos Aires">University of Buenos Aires</option>
							<option value="Universidad de Chile">Universidad de Chile</option>
							<option value="Universidad Nacional Autónoma de México">Universidad Nacional Autónoma de México</option>
							<option value="University of the Philippines">University of the Philippines</option>
							<option value="Mahidol University">Mahidol University</option>
							<option value="University of Indonesia">University of Indonesia</option>
							<option value="University of the Witwatersrand">University of the Witwatersrand</option>
							<option value="Universidad de los Andes">Universidad de los Andes</option>
							<option value="University of Khartoum">University of Khartoum</option>
							<option value="Chulalongkorn University">Chulalongkorn University</option>
							<option value="Ain Shams University">Ain Shams University</option>
							<option value="University of Zagreb">University of Zagreb</option>
							<option value="University of Ibadan">University of Ibadan</option>
							<option value="National Taiwan University">National Taiwan University</option>
							<option value="University of Tohoku">University of Tohoku</option>
							<option value="University of Vienna">University of Vienna</option>
						</select>
					</div>
	            </li>





                <!-- 회원정보 수정으로 들어왔을때 추가 되는 부분  -->
                <li>
	                <label for="">입학/졸업년도(학사)</label>
					<div class="input_inner2 ">
                        <div>
                            <span>입학년도</span>
                            <select name="" id="">
                                <option value="">선택</option>
                            </select>
                        </div>
                        <div>
                            <span>졸업년도</span>
                            <select name="" id="">
                                <option value="">선택</option>
                            </select>
                        </div>
					</div>
	            </li>
             <li class="stretch">
                <label for="">석/박사 졸업</label>
                <div class="input_inner">
                    <table class="degree_table" id="degreeTable">
                        <thead>
                            <tr>
                                <th style="width:15%;">학회</th>
                                <th style="width:42%;">학교 및 전공</th>
                                <th style="width:43%;">기간</th>
                            </tr>
                        </thead>
                        <tbody id="degreeTableBody">
                            <tr>
                                <td>
                                    <select class="width_10">
                                        <option value="">석사</option>
                                        <option value="">박사</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text">
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <select class="width_15">
                                            <option value="">선택</option>
                                        </select>
                                        <span>-</span>
                                        <select class="width_15">
                                            <option value="">선택</option>
                                        </select>
                                        <button type="button" onclick="addRow(this)">추가</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </li>

            <script>
            function addRow(button) {
                const tableBody = document.getElementById('degreeTableBody');
                const currentRow = button.closest('tr');
                const newRow = currentRow.cloneNode(true);

                // 입력값 초기화
                const inputs = newRow.querySelectorAll('input, select');
                inputs.forEach(el => {
                    if (el.tagName === 'SELECT') el.selectedIndex = 0;
                    if (el.tagName === 'INPUT') el.value = '';
                });

                // 기존 버튼은 숨기고 새로운 버튼만 보이게
                const oldButton = currentRow.querySelector('button');
                if (oldButton) oldButton.style.visibility = 'hidden';

                tableBody.appendChild(newRow);
            }
            </script>


                <li>
                    <label for="">우편물 수령지</label>
                    <div class="input_inner">
                        <input type="radio" id="radio1" name="category" value="working">
                        <label for="radio1" >근무처</label>

                        <input type="radio" id="radio2" name="category" value="conference"class="margin">
                        <label for="radio2">자택</label>
                    </div>
                </li>

                <li>
                    <label for="">재직여부</label>
                    <div class="input_inner">
                        <input type="checkbox" id="" name="" vaule="">
                        <label for="">재직중(가급적 근무처를 기입해주세요)</label> 
                    </div>
                </li>



                <!-- //회원정보 수정으로 들어왔을때 추가 되는 부분  -->




				<li>
	                <label for=""><span class="required_mark">*</span>근무지 명</label>
					<div class="input_inner ">
	      				<input type="text" class="full_input">
					</div>
	            </li>
				<li>
	                <label for="" class="address"><span class="required_mark">*</span>근무지 주소</label>
					<div class="input_inner">
	      				 <label for="reg_mb_zip" class="sound_only">우편번호<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
	                <input type="text" name="mb_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input twopart_input full_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="5" maxlength="6"  >
	                <button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">우편번호 검색</button><br>
	                <input type="text" name="mb_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input frm_address full_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="50"  >
	                <label for="reg_mb_addr1" class="sound_only">기본주소<?php echo $config['cf_req_addr']?'<strong> 필수</strong>':''; ?></label><br>
	                <input type="text" name="mb_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="reg_mb_addr2" class="frm_input frm_address full_input" size="50">
	                <label for="reg_mb_addr2" class="sound_only">상세주소</label>
	
	              
					</div>
	            </li>
				<li>
	                <label for=""><span class="required_mark">*</span>근무지 전화번호</label>
					<div class="input_inner number_input">
						<input type="text">
						<span>-</span>
						<input type="text">
						<span>-</span>
						<input type="text">
					</div>
	            </li>
	        </ul>
	    </div>

		<h3>동의 여부</h3>

	    <div class="tbl_frm01 tbl_wrap register_form_inner">
	        <ul>
				<li class="chk_box">
		            <label for="">
						<span class="required_mark">*</span>
						회원 검색 동의
		            </label>
					<div class="input_inner">
						<h6>회원 성명, 소속, 회원등급, 근무처 주소가 회원병원 검색 메뉴에서 노출됩니다.</h6>
						<input type="radio" id="agree1_yes" name="agree1" value="동의">
						<label for="agree1_yes">동의</label>

						<input type="radio" id="agree1_no" name="agree1" value="동의하지 않음" class="margin">
						<label for="agree1_no">동의하지 않음</label>
					</div>
		        </li>
	            <li class="chk_box">
		            <label for="reg_mb_mailling">
						<span class="required_mark">*</span>
						안내 메일 수신 동의
		            </label>
					<div class="input_inner">
						<h6>대한치과이식임플란트학회에서 발생하는 안내 메일을 수신합니다.<br> 수신 거부가 가능하며, 거부 시 학회에서 발송하는 메일을 수신할 수 없습나다.</h6>
						<input type="radio" id="agree2_yes" name="agree2" value="동의">
						<label for="agree2_yes">동의</label>

						<input type="radio" id="agree2_no" name="agree2" value="동의하지 않음" class="margin">
						<label for="agree2_no">동의하지 않음</label>
					</div>
		        </li>

				<li class="chk_box">
		            <label for="">
						<span class="required_mark">*</span>
						SMS 수신 동의
		            </label>
					<div class="input_inner">
						<h6>대한치과이식임플란트학회에서 발생하는 안내 메일을 수신합니다.<br> 수신 거부가 가능하며, 거부 시 학회에서 발송하는 메일을 수신할 수 없습나다.</h6>
						<input type="radio" id="agree3_yes" name="agree3" value="동의">
						<label for="agree3_yes">동의</label>

						<input type="radio" id="agree3_no" name="agree3" value="동의하지 않음" class="margin">
						<label for="agree3_no">동의하지 않음</label>
					</div>
		        </li>

				<li class="chk_box">
		            <label for="">
						<span class="required_mark">*</span>
						고유식별번호
수집 동의
		            </label>
					<div class="input_inner">
						<h6>대한치과이식임플란트학회가 회비관리 및 학회 업무 처리를 위해 본인의 고유식별정보를 수집 이용하는 것에 동의합니다.</h6>
						<input type="radio" id="agree4_yes" name="agree4" value="동의">
						<label for="agree4_yes">동의</label>

						<input type="radio" id="agree4_no" name="agree4" value="동의하지 않음" class="margin">
						<label for="agree4_no">동의하지 않음</label>
						<span>*동의하지 않을 경우 회원가입이 불가합니다.</span>
					</div>
		        </li>

				<?php if ($config['cf_use_hp']) { ?>
		        <li class="chk_box">
		            <input type="checkbox" name="mb_sms" value="1" id="reg_mb_sms" <?php echo ($w=='' || $member['mb_sms'])?'checked':''; ?> class="selec_chk">
		        	<label for="reg_mb_sms">
		            	<span></span>
		            	<b class="sound_only">SMS 수신여부</b>
		            </label>
		            <span class="chk_li">휴대폰 문자메세지를 받겠습니다.</span>
		        </li>
		        <?php } ?>
				 </ul>
				 <div class="agree_all">
				 <input type="radio" id="agree_all" name="agree_all" value="동의">
				 <label for="agree_all">필수동의 항목 및 광고성 정보 수신(선택)에 모두 동의합니다.</label>
				 </div>
				 
	    </div>
<script>
  // 각 동의 항목 그룹 이름들
  const agreeGroups = ['agree1', 'agree2', 'agree3', 'agree4']; // ← 필요에 따라 추가
  const agreeAllId = 'agree_all';

  // "모두 동의" 클릭 시 위 항목 모두 동의로 설정
  document.getElementById(agreeAllId).addEventListener('change', function () {
    if (this.checked) {
      agreeGroups.forEach(name => {
        const yesRadio = document.querySelector(`input[name="${name}"][value="동의"]`);
        if (yesRadio) yesRadio.checked = true;
      });
    }
  });

  // 각 동의 항목 중 하나라도 "동의하지 않음"이면 "모두 동의" 해제
  agreeGroups.forEach(name => {
    const radios = document.querySelectorAll(`input[name="${name}"]`);
    radios.forEach(radio => {
      radio.addEventListener('change', () => {
        const allAgreed = agreeGroups.every(groupName => {
          const yesRadio = document.querySelector(`input[name="${groupName}"][value="동의"]`);
          return yesRadio && yesRadio.checked;
        });

        const agreeAll = document.getElementById(agreeAllId);
        agreeAll.checked = allAgreed;
      });
    });
  });
  
</script>

		       
	    <div class="tbl_frm01 tbl_wrap register_form_inner">
	        <ul>
	            <li class="is_captcha_use">
					<label for=""><span class="required_mark">*</span>자동화 프로그램<br>입력 방지</label>
					<div class="input_inner">
						<?php echo captcha_html(); ?>
					</div>
	            </li>
	        </ul>
	    </div>

		<h3>회비 금액 안내</h3>
 <div class="tbl_frm01 tbl_wrap register_form_inner">
	        <ul>
	            <li class="">
					<label for="">학회 회비</label>
					<div class="input_inner">
						<h4>치과의사, 기공사, 간호사 120,000원 (※ 비용 동일) / 학생 및 전공의 60,000원</h4>
					</div>
	            </li>
	            <li class="">
					<label for="">안내</label>
					<div class="input_inner">
						<h4>가입 완료 후 마이페이지에서 결제 하셔야 정상적으로 홈페이지 이용이 가능합니다.</h4>
					</div>
	            </li>
	        </ul>
	    </div>

	</div>
	<div class="btn_confirm">
	    <a href="<?php echo G5_URL ?>" class="btn_close">취소</a>
	    <button type="submit" id="btn_submit" class="btn_submit" accesskey="s"><?php echo $w==''?'회원가입':'정보수정'; ?></button>
	</div>
	</form>
</div>
</div>
<script>
$(function() {
    $("#reg_zip_find").css("display", "inline-block");

    <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
    // 아이핀인증
    $("#win_ipin_cert").click(function() {
        if(!cert_confirm())
            return false;

        var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
        certify_win_open('kcb-ipin', url);
        return;
    });

    <?php } ?>
    <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
    // 휴대폰인증
    $("#win_hp_cert").click(function() {
        if(!cert_confirm())
            return false;

        <?php
        switch($config['cf_cert_hp']) {
            case 'kcb':
                $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                $cert_type = 'kcb-hp';
                break;
            case 'kcp':
                $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                $cert_type = 'kcp-hp';
                break;
            case 'lg':
                $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
                $cert_type = 'lg-hp';
                break;
            default:
                echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                echo 'return false;';
                break;
        }
        ?>

        certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
        return;
    });
    <?php } ?>
});

// submit 최종 폼체크
function fregisterform_submit(f)
{
    // 회원아이디 검사
    if (f.w.value == "") {
        var msg = reg_mb_id_check();
        if (msg) {
            alert(msg);
            f.mb_id.select();
            return false;
        }
    }

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

    if (f.mb_password.value.length > 0) {
        if (f.mb_password_re.value.length < 3) {
            alert("비밀번호를 3글자 이상 입력하십시오.");
            f.mb_password_re.focus();
            return false;
        }
    }

    // 이름 검사
    if (f.w.value=="") {
        if (f.mb_name.value.length < 1) {
            alert("이름을 입력하십시오.");
            f.mb_name.focus();
            return false;
        }

        /*
        var pattern = /([^가-힣\x20])/i;
        if (pattern.test(f.mb_name.value)) {
            alert("이름은 한글로 입력하십시오.");
            f.mb_name.select();
            return false;
        }
        */
    }

    <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
    // 본인확인 체크
    if(f.cert_no.value=="") {
        alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
        return false;
    }
    <?php } ?>

    // 닉네임 검사
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
        var msg = reg_mb_nick_check();
        if (msg) {
            alert(msg);
            f.reg_mb_nick.select();
            return false;
        }
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

    <?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
    // 휴대폰번호 체크
    var msg = reg_mb_hp_check();
    if (msg) {
        alert(msg);
        f.reg_mb_hp.select();
        return false;
    }
    <?php } ?>

    if (typeof f.mb_icon != "undefined") {
        if (f.mb_icon.value) {
            if (!f.mb_icon.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                alert("회원아이콘이 이미지 파일이 아닙니다.");
                f.mb_icon.focus();
                return false;
            }
        }
    }

    if (typeof f.mb_img != "undefined") {
        if (f.mb_img.value) {
            if (!f.mb_img.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                alert("회원이미지가 이미지 파일이 아닙니다.");
                f.mb_img.focus();
                return false;
            }
        }
    }

    if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
        if (f.mb_id.value == f.mb_recommend.value) {
            alert("본인을 추천할 수 없습니다.");
            f.mb_recommend.focus();
            return false;
        }

        var msg = reg_mb_recommend_check();
        if (msg) {
            alert(msg);
            f.mb_recommend.select();
            return false;
        }
    }

    <?php echo chk_captcha_js();  ?>

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
</script>


<!-- } 회원정보 입력/수정 끝 -->
