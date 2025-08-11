<?php
$sub_menu = "300100";
require_once './_common.php';
require_once G5_EDITOR_LIB;

auth_check_menu($auth, $sub_menu, 'w');

$html_title = '집담회';

if ($w == '') {
    $html_title .= ' 생성';
} elseif ($w == 'u') {
	$conference=sql_fetch("select * from g5_conference where sy_id='{$sy_id}'");
    $html_title .= ' 수정';
}

$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<style>
.conference_form { margin: 20px 0; }
.file_upload { margin: 10px 0; }
.current_file { color: #666; font-size: 12px; margin-top: 5px; }
.datetime_input { display: flex; gap: 10px; align-items: center; }
.datetime_input input[type="datetime-local"] { flex: 1; }
.form_section { margin: 30px 0; border-top: 2px solid #ddd; padding-top: 20px; }
.form_section:first-child { border-top: none; }
.section_title {padding-left:20px; font-size: 16px; font-weight: bold; color: #333; margin-bottom: 15px; }
</style>

<form name="inputform" id="inputform" action="./conference_form_update.php" onsubmit="return inputform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sy_id" value="<?php echo $sy_id ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<div class="tbl_frm01 tbl_wrap conference_form">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
        <!-- 기본 정보 -->
        <tr>
            <th scope="row"><label for="sy_title">집담회 제목<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="text" name="sy_title" value="<?php echo get_text($conference['sy_title']); ?>" id="sy_title" required class="frm_input required" size="90" maxlength="255">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="sy_title_en">집담회 제목(영문)<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="text" name="sy_title_en" value="<?php echo get_text($conference['sy_title_en']); ?>" id="sy_title_en" required class="frm_input required" size="90" maxlength="255">
            </td>
        </tr>
        
        <tr>
            <th scope="row"><label for="sy_year">개최년도<strong class="sound_only">필수</strong></label></th>
            <td>
				<input type="date" name="sy_sdate" id="sy_sdate" value="<?=$conference['sy_sdate']?>" class="frm_input required" required> ~ <input type="date" name="sy_edate" id="sy_edate" value="<?=$conference['sy_edate']?>" class="frm_input required" required>
            </td>
        </tr>
        
        <tr>
            <th scope="row"><label for="sy_status">상태</label></th>
            <td>
                <select name="sy_status" id="sy_status" class="frm_input">
                    <option value="planning" <?php echo ($conference['sy_status'] == 'planning') ? 'selected' : ''; ?>>기획중</option>
                    <option value="active" <?php echo ($conference['sy_status'] == 'active') ? 'selected' : ''; ?>>진행중</option>
                    <option value="completed" <?php echo ($conference['sy_status'] == 'completed') ? 'selected' : ''; ?>>완료</option>
                </select>
            </td>
        </tr>
    </tbody>
    </table>
</div>

<!-- 이미지 업로드 섹션 -->
<div class="form_section">
    <div class="section_title">이미지 관리</div>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
            <tr>
                <th scope="row"><label for="sy_main_image">메인 이미지</label></th>
                <td>
                    <div class="file_upload">
                        <input type="file" name="sy_main_image" id="sy_main_image" accept="image/*">
                        <?php if ($conference['sy_main_image']) { ?>
                        <div class="current_file">
                            현재 파일: <?php echo basename($conference['sy_main_image']); ?>
                            <input type="checkbox" name="sy_main_image_del" value="1" id="sy_main_image_del">
                            <label for="sy_main_image_del">삭제</label>
                        </div>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><label for="sy_map_image">약도 이미지</label></th>
                <td>
                    <div class="file_upload">
                        <input type="file" name="sy_map_image" id="sy_map_image" accept="image/*">
                        <?php if ($conference['sy_map_image']) { ?>
                        <div class="current_file">
                            현재 파일: <?php echo basename($conference['sy_map_image']); ?>
                            <input type="checkbox" name="sy_map_image_del" value="1" id="sy_map_image_del">
                            <label for="sy_map_image_del">삭제</label>
                        </div>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</div>

<!-- 장소 및 일시 섹션 -->
<div class="form_section">
    <div class="section_title">장소 및 일시</div>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
            <tr>
                <th scope="row"><label for="sy_place">장소<strong class="sound_only">필수</strong></label></th>
                <td>
                    <input type="text" name="sy_place" value="<?php echo get_text($conference['sy_place']); ?>" id="sy_place" required class="frm_input required" size="90" maxlength="500" placeholder="집담회 개최 장소">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="sy_address">주소<strong class="sound_only">필수</strong></label></th>
                <td>
                    <input type="text" name="sy_address" value="<?php echo get_text($conference['sy_address']); ?>" id="sy_address" required class="frm_input required" size="90" maxlength="500" placeholder="집담회 개최 장소 주소">
                </td>
            </tr>
            
            <tr>
                <th scope="row"><label for="sy_time">일시<strong class="sound_only">필수</strong></label></th>
                <td>
                    <input type="text" name="sy_time" value="<?php echo get_text($conference['sy_time']); ?>" id="sy_time" required class="frm_input required" size="90" maxlength="500" placeholder="예: 2025년 7월 15일(토) 09:00 ~ 18:00">
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</div>

<!-- 등록 기간 섹션 -->
<div class="form_section">
    <div class="section_title">등록 기간 설정</div>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
            <tr>
                <th scope="row">사전등록 기간</th>
                <td>
                    <div class="datetime_input">
                        <input type="date" name="sy_early_reg_start" value="<?php echo $conference['sy_early_reg_start'] ? date('Y-m-d', strtotime($conference['sy_early_reg_start'])) : ''; ?>" placeholder="시작일시" class="frm_input">
                        <span>~</span>
                        <input type="date" name="sy_early_reg_end" value="<?php echo $conference['sy_early_reg_end'] ? date('Y-m-d', strtotime($conference['sy_early_reg_end'])) : ''; ?>" placeholder="종료일시" class="frm_input">
                    </div>
                </td>
            </tr>
            
            <tr>
                <th scope="row">일반등록 기간</th>
                <td>
                    <div class="datetime_input">
                        <input type="date" name="sy_reg_start" value="<?php echo $conference['sy_reg_start'] ? date('Y-m-d', strtotime($conference['sy_reg_start'])) : ''; ?>" placeholder="시작일시" class="frm_input">
                        <span>~</span>
                        <input type="date" name="sy_reg_end" value="<?php echo $conference['sy_reg_end'] ? date('Y-m-d', strtotime($conference['sy_reg_end'])) : ''; ?>" placeholder="종료일시" class="frm_input">
                    </div>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</div>
<!-- 등록비 섹션 -->
<div class="form_section">
    <div class="section_title">등록비 설정</div>
    <div class="tbl_frm01 tbl_wrap">
		<table style="border-spacing:0;">
			<tr>
				<th></th>
				<th>정회원</th>
				<th>준회원</th>
				<th>비회원</th>
			</tr>
			<tr>
				<td>등록비</td>
				<td><input type="number" name="sy_fee_member" value="<?php echo $conference['sy_fee_member'] ? $conference['sy_fee_member'] : ''; ?>" id="sy_fee_member" class="frm_input" min="0" placeholder="0"> 원</td>
				<td><input type="number" name="sy_fee_associate" value="<?php echo $conference['sy_fee_associate'] ? $conference['sy_fee_associate'] : ''; ?>" id="sy_fee_associate" class="frm_input" min="0" placeholder="0"> 원</td>
				<td><input type="number" name="sy_fee_nonmember" value="<?php echo $conference['sy_fee_nonmember'] ? $conference['sy_fee_nonmember'] : ''; ?>" id="sy_fee_nonmember" class="frm_input" min="0" placeholder="0"> 원</td>
			</tr>
			<tr>
				<td>현장등록비</td>
				<td><input type="number" name="sy_offfee_member" value="<?php echo $conference['sy_offfee_member'] ? $conference['sy_offfee_member'] : ''; ?>" id="sy_offfee_member" class="frm_input" min="0" placeholder="0"> 원</td>
				<td><input type="number" name="sy_offfee_associate" value="<?php echo $conference['sy_offfee_associate'] ? $conference['sy_offfee_associate'] : ''; ?>" id="sy_offfee_associate" class="frm_input" min="0" placeholder="0"> 원</td>
				<td><input type="number" name="sy_offfee_nonmember" value="<?php echo $conference['sy_offfee_nonmember'] ? $conference['sy_offfee_nonmember'] : ''; ?>" id="sy_offfee_nonmember" class="frm_input" min="0" placeholder="0"> 원</td>
			</tr>
		</table>

    </div>
</div>
<div class="form_section">
    <div class="section_title">참가 및 초록제출 설정</div>
    <div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
        <!-- 기본 정보 -->
        <tr>
            <th scope="row"><label for="sy_nonmember_enter">비회원 집담회 참가가능 여부<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="checkbox" name="sy_nonmember_enter" value="1" id="sy_nonmember_enter"  class="frm_input " <?=$conference['sy_nonmember_enter']?"checked":"";?>> 참가
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="sy_summary_supply">비회원 초록제출 여부<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="checkbox" name="sy_summary_supply" value="1" id="sy_summary_supply"  class="frm_input " <?=$conference['sy_summary_supply']?"checked":"";?>> 제출
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="sy_summary_supply">회원 초록제출 여부<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="checkbox" name="sy_member_summary_supply" value="1" id="sy_member_summary_supply"  class="frm_input " <?=$conference['sy_member_summary_supply']?"checked":"";?>> 제출
            </td>
        </tr>
	</tbody>
	</table>

    </div>
</div>
<div class="form_section">
    <div class="section_title">인사말</div>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
            <tr>
                <th scope="row">인사말 내용</th>
                <td>
                    <?php echo editor_html('sy_greeting', get_text(html_purifier($conference['sy_greeting']), 0)); ?>
                    <div style="margin-top: 10px; color: #666; font-size: 12px;">
                        집담회 소개 및 환영 인사말을 작성해주세요.
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">등록방법</th>
                <td>
                    <?php echo editor_html('sy_content_5', get_text(html_purifier($conference['sy_content_5']), 0)); ?>
                    <div style="margin-top: 10px; color: #666; font-size: 12px;">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">결제방법</th>
                <td>
                    <?php echo editor_html('sy_content_1', get_text(html_purifier($conference['sy_content_1']), 0)); ?>
                    <div style="margin-top: 10px; color: #666; font-size: 12px;">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">보수교육 점수</th>
                <td>
                    <?php echo editor_html('sy_content_2', get_text(html_purifier($conference['sy_content_2']), 0)); ?>
                    <div style="margin-top: 10px; color: #666; font-size: 12px;">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">등록 문의</th>
                <td>
                    <?php echo editor_html('sy_content_3', get_text(html_purifier($conference['sy_content_3']), 0)); ?>
                    <div style="margin-top: 10px; color: #666; font-size: 12px;">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">안내드립니다</th>
                <td>
                    <?php echo editor_html('sy_content_4', get_text(html_purifier($conference['sy_content_4']), 0)); ?>
                    <div style="margin-top: 10px; color: #666; font-size: 12px;">
                    </div>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</div>

<div class="btn_confirm01 btn_confirm">
    <a href="./conference_list.php" class="btn btn_02">목록</a>
    <?php if ($w == 'u') { ?>
    <a href="./conference_schedule.php?sy_id=<?php echo $sy_id; ?>" class="btn btn_03">일정 관리</a>
    <a href="./conference_speaker.php?sy_id=<?php echo $sy_id; ?>" class="btn btn_03">연자 관리</a>
    <?php } ?>
    <input type="submit" value="확인" class="btn btn_submit" accesskey="s">
</div>
</form>

<script>
function inputform_submit(f) {
    if (!f.sy_title.value) {
        alert('집담회 제목을 입력해주세요.');
        f.sy_title.focus();
        return false;
    }
    
   
    if (!f.sy_address.value) {
        alert('주소를 입력해주세요.');
        f.sy_address.focus();
        return false;
    }
    
    if (!f.sy_time.value) {
        alert('일시를 입력해주세요.');
        f.sy_time.focus();
        return false;
    }
    
    // 등록 기간 유효성 검사
    var early_start = f.sy_early_reg_start.value;
    var early_end = f.sy_early_reg_end.value;
    var reg_start = f.sy_reg_start.value;
    var reg_end = f.sy_reg_end.value;
    
    if (early_start && early_end) {
        if (early_start >= early_end) {
            alert('사전등록 시작일시는 종료일시보다 빨라야 합니다.');
            f.sy_early_reg_start.focus();
            return false;
        }
    }
    
    if (reg_start && reg_end) {
        if (reg_start >= reg_end) {
            alert('일반등록 시작일시는 종료일시보다 빨라야 합니다.');
            f.sy_reg_start.focus();
            return false;
        }
    }
    
    if (early_end && reg_start) {
        if (early_end > reg_start) {
            alert('사전등록 종료일시는 일반등록 시작일시보다 빨라야 합니다.');
            f.sy_early_reg_end.focus();
            return false;
        }
    }
    
   <?php echo get_editor_js('sy_greeting'); ?>
   <?php echo get_editor_js('sy_content_1'); ?>
   <?php echo get_editor_js('sy_content_2'); ?>
   <?php echo get_editor_js('sy_content_3'); ?>
   <?php echo get_editor_js('sy_content_4'); ?>
   <?php echo get_editor_js('sy_content_5'); ?>

	return true;
}

// 파일 업로드 미리보기 (선택사항)
document.getElementById('sy_main_image').addEventListener('change', function(e) {
    previewImage(e.target, 'main_preview');
});

document.getElementById('sy_map_image').addEventListener('change', function(e) {
    previewImage(e.target, 'map_preview');
});

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById(previewId);
            if (!preview) {
                preview = document.createElement('img');
                preview.id = previewId;
                preview.style.maxWidth = '200px';
                preview.style.maxHeight = '150px';
                preview.style.marginTop = '10px';
                preview.style.border = '1px solid #ddd';
                input.parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// 등록 기간 자동 설정 (선택사항)
function setDefaultDates() {
    var now = new Date();
    var nextMonth = new Date(now.getFullYear(), now.getMonth() + 1, 1);
    var twoMonthsLater = new Date(now.getFullYear(), now.getMonth() + 2, 15);
    
    // 기본값이 없을 때만 설정
    if (!document.querySelector('input[name="sy_early_reg_start"]').value) {
        document.querySelector('input[name="sy_early_reg_start"]').value = 
            nextMonth.toISOString().slice(0, 16);
    }
}

// 페이지 로드시 기본 날짜 설정 (필요시)
// window.onload = setDefaultDates;
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');

