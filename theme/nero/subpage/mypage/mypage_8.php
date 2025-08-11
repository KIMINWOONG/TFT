<?php
include "../../../../common.php";

$tNum="마이페이지";
$sNum="마이페이지";

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 로그인 체크
if (!$is_member && !$is_nonemember) {
    alert("로그인 후 이용할 수 있습니다.", G5_URL);
}

// 회원 등록 정보 확인
$registration = sql_fetch("SELECT * FROM g5_conference_registration WHERE cr_id='{$cr_id}' AND cr_status = 'registered'");

// 활성화된 학술집담회 정보 가져오기
$conference = sql_fetch("SELECT * FROM g5_conference WHERE sy_status='active' ORDER BY sy_id DESC LIMIT 0,1");

if (!$conference) {
    alert("현재 진행중인 학술집담회가 없습니다.");
}


if (!$registration) {
    alert("학술집담회에 등록하신 후 초록을 제출할 수 있습니다.", G5_THEME_URL."/subpage/event/event2_07.php");
}

// 기존 초록 제출 여부 확인
if($is_member){
	$existing_abstract = sql_fetch("SELECT * FROM g5_conference_summary WHERE as_cr_id = '{$registration['cr_id']}' AND cr_mb_id = '{$member['mb_id']}'");
}else{
	$existing_abstract = sql_fetch("SELECT * FROM g5_conference_summary WHERE as_cr_id = '{$registration['cr_id']}' AND cr_nonemb_name = '".get_session("ss_nonemb_name")."' and cr_nonemb_birth='".get_session("ss_nonemb_birth")."'");
}

// 기존 저자 정보 로드
$existing_authors = array();
if ($existing_abstract) {
    $author_result = sql_query("SELECT * FROM g5_conference_summary_authors WHERE aa_as_id = '{$existing_abstract['as_id']}' ORDER BY aa_order");
    while ($author = sql_fetch_array($author_result)) {
        $existing_authors[] = $author;
    }
}

include_once(G5_THEME_PATH.'/head2.php');
?>

