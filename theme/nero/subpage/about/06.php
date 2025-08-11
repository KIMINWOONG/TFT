<?php
include "../../../../common.php";

$tNum="학회소개";
$sNum="임원소개";
$bNum="106";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 페이징 처리
$page = (int)$_GET['page'] ?: 1;
$per_page = 6;
$offset = ($page - 1) * $per_page;

// 카테고리 필터
$category = $_GET['category'] ?? 'all';

// 임원 목록 조회
$where_sql = "";
if ($category != 'all') {
    $where_sql = "WHERE ex_category = '" . sql_real_escape_string($category) . "'";
}

$sql = "SELECT * FROM g5_executive $where_sql ORDER BY ex_order ASC, ex_id ASC LIMIT $offset, $per_page";
$result = sql_query($sql);

// 전체 개수 (페이징용)
$total_sql = "SELECT COUNT(*) as cnt FROM g5_executive $where_sql";
$total_result = sql_fetch($total_sql);
$total_count = $total_result['cnt'];
$total_page = ceil($total_count / $per_page);

// 카테고리별 데이터 조회
$categories = array(
    'all' => '전체',
    //'지역별' => '지역별',
   // '전체분과별' => '전체 분과별',
    '연구 분과' => '연구 분과',
    '영상 및 AI 분과' => '영상 및 AI 분과',
    '구강외과 분과' => '구강외과 분과',
    '치주 분과' => '치주 분과',
    '보철 분과' => '보철 분과',
    '통합치의학 및 장애인치과 분과' => '통합치의학 및 장애인치과 분과'
);

include_once(G5_THEME_PATH.'/head.php');
?>

