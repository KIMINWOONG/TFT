<?php
include "../../../../common.php";

$tNum="학회소개";
$sNum="학회 회원 검색";
$bNum="107";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 검색 파라미터 받기
$search_region = isset($_GET['region']) ? clean_xss_tags($_GET['region']) : '';
$search_hospital = isset($_GET['hospital']) ? clean_xss_tags($_GET['hospital']) : '';
$search_name = isset($_GET['name']) ? clean_xss_tags($_GET['name']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// 페이징 설정
$rows_per_page = 10;
$offset = ($page - 1) * $rows_per_page;

// 검색 조건 구성
$where_clause = "WHERE mb_level > 2 AND mb_leave_date = ''";
$search_params = array();

if (!empty($search_region)) {
	switch($search_region){
		case "경남":
			$where_clause .= " AND (mb_work_addr1 LIKE '경남%' or mb_work_addr1 LIKE '경상남도%' OR mb_addr1 LIKE '경남%' or mb_addr1 LIKE '%경상남도%')";
			break;
		case "경북":
			$where_clause .= " AND (mb_work_addr1 LIKE '경북%' or mb_work_addr1 LIKE '경상북도%' OR mb_addr1 LIKE '경북%' or mb_addr1 LIKE '%경상북도%')";
			break;
		case "전남":
			$where_clause .= " AND (mb_work_addr1 LIKE '전남%' or mb_work_addr1 LIKE '전라남도%' OR mb_addr1 LIKE '전남%' or mb_addr1 LIKE '%전라남도%')";
			break;
		case "전북":
			$where_clause .= " AND (mb_work_addr1 LIKE '전북%' or mb_work_addr1 LIKE '전라북도%' OR mb_addr1 LIKE '전북%' or mb_addr1 LIKE '%전라북도%')";
			break;
		case "충남":
			$where_clause .= " AND (mb_work_addr1 LIKE '충남%' or mb_work_addr1 LIKE '충청남도%' OR mb_addr1 LIKE '충남%' or mb_addr1 LIKE '%충청남도%')";
			break;
		case "충북":
			$where_clause .= " AND (mb_work_addr1 LIKE '충북%' or mb_work_addr1 LIKE '충청북도%' OR mb_addr1 LIKE '충북%' or mb_addr1 LIKE '%충청북도%')";
			break;
		default:
			$where_clause .= " AND (mb_work_addr1 LIKE '{$search_region}%' OR mb_addr1 LIKE '{$search_region}%')";
			break;
	}
}

if (!empty($search_hospital)) {
    $where_clause .= " AND mb_work_name LIKE '%{$search_hospital}%'";
}

if (!empty($search_name)) {
    $where_clause .= " AND mb_name LIKE '%{$search_name}%'";
}

// 회원검색동의한 회원만 표시
$where_clause .= " AND mb_search_agree = '동의'";

// 총 검색 결과 수 조회
$count_sql = "SELECT COUNT(*) as total FROM {$g5['member_table']} {$where_clause}";
$count_result = sql_fetch($count_sql);
$total_count = $count_result['total'];
$total_page = ceil($total_count / $rows_per_page);
// 검색 결과 조회
$search_sql = "SELECT mb_name, mb_work_name, mb_work_addr1, mb_work_addr2, mb_memclass, mb_title 
               FROM {$g5['member_table']} 
               {$where_clause} 
               ORDER BY mb_name ASC 
               LIMIT {$offset}, {$rows_per_page}";

$search_result = sql_query($search_sql);

// 지역 목록 조회 (중복 제거)
$region_sql = "SELECT DISTINCT SUBSTRING_INDEX(mb_work_addr1, ' ', 1) as region 
               FROM {$g5['member_table']} 
               WHERE mb_work_addr1 != '' AND mb_search_agree = '동의' 
               ORDER BY region";
$region_result = sql_query($region_sql);

$qstr.="&region={$search_region}&hospital={$search_hospital}&name={$search_name}";
// 병원명 목록 조회 (중복 제거)
/**
$hospital_sql = "SELECT DISTINCT mb_work_name 
                 FROM {$g5['member_table']} 
                 WHERE mb_work_name != '' AND mb_search_agree = '동의' 
                 ORDER BY mb_work_name";
$hospital_result = sql_query($hospital_sql);
*/
include_once(G5_THEME_PATH.'/head.php');
?>

<div class="mem_search common">
    <div class="width">
        <h2 class="contents_title">대한치과이식임플란트학회 회원 검색</h2>
        <h3>회원 성명 또는 소속 정보를 통해 등록된 학회 회원을 검색할 수 있습니다.</h3>

        <form id="searchForm" method="get" action="">
            <div class="search_box">
                <div class="input_wrap">
                    <label for="region">지역을 선택해주세요</label>
                    <select name="region" id="region">
                        <option value="">전국</option>
							<option value="서울" <?php echo ($search_region == "서울") ? 'selected' : ''; ?>>서울</option>
							<option value="부산" <?php echo ($search_region == "부산") ? 'selected' : ''; ?>>부산</option>
							<option value="인천" <?php echo ($search_region == "인천") ? 'selected' : ''; ?>>인천</option>
							<option value="울산" <?php echo ($search_region == "울산") ? 'selected' : ''; ?>>울산</option>
							<option value="광주" <?php echo ($search_region == "광주") ? 'selected' : ''; ?>>광주</option>
							<option value="대구" <?php echo ($search_region == "대구") ? 'selected' : ''; ?>>대구</option>
							<option value="대전" <?php echo ($search_region == "대전") ? 'selected' : ''; ?>>대전</option>
							<option value="세종" <?php echo ($search_region == "세종") ? 'selected' : ''; ?>>세종</option>
							<option value="경기" <?php echo ($search_region == "경기") ? 'selected' : ''; ?>>경기</option>
							<option value="강원" <?php echo ($search_region == "강원") ? 'selected' : ''; ?>>강원</option>
							<option value="경남" <?php echo ($search_region == "경남") ? 'selected' : ''; ?>>경남</option>
							<option value="경북" <?php echo ($search_region == "경북") ? 'selected' : ''; ?>>경북</option>
							<option value="전남" <?php echo ($search_region == "전남") ? 'selected' : ''; ?>>전남</option>
							<option value="전북" <?php echo ($search_region == "전북") ? 'selected' : ''; ?>>전북</option>
							<option value="충남" <?php echo ($search_region == "충남") ? 'selected' : ''; ?>>충남</option>
							<option value="충북" <?php echo ($search_region == "충북") ? 'selected' : ''; ?>>충북</option>
							<option value="제주" <?php echo ($search_region == "제주") ? 'selected' : ''; ?>>제주</option>
                    </select>
                </div>
                <div class="input_wrap">
                    <label for="hospital">병원명을 선택해주세요</label>
                    <div class="text_wrap">
                        <input type="text" name="hospital" id="hospital" placeholder="병원명을 입력해주세요." value="<?php echo $mb_work_name; ?>">
                    </div>
                    <!-- <select name="hospital" id="hospital">
                        <option value="">병원명</option>
                        <?php while ($row = sql_fetch_array($hospital_result)): ?>
                            <option value="<?php echo $row['mb_work_name']; ?>" <?php echo ($search_hospital == $row['mb_work_name']) ? 'selected' : ''; ?>>
                                <?php echo $row['mb_work_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select> -->
                </div>
                <div class="input_wrap">
                    <label for="name">성명을 입력해주세요</label>
                    <div class="text_wrap">
                        <input type="text" name="name" id="name" placeholder="예) 홍길동" value="<?php echo $search_name; ?>">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon5.png" alt="">
                    </div>
                </div>
                <div class="input_wrap">
                    <button type="submit" class="search_btn">검색하기</button>
                </div>
            </div>
        </form>

        <?php if (!empty($search_region) || !empty($search_hospital) || !empty($search_name)){ ?>
        <!-- 검색 결과 안내 -->
        <div class="search_result">
            <p>검색어 
                <?php if (!empty($search_name)): ?>
                    <span>"<?php echo $search_name; ?>"</span>
                <?php endif; ?>
                <?php if (!empty($search_region) || !empty($search_hospital)): ?>
                    <?php if (!empty($search_name)): ?>, <?php endif; ?>
                    <span>"<?php echo $search_region . ' ' . $search_hospital; ?>"</span>
                <?php endif; ?>
                에 대한 검색 결과는 <b><?php echo number_format($total_count); ?></b> 건 입니다.
            </p>
        </div>
        <?php } ?>

        <?php if ($total_count == 0 && (!empty($search_region) || !empty($search_hospital) || !empty($search_name))){ ?>
        <!-- 검색 결과 없음 -->
        <div class="search_result_none">
            <div class="search_none_page">
                <div class="text">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/page_none2.png" alt="">
                    <p><span>"<?php echo $search_name . ' ' . $search_region . ' ' . $search_hospital; ?>"</span> 에 대한 자료를 찾을 수 없습니다.</p>
                </div>
            </div>
        </div>
        <?php }else{ ?>

        <table>
            <tr>
                <th>병원명</th>
                <th>주소</th>
                <th>성명</th>
                <th>구분</th>
            </tr>
            <?php if ($total_count > 0): ?>
                <?php while ($row = sql_fetch_array($search_result)): ?>
                <tr>
                    <td><?php echo $row['mb_work_name'] ? $row['mb_work_name'] : '-'; ?></td>
                    <td><?php echo $row['mb_work_addr1'] . ' ' . $row['mb_work_addr2']; ?></td>
                    <td><?php echo $row['mb_name']; ?></td>
                    <td><?php echo $row['mb_gubun'] ? $row['mb_gubun'] : ''; ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 50px;">검색된 회원이 없습니다.</td>
                </tr>
            <?php endif; ?>
        </table>


		<?php echo get_paging_new(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?' . $qstr . '&amp;page='); ?>

        <?php } ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // 검색 폼 제출시 빈 값 제거
    $('#searchForm').on('submit', function() {
        $(this).find('input, select').each(function() {
            if ($(this).val() === '') {
                $(this).prop('disabled', true);
            }
        });
    });

    // 엔터키로 검색
    $('#name').on('keypress', function(e) {
        if (e.which === 13) {
            $('#searchForm').submit();
        }
    });

    // 지역 선택시 병원명 필터링 (선택사항)
	/**
    $('#region').on('change', function() {
        var selectedRegion = $(this).val();
        if (selectedRegion !== '') {
            $.ajax({
                url: '<?=G5_THEME_URL?>/subpage/get_hospitals.php',
                type: 'GET',
                data: { region: selectedRegion },
                dataType: 'json',
                success: function(data) {
                    var hospitalSelect = $('#hospital');
                    hospitalSelect.empty();
                    hospitalSelect.append('<option value="">병원명</option>');
                    
                    $.each(data, function(index, hospital) {
                        hospitalSelect.append('<option value="' + hospital + '">' + hospital + '</option>');
                    });
                }
            });
        }
    });
	*/
});
</script>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>