<div class="mypage common">
  <div class="width">
    <div class="sub_menu sub_menu_nonmem">
        <ul>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_1.php">회원정보 수정</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_2.php">회비 납부 내역</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_3.php">학술대회 신청 내역</a></li>
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_4.php">초록 제출 및 수정</a></li>
        </ul>
    </div>


    <h2>초록 제출 내역</h2>
    <div class="submission_form">
        <h3>논문메타정보</h3>

        <form id="abstractForm" action="mypage_8_update.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="<?php echo $existing_abstract ? 'update' : 'submit'; ?>">
		<input type="hidden" name="as_id" value="<?php echo $existing_abstract['as_id']; ?>">
		<input type="hidden" name="as_cr_id" id="as_cr_id" value="<?=$cr_id?>">
            <ul>
                <li>
                    <label for="submitter">
						<span class="required_mark">*</span>제출자
					</label>
					<div class="input_inner">
						<input type="text" name="submitter" id="submitter" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_submitter']) : htmlspecialchars($member['mb_name']); ?>" required>
					</div>
                </li>
                <li>
                    <label for="title_kor">
						<span class="required_mark">*</span>논문제목
					</label>
					<div class="input_inner">
						<input type="text" name="title_kor" id="title_kor" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_title_kor']) : ''; ?>" required>
					</div>
                </li>
                <li>
                    <label for="">
						논문제목(영어)
					</label>
					<div class="input_inner">
						<input type="text" name="title_eng" id="title_eng" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_title_eng']) : ''; ?>">
					</div>
                </li>
                <li class="author_name">
                    <label for="">
						<span class="required_mark">*</span>저자명
					</label>
					<div class="input_inner2">
                        <div id="authors_container">
                            <?php if (!empty($existing_authors)) { 
                                foreach ($existing_authors as $index => $author) { ?>
                            <div class="plus_inner">
                                <input type="text" name="authors[]" value="<?php echo htmlspecialchars($author['aa_name']); ?>" placeholder="저자명" required>
                                <select name="author_roles[]">
                                    <option value="제1저자" <?php echo ($author['aa_role'] == '제1저자') ? 'selected' : ''; ?>>제1저자</option>
                                    <option value="공동저자" <?php echo ($author['aa_role'] == '공동저자') ? 'selected' : ''; ?>>공동저자</option>
                                    <option value="교신저자" <?php echo ($author['aa_role'] == '교신저자') ? 'selected' : ''; ?>>교신저자</option>
                                    <option value="책임저자" <?php echo ($author['aa_role'] == '책임저자') ? 'selected' : ''; ?>>책임저자</option>
                                    <option value="참여저자" <?php echo ($author['aa_role'] == '참여저자') ? 'selected' : ''; ?>>참여저자</option>
                                    <option value="공동제1저자" <?php echo ($author['aa_role'] == '공동제1저자') ? 'selected' : ''; ?>>공동 제1저자</option>
                                    <option value="공동교신저자" <?php echo ($author['aa_role'] == '공동교신저자') ? 'selected' : ''; ?>>공동 교신저자</option>
                                </select>
                                <label>
                                    <input type="checkbox" name="is_presenter[]" <?php echo $author['aa_is_presenter'] ? 'checked' : ''; ?>> 발표자와 동일
                                </label>
                                <?php if ($index > 0) { ?>
                                <button type="button" class="remove_author" onclick="removeAuthor(this)">삭제</button>
                                <?php } ?>
                            </div>
                            <?php } 
                            } else { ?>
                            <div class="plus_inner">
                                <input type="text" name="authors[]" placeholder="저자명" required>
                                <select name="author_roles[]">
                                    <option value="제1저자">제1저자</option>
                                    <option value="공동저자">공동저자</option>
                                    <option value="교신저자">교신저자</option>
                                    <option value="책임저자">책임저자</option>
                                    <option value="참여저자">참여저자</option>
                                    <option value="공동제1저자">공동 제1저자</option>
                                    <option value="공동교신저자">공동 교신저자</option>
                                </select>
                                <label>
                                    <input type="checkbox" name="is_presenter[]"> 발표자와 동일
                                </label>
                            </div>
                            <?php } ?>
                        </div>
                        <button type="button" class="plus" onclick="addAuthor()">저자 추가</button>
					</div>
                </li>
                <li>
                    <label for="institution">
						<span class="required_mark">*</span>소속기관
					</label>
					<div class="input_inner">
						<input type="text" name="institution" id="institution" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_institution']) : ''; ?>" required>
					</div>
                </li>
                <li>
                    <label for="">
						언어
					</label>
					<div class="input_inner">
						<select name="language" id="language">
                            <option value="한국어" <?php echo ($existing_abstract && $existing_abstract['as_language'] == '한국어') ? 'selected' : ''; ?>>한국어</option>
                            <option value="영어" <?php echo ($existing_abstract && $existing_abstract['as_language'] == '영어') ? 'selected' : ''; ?>>영어</option>
                        </select>
					</div>
                </li>
                <li class="strech">
                    <label for="">
						<span class="required_mark">*</span>초록
					</label>
					<div class="input_inner">
						<textarea name="abstract_kor" id="abstract_kor" rows="10" required><?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_abstract_kor']) : ''; ?></textarea>
					</div>
                </li>
                <li class="strech">
                    <label for="">
						초록(영어)
					</label>
					<div class="input_inner">
						<textarea name="abstract_eng" id="abstract_eng" rows="10"><?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_abstract_eng']) : ''; ?></textarea>
					</div>
                </li>
                <li class="file_wrap">
                    <label for="">
						파일제출
					</label>
					<div class="inputbox">
						<div class="input_inner">
							<div class="file_upload">
								<label for="fileInput" class="custom_file_label">파일 선택</label>
								<span class="file_name">선택된 파일이 없습니다</span>
								<input type="file" name="abstract_file" id="fileInput" accept=".pdf,.doc,.docx">
							</div>
							<span>PDF, Word 파일만 업로드 가능합니다.</span>
						</div>
						<?php if($existing_abstract['as_file_path']){?>
						<div style="padding: 0px 2.8rem;"><?=$existing_abstract['as_file_name']?> <input type="checkbox" name="abstract_file_del" value="1"> 삭제</div>
						<?php }?>
					</div>
                </li>
            </ul>

            <h3>발표정보</h3>
            <ul>
                <li class="field">
                    <label for="">
						<span class="required_mark">*</span>발표분야
					</label>
					<div class="input_inner">
						<input type="radio" id="field_oral" name="presentation_field" value="구강" <?php echo ($existing_abstract['as_presentation_field'] == '구강') ? 'checked' : ''; ?> required>
						<label for="field_oral">구강</label>
						
						<input type="radio" id="field_prevention" name="presentation_field" value="예방" <?php echo ($existing_abstract['as_presentation_field'] == '예방') ? 'checked' : ''; ?> class="margin">
						<label for="field_prevention">예방</label>
						
						<input type="radio" id="field_perio" name="presentation_field" value="치주" <?php echo ($existing_abstract['as_presentation_field'] == '치주') ? 'checked' : ''; ?> class="margin">
						<label for="field_perio">치주</label>
						
						<input type="radio" id="field_pediatric" name="presentation_field" value="소아치과" <?php echo ($existing_abstract['as_presentation_field'] == '소아치과') ? 'checked' : ''; ?> class="margin">
						<label for="field_pediatric">소아치과</label>
						
						<input type="radio" id="field_dental" name="presentation_field" value="치과" <?php echo ($existing_abstract['as_presentation_field'] == '치과') ? 'checked' : ''; ?> class="margin">
						<label for="field_dental">치과</label>
					</div>
                </li>
                <li>
                    <label for=""><span class="required_mark">*</span>발표유형
					</label>
					<div class="input_inner">
                        <select name="presentation_type" id="presentation_type" required>
                            <option value="">초록 발표유형</option>
                            <option value="구연" <?php echo ($existing_abstract && $existing_abstract['as_presentation_type'] == '구연') ? 'selected' : ''; ?>>구연</option>
                            <option value="포스터" <?php echo ($existing_abstract && $existing_abstract['as_presentation_type'] == '포스터') ? 'selected' : ''; ?>>포스터</option>
                        </select>
					</div>
                </li>
            </ul>

            <h3>발표자 정보</h3>
            <ul>
                <li>
                    <label for="">
						<span class="required_mark">*</span>발표자명
					</label>
					<div class="input_inner">
						<input type="text" name="presenter_name" id="presenter_name" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_presenter_name']) : htmlspecialchars($registration['cr_name_kor']); ?>" required>
					</div>
                </li>
                <li>
                    <label for="">
						<span class="required_mark">*</span>소속기관
					</label>
					<div class="input_inner">
						<input type="text" name="presenter_institution" id="presenter_institution" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_presenter_institution']) : htmlspecialchars($registration['cr_hospital_name']); ?>" required>
					</div>
                </li>
                <li class="home_num">
                    <label for="">자택 전화번호</label>
					<div class="input_inner">
						<input type="text" name="presenter_home_phone1" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_presenter_home_phone1']) : ''; ?>" maxlength="4" placeholder="">
                        <span>-</span>
						<input type="text" name="presenter_home_phone2" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_presenter_home_phone2']) : ''; ?>" maxlength="4" placeholder="">
                        <span>-</span>
						<input type="text" name="presenter_home_phone3" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_presenter_home_phone3']) : ''; ?>" maxlength="4" placeholder="">
					</div>
                </li>
                <li class="phone">
                    <label for=""><span class="required_mark">*</span>휴대전화</label>
					<div class="input_inner">
						<select name="presenter_mobile_carrier" required>
                            <option value="">선택</option>
                            <option value="010" <?php echo ($existing_abstract && $existing_abstract['as_presenter_mobile_carrier'] == '010') ? 'selected' : ((!$existing_abstract && $registration['cr_mobile_carrier'] == '010') ? 'selected' : ''); ?>>010</option>
                            <option value="011" <?php echo ($existing_abstract && $existing_abstract['as_presenter_mobile_carrier'] == '011') ? 'selected' : ((!$existing_abstract && $registration['cr_mobile_carrier'] == '011') ? 'selected' : ''); ?>>011</option>
                            <option value="016" <?php echo ($existing_abstract && $existing_abstract['as_presenter_mobile_carrier'] == '016') ? 'selected' : ((!$existing_abstract && $registration['cr_mobile_carrier'] == '016') ? 'selected' : ''); ?>>016</option>
                            <option value="017" <?php echo ($existing_abstract && $existing_abstract['as_presenter_mobile_carrier'] == '017') ? 'selected' : ((!$existing_abstract && $registration['cr_mobile_carrier'] == '017') ? 'selected' : ''); ?>>017</option>
                            <option value="018" <?php echo ($existing_abstract && $existing_abstract['as_presenter_mobile_carrier'] == '018') ? 'selected' : ((!$existing_abstract && $registration['cr_mobile_carrier'] == '018') ? 'selected' : ''); ?>>018</option>
                            <option value="019" <?php echo ($existing_abstract && $existing_abstract['as_presenter_mobile_carrier'] == '019') ? 'selected' : ((!$existing_abstract && $registration['cr_mobile_carrier'] == '019') ? 'selected' : ''); ?>>019</option>
                        </select>
                        <span>-</span>
						<input type="text" name="presenter_mobile1" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_presenter_mobile1']) : htmlspecialchars($registration['cr_mobile1']); ?>" maxlength="4" required>
                        <span>-</span>
						<input type="text" name="presenter_mobile2" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_presenter_mobile2']) : htmlspecialchars($registration['cr_mobile2']); ?>" maxlength="4" required>
					</div>
                </li>
                <li>
                    <label for="">이메일</label>
					<div class="input_inner">
						<input type="email" name="presenter_email" id="presenter_email" value="<?php echo $existing_abstract ? htmlspecialchars($existing_abstract['as_presenter_email']) : htmlspecialchars($registration['cr_email']); ?>">
					</div>
                </li>
            </ul>

            <div class="btn_center">
                <a href="javascript:history.back();" class="btn_close">취소</a>
                <button type="submit" class="btn_submit"><?php echo $existing_abstract ? '수정하기' : '제출하기'; ?></button>
            </div>
        </form>
    </div>

    <!-- 성공 팝업 -->
    <div id="success_popup" class="my_pop_wrap" style="display: none;">
        <div class="my_pop-inner">
            <div class="my_pop-text my_pop">
                <div class="pop_title">
                    <div>
                        <h5>접수 완료 되었습니다.</h5>
                    </div>
                </div>
                <div class="pop_btn_wrap">
                    <div class="my_pop_ok" onclick="closeSuccessPopup();">확인</div>
                </div>
            </div>
        </div>
    </div>



  </div>