<div class="officers common">
    <div class="width">
        <h2 class="contents_title">대한치과이식임플란트학회 임원 소개</h2>

        <div class="tab-container">
            <div class="btn_wrap1">
                <div class="button_wrap">
                    <button class="btn_1 <?php echo ($category == 'all') ? 'btn_active1' : ''; ?> entire" onclick="showCategory('all')">전체</button>
                </div>
                
                <div class="btn_1_wrap">
                    <?php foreach ($categories as $cat_key => $cat_name) {
                        if ($cat_key == 'all') continue;
                    ?>
                    <button class="btn_1 <?php echo ($category == $cat_key) ? 'btn_active1' : ''; ?>" onclick="showCategory('<?php echo $cat_key; ?>')"><?php echo $cat_name; ?></button>
                    <?php } ?>
                </div>
            </div>
           
            <div class="contents1"> 
                <div class="tab_content" id="content-list">
                    <div class="items">
                        <?php
                        if (sql_num_rows($result) == 0) {
                            echo '<div class="no_data">등록된 임원이 없습니다.</div>';
                        } else {
                            while ($row = sql_fetch_array($result)) {
                        ?>
                        <a href="javascript:void(0)" class="profile_popup item" data-id="<?php echo $row['ex_id']; ?>">
                            <?php if ($row['ex_image']) { ?>
                                <img src="<?php echo G5_DATA_URL; ?>/executive/<?php echo $row['ex_image']; ?>" alt="<?php echo $row['ex_name']; ?>">
                            <?php } else { ?>
                                <img src="<?php echo G5_THEME_IMG_URL ?>/profile.png" alt="<?php echo $row['ex_name']; ?>">
                            <?php } ?>
                            <div class="name_wrap">
                                <h5><?php echo $row['ex_department']; ?></h5>
                                <h6><?php echo $row['ex_name']; ?></h6>
                                <img src="<?php echo G5_THEME_IMG_URL ?>/pop_arrow.png" alt="">
                            </div>
                        </a>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 페이징 -->
        <?php if ($total_page > 1) { ?>
        <div class="page_wrap">
            <?php if ($page > 1) { ?>
                <button class="prev_2" onclick="goPage(1)">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/prev2.png" alt="">
                </button>
                <button class="prev_1" onclick="goPage(<?php echo $page-1; ?>)">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/prev1.png" alt="">
                </button>
            <?php } ?>
            
            <ul>
                <?php
                $start_page = max(1, $page - 5);
                $end_page = min($total_page, $start_page + 9);
                
                for ($i = $start_page; $i <= $end_page; $i++) {
                ?>
                <li><a href="javascript:goPage(<?php echo $i; ?>)" class="<?php echo ($i == $page) ? 'page_active' : ''; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
            </ul>
            
            <?php if ($page < $total_page) { ?>
                <button class="next_1" onclick="goPage(<?php echo $page+1; ?>)">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/next1.png" alt="">
                </button>
                <button class="next_2" onclick="goPage(<?php echo $total_page; ?>)">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/next2.png" alt="">
                </button>
            <?php } ?>
        </div>
        <?php } ?>
        <!-- //페이징 -->

    </div> 
</div>

<!-- 임원 상세 팝업 모달 -->
<div id="pro_popup" class="pop_wrap1">
    <div class="pop_inner">
        <div class="profile_img">
            <img id="popup_image" src="" alt="">
        </div>
        
        <div class="text_box">
            <h6 id="popup_category"></h6>
            <h3 id="popup_name"></h3>
            <h4>약력</h4>
            <ul id="popup_career">
                <!-- 경력 정보가 여기에 동적으로 들어갑니다 -->
            </ul>
        </div>
        
        <div class="pop-close" onclick="closePopup();">
            <img src="<?php echo G5_THEME_IMG_URL ?>/close.png" alt="">
        </div>
    </div>
</div>

<script>
$(function () {
    // 임원 프로필 클릭 이벤트
    $(document).on("click", ".profile_popup", function () {
        const executive_id = $(this).data("id");
        loadExecutiveDetail(executive_id);
    });

    // 닫기 버튼 클릭 시 팝업 닫기
    $(document).on("click", ".pop-close", function () {
        closePopup();
    });
    
    // 팝업 배경 클릭시 닫기
    $(document).on("click", ".pop_wrap1", function(e) {
        if (e.target === this) {
            closePopup();
        }
    });
});

// 임원 상세 정보 로드
function loadExecutiveDetail(executive_id) {
    $.ajax({
        url: './executive_ajax.php',
        type: 'GET',
        data: { 
            mode: 'detail',
            ex_id: executive_id 
        },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                const executive = data.executive;
                
                // 이미지 설정
                if (executive.ex_image) {
                    $('#popup_image').attr('src', '<?php echo G5_DATA_URL; ?>/executive/' + executive.ex_image);
                } else {
                    $('#popup_image').attr('src', '<?php echo G5_THEME_IMG_URL ?>/profile.png');
                }
                
                // 기본 정보 설정
                $('#popup_category').text(executive.ex_category + ', 자문위원');
                $('#popup_name').text(executive.ex_name );
                
                // 경력 정보 설정
                $('#popup_career').empty();
                if (executive.ex_career) {
                    const careers = executive.ex_career.split('\n');
                    careers.forEach(function(career) {
                        if (career.trim()) {
                            $('#popup_career').append('<li><span></span><p>' + career.trim() + '</p></li>');
                        }
                    });
                } else {
                    $('#popup_career').append('<li><span></span><p>등록된 경력 정보가 없습니다.</p></li>');
                }
                
                // 팝업 표시
                $('#pro_popup').fadeIn(500);
            } else {
                alert('임원 정보를 불러오는데 실패했습니다.');
            }
        },
        error: function() {
            alert('서버 오류가 발생했습니다.');
        }
    });
}

// 팝업 닫기
function closePopup() {
    $('#pro_popup').fadeOut(300);
}

// 카테고리 변경
function showCategory(category) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('category', category);
    currentUrl.searchParams.delete('page'); // 카테고리 변경시 페이지 초기화
    window.location.href = currentUrl.toString();
}

// 페이지 이동
function goPage(page) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('page', page);
    window.location.href = currentUrl.toString();
}
</script>

<style>
.no_data {
    text-align: center;
    padding: 80px 20px;
    color: #999;
    font-size: 16px;
}

.items {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.item {
    display: block;
    text-decoration: none;
    color: inherit;
}

.pop_wrap1 {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.pop_wrap1.show {
    display: flex;
}

/* PC 기본 스타일 */
.pop_inner {
    position: relative;
    max-width: 600px;
    max-height: 80vh;
    overflow: hidden;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.text_box {
    padding: 20px;
    overflow-y: auto;
    max-height: 400px;
}

/* PC용 스크롤바 스타일링 */
.text_box::-webkit-scrollbar {
    width: 8px;
}

.text_box::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.text_box::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.text_box::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* 태블릿 스타일 */
@media (max-width: 1024px) {
    .pop_inner {
        max-width: 90vw;
        max-height: 85vh;
        margin: 20px;
    }
    
    .text_box {
        max-height: calc(85vh - 250px);
        padding: 15px;
    }
}

/* 모바일 스타일 */
@media (max-width: 768px) {
    .pop_inner {
        max-width: 95vw;
        max-height: 90vh;
        margin: 10px;
    }
    
    .text_box {
        max-height: calc(90vh - 200px);
        padding: 15px 10px;
    }
    
    /* 모바일용 스크롤바 스타일링 */
    .text_box::-webkit-scrollbar {
        width: 6px;
    }
    
    .text_box::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .text_box::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
}
</style>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>