</div> 
<script>
// 저자 추가
function addAuthor() {
    const container = document.getElementById('authors_container');
    const authorCount = container.children.length;
    
    const newAuthorDiv = document.createElement('div');
    newAuthorDiv.className = 'plus_inner';
    newAuthorDiv.innerHTML = `
        <input type="text" name="authors[]" placeholder="저자명" required>
        <select name="author_roles[]">
            <option value="제1저자">제1저자</option>
            <option value="공동저자" selected>공동저자</option>
            <option value="교신저자">교신저자</option>
            <option value="책임저자">책임저자</option>
            <option value="참여저자">참여저자</option>
            <option value="공동제1저자">공동 제1저자</option>
            <option value="공동교신저자">공동 교신저자</option>
        </select>
        <label>
            <input type="checkbox" name="is_presenter[]"> 발표자와 동일
        </label>
        <button type="button" class="remove_author" onclick="removeAuthor(this)">삭제</button>
    `;
    
    container.appendChild(newAuthorDiv);
    
    // 새로 추가된 저자의 체크박스에 이벤트 리스너 추가
    const newCheckbox = newAuthorDiv.querySelector('input[name="is_presenter[]"]');
    const newAuthorInput = newAuthorDiv.querySelector('input[name="authors[]"]');
    
    // 체크박스 이벤트
    newCheckbox.addEventListener('change', function() {
        const presenterNameInput = document.getElementById('presenter_name');
        const presenterInstitutionInput = document.getElementById('presenter_institution');
        const institutionInput = document.getElementById('institution');
        
        if (this.checked) {
            // 다른 체크박스들 해제
            const allCheckboxes = document.querySelectorAll('input[name="is_presenter[]"]');
            allCheckboxes.forEach(function(checkbox) {
                if (checkbox !== newCheckbox) {
                    checkbox.checked = false;
                }
            });
            
            // 발표자명과 소속기관 복사
            if (newAuthorInput.value.trim() !== '') {
                presenterNameInput.value = newAuthorInput.value;
            }
            
            if (institutionInput.value.trim() !== '') {
                presenterInstitutionInput.value = institutionInput.value;
            }
            
            // 발표자 필드를 읽기전용으로 설정
            presenterNameInput.readOnly = true;
            presenterInstitutionInput.readOnly = true;
            presenterNameInput.style.backgroundColor = '#f8f9fa';
            presenterInstitutionInput.style.backgroundColor = '#f8f9fa';
            
        } else {
            // 체크 해제 시 다른 체크박스가 없으면 직접 입력 가능
            const anyChecked = Array.from(document.querySelectorAll('input[name="is_presenter[]"]')).some(cb => cb.checked);
            
            if (!anyChecked) {
                presenterNameInput.readOnly = false;
                presenterInstitutionInput.readOnly = false;
                presenterNameInput.style.backgroundColor = '';
                presenterInstitutionInput.style.backgroundColor = '';
            }
        }
    });
    
    // 저자명 입력 이벤트
    newAuthorInput.addEventListener('input', function() {
        if (newCheckbox.checked) {
            const presenterNameInput = document.getElementById('presenter_name');
            presenterNameInput.value = this.value;
        }
    });
}

// 저자 삭제
function removeAuthor(button) {
    const container = document.getElementById('authors_container');
    const authorRow = button.parentElement;
    const checkbox = authorRow.querySelector('input[name="is_presenter[]"]');
    
    if (container.children.length > 1) {
        // 삭제할 저자가 발표자로 선택되어 있었다면
        const wasPresenter = checkbox && checkbox.checked;
        
        authorRow.remove();
        
        // 발표자였던 저자가 삭제된 경우 발표자 필드를 직접 입력 가능하게 변경
        if (wasPresenter) {
            const presenterNameInput = document.getElementById('presenter_name');
            const presenterInstitutionInput = document.getElementById('presenter_institution');
            
            // 다른 체크된 저자가 있는지 확인
            const anyChecked = document.querySelector('input[name="is_presenter[]"]:checked');
            
            if (!anyChecked) {
                presenterNameInput.readOnly = false;
                presenterInstitutionInput.readOnly = false;
                presenterNameInput.style.backgroundColor = '';
                presenterInstitutionInput.style.backgroundColor = '';
            }
        }
    } else {
        alert('최소 한 명의 저자는 필요합니다.');
    }
}

// 파일 선택 처리
document.getElementById("fileInput").addEventListener("change", function () {
    const fileNameDisplay = document.querySelector(".file_name");
    
    if (this.files.length > 0) {
        const file = this.files[0];
        const fileSize = file.size / (1024 * 1024); // MB 단위
        
        // 파일 크기 검증
        if (fileSize > 10) {
            alert('파일 크기는 10MB 이하만 가능합니다.');
            this.value = '';
            fileNameDisplay.textContent = "선택된 파일이 없습니다";
            return;
        }
        
        // 파일 확장자 검증
        const allowedExtensions = ['pdf', 'doc', 'docx'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!allowedExtensions.includes(fileExtension)) {
            alert('PDF, Word 파일만 업로드 가능합니다.');
            this.value = '';
            fileNameDisplay.textContent = "선택된 파일이 없습니다";
            return;
        }
        
        fileNameDisplay.textContent = file.name;
    } else {
        fileNameDisplay.textContent = "선택된 파일이 없습니다";
    }
});

// 폼 제출 검증
document.getElementById('abstractForm').addEventListener('submit', function(e) {
    // 저자 검증
    const authors = document.querySelectorAll('input[name="authors[]"]');
    let hasAuthor = false;
    
    authors.forEach(function(input) {
        if (input.value.trim() !== '') {
            hasAuthor = true;
        }
    });
    
    if (!hasAuthor) {
        e.preventDefault();
        alert('최소 한 명의 저자를 입력해주세요.');
        return false;
    }
    
    // 발표분야 검증
    const presentationFields = document.querySelectorAll('input[name="presentation_field"]');
    let fieldSelected = false;
    
    presentationFields.forEach(function(radio) {
        if (radio.checked) {
            fieldSelected = true;
        }
    });
    
    if (!fieldSelected) {
        e.preventDefault();
        alert('발표분야를 선택해주세요.');
        return false;
    }
    
    return true;
});

// 성공 팝업 닫기
function closeSuccessPopup() {
    document.getElementById('success_popup').style.display = 'none';
    location.href = '<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_3.php';
}

// 전역 함수로 팝업 열기 (처리 페이지에서 호출 가능)
function showSuccessPopup() {
    document.getElementById('success_popup').style.display = 'block';
}

// 페이지 로드 시 기존 저자들에게 이벤트 리스너 추가
document.addEventListener('DOMContentLoaded', function() {
    const existingAuthors = document.querySelectorAll('#authors_container .plus_inner');
    const presenterNameInput = document.getElementById('presenter_name');
    const presenterInstitutionInput = document.getElementById('presenter_institution');
    
    // 초기 상태 설정 - 체크된 저자가 있으면 읽기전용으로 설정
    const initialChecked = document.querySelector('input[name="is_presenter[]"]:checked');
    if (initialChecked) {
        presenterNameInput.readOnly = true;
        presenterInstitutionInput.readOnly = true;
        presenterNameInput.style.backgroundColor = '#f8f9fa';
        presenterInstitutionInput.style.backgroundColor = '#f8f9fa';
    }
    
    existingAuthors.forEach(function(authorRow) {
        const checkbox = authorRow.querySelector('input[name="is_presenter[]"]');
        const authorInput = authorRow.querySelector('input[name="authors[]"]');
        
        if (checkbox && authorInput) {
            // 체크박스 이벤트
            checkbox.addEventListener('change', function() {
                const institutionInput = document.getElementById('institution');
                
                if (this.checked) {
                    // 다른 체크박스들 해제
                    const allCheckboxes = document.querySelectorAll('input[name="is_presenter[]"]');
                    allCheckboxes.forEach(function(cb) {
                        if (cb !== checkbox) {
                            cb.checked = false;
                        }
                    });
                    
                    // 발표자명과 소속기관 복사
                    if (authorInput.value.trim() !== '') {
                        presenterNameInput.value = authorInput.value;
                    }
                    
                    if (institutionInput.value.trim() !== '') {
                        presenterInstitutionInput.value = institutionInput.value;
                    }
                    
                    // 발표자 필드를 읽기전용으로 설정
                    presenterNameInput.readOnly = true;
                    presenterInstitutionInput.readOnly = true;
                    presenterNameInput.style.backgroundColor = '#f8f9fa';
                    presenterInstitutionInput.style.backgroundColor = '#f8f9fa';
                    
                } else {
                    // 체크 해제 시 다른 체크박스가 없으면 직접 입력 가능
                    const anyChecked = Array.from(document.querySelectorAll('input[name="is_presenter[]"]')).some(cb => cb.checked);
                    
                    if (!anyChecked) {
                        presenterNameInput.readOnly = false;
                        presenterInstitutionInput.readOnly = false;
                        presenterNameInput.style.backgroundColor = '';
                        presenterInstitutionInput.style.backgroundColor = '';
                    }
                }
            });
            
            // 저자명 입력 이벤트
            authorInput.addEventListener('input', function() {
                if (checkbox.checked) {
                    presenterNameInput.value = this.value;
                }
            });
        }
    });
});
</script>

<?php
include_once(G5_THEME_PATH.'/tail.php');
