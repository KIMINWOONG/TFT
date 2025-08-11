<?php
include "../../../../common.php";

$tNum="회원마당";
$sNum="회원 QNA";
$bNum="303";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


include_once(G5_THEME_PATH.'/head.php');
?>


<div class="qna common">
    <div class="width">
        <h2 class="contents_title">QNA</h2>

        <div class="board_top_info">
            <div class="total">TOTAL <span>39</span></div>
            <div class="input_wrap">
                <form name="fsearch" method="get">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sop" value="and">
            <label for="sfl" class="sound_only">검색대상</label>
            <select name="sfl" id="sfl">
                <?php echo get_board_sfl_select_options($sfl); ?>
            </select>
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <div class="sch_bar">
                <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="sch_input" size="25" maxlength="20" placeholder=" 검색어를 입력해주세요">
                <button type="submit" value="검색" class="sch_btn">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon5.png" alt="" ><span class="sound_only">검색</span></button>
            </div>
            </form>

            <a href="<?php echo G5_THEME_URL ?>/subpage/member/05.php" class="write_btn">글쓰기</a>
            </div>
        </div>


        <table>
            <tr>
                <th style="width:6%;">No.</th>
                <th style="width:50%;">제목</th>
                <th style="width:7%;">파일</th>
                <th style="width:10%;">작성자</th>
                <th style="width:15%;" class="qa_date">등록일</th>
                <th style="width:8%;"class="qa_hit">조회수</th>
            </tr>
            <tr>
                <td style="width:6%;">39</td>
                <td class="subject" style="width:50%;">
                    <a href="<?php echo G5_THEME_URL ?>/subpage/member/04.php">
                        <span class="q_login">회원가입 및 자격</span><p>연회비는 얼마정도 하나요?</p>
                    </a>
                </td>
                <td style="width:7%;"><img src="<?php echo G5_THEME_IMG_URL ?>/file.png" alt=""></td>
                <td style="width:10%;">홍길동</td>
                <td style="width:15%;" class="qa_date">2025-03-27</td>
                <td style="width:8%;"class="qa_hit">597</td>
            </tr>
            <tr>
                <td style="width:6%;">38</td>
                <td class="subject" style="width:50%;">
                    <a href="<?php echo G5_THEME_URL ?>/subpage/member/04.php">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/qnare.png" alt=""><p>연회비 관련하여 안내드리겠습니다.</p> 
                    </a>
                </td>
                <td style="width:7%;"></td>
                <td style="width:10%;">관리자</td>
                <td style="width:15%;" class="qa_date">2025-03-28</td>
                <td style="width:8%;"class="qa_hit">22</td>
            </tr>
            <tr>
                <td style="width:6%;">37</td>
                <td class="subject" style="width:50%;">
                    <a href="<?php echo G5_THEME_URL ?>/subpage/member/04.php">
                        <span class="q_course">진료 및 상담</span><p>학술대회 학생도 참여 가능한가요?</p>
                    </a>
                </td>
                <td style="width:7%;"></td>
                <td style="width:10%;">홍길동</td>
                <td style="width:15%;" class="qa_date">2025-03-27</td>
                <td style="width:8%;"class="qa_hit">597</td>
            </tr>
            <tr>
                <td style="width:6%;">36</td>
                <td class="subject" style="width:50%;">
                    <a href="<?php echo G5_THEME_URL ?>/subpage/member/04.php">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/qnare.png" alt=""><p>학술대회 참가자와 관련하여 안내드리겠습니다.</p> 
                    </a>
                </td>
                <td style="width:7%;"></td>
                <td style="width:10%;">관리자</td>
                <td style="width:15%;" class="qa_date">2025-03-28</td>
                <td style="width:8%;"class="qa_hit">22</td>
            </tr>
            <tr>
                <td style="width:6%;">35</td>
                <td class="subject" style="width:50%;">
                    <a href="<?php echo G5_THEME_URL ?>/subpage/member/04.php">
                        <span class="q_conferen">학술대회 및 집담회</span><p>학술대회 학생도 참여 가능한가요?</p>
                    </a>
                </td>
                <td style="width:7%;"></td>
                <td style="width:10%;">홍길동</td>
                <td style="width:15%;" class="qa_date">2025-03-27</td>
                <td style="width:8%;"class="qa_hit">597</td>
            </tr>
            <tr>
                <td style="width:6%;">34</td>
                <td class="subject" style="width:50%;">
                    <a href="<?php echo G5_THEME_URL ?>/subpage/member/04.php">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/qnare.png" alt=""><p>학술대회 참가자와 관련하여 안내드리겠습니다.</p> 
                    </a>
                </td>
                <td style="width:7%;"></td>
                <td style="width:10%;">관리자</td>
                <td style="width:15%;" class="qa_date">2025-03-28</td>
                <td style="width:8%;"class="qa_hit">22</td>
            </tr>
            <tr>
                <td style="width:6%;">33</td>
                <td class="subject" style="width:50%;">
                    <a href="<?php echo G5_THEME_URL ?>/subpage/member/04.php">
                        <span class="q_etc">기타 문의</span><p>학술대회 학생도 참여 가능한가요?</p>
                    </a>
                </td>
                <td style="width:7%;"></td>
                <td style="width:10%;">홍길동</td>
                <td style="width:15%;" class="qa_date">2025-03-27</td>
                <td style="width:8%;"class="qa_hit">597</td>
            </tr>
            <tr>
                <td style="width:6%;">32</td>
                <td class="subject" style="width:50%;">
                    <a href="<?php echo G5_THEME_URL ?>/subpage/member/04.php">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/qnare.png" alt=""><p>학술대회 참가자와 관련하여 안내드리겠습니다.</p> 
                    </a>
                </td>
                <td style="width:7%;"></td>
                <td style="width:10%;">관리자</td>
                <td style="width:15%;" class="qa_date">2025-03-28</td>
                <td style="width:8%;"class="qa_hit">22</td>
            </tr>
            <tr>
                <td style="width:6%;">31</td>
                <td class="subject" style="width:50%;">
                    <a href="#" onclick="openPopup(); return false;">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/lock.png" alt=""><p>비밀글입니다.</p> 
                    </a>
                </td>
                <td style="width:7%;"></td>
                <td style="width:10%;">-</td>
                <td style="width:15%;" class="qa_date">-</td>
                <td style="width:8%;"class="qa_hit">-</td>
            </tr>
            <tr>
                <td style="width:6%;">30</td>
                <td class="subject" style="width:50%;">
                    <a href="#" onclick="openPopup(); return false;">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/qnare.png" alt=""><p>비밀글입니다.</p> 
                    </a>
                </td>
                <td style="width:7%;"></td>
                <td style="width:10%;">-</td>
                <td style="width:15%;" class="qa_date">-</td>
                <td style="width:8%;"class="qa_hit">-</td>
            </tr>
        </table>

        <!-- 페이징 -->
        <div class="page_wrap">
            <button class="prev_2">
                <img src="<?php echo G5_THEME_IMG_URL ?>/prev2.png" alt="">
            </button>
            <button class="prev_1">
                <img src="<?php echo G5_THEME_IMG_URL ?>/prev1.png" alt="">
            </button>
            <ul>
                <li><a href="#" class="page_active">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">6</a></li>
                <li><a href="#">7</a></li>
                <li><a href="#">8</a></li>
                <li><a href="#">9</a></li>
                <li><a href="#">10</a></li>
            </ul>
            <button class="next_1">
                <img src="<?php echo G5_THEME_IMG_URL ?>/next1.png" alt="">
            </button>
            <button class="next_2">
                <img src="<?php echo G5_THEME_IMG_URL ?>/next2.png" alt="">
            </button>
        </div>
        <!-- //페이징 -->


<!-- 비밀글 팝업 -->
   <!-- 배경 어둡게 -->
  <div id="overlay"></div>

  <!-- 팝업 창 -->
  <div id="popup">
    <h3>회원정보 입력</h3>
    <div class="popup_inner">
    <div class="pop_title">
        <img src="<?php echo G5_THEME_IMG_URL ?>/mem_icon1.png" alt="">
        <div>
            <h4>회원정보 수정을 원하시면</h4>
            <h5>이메일과 비밀번호를 입력해주세요.</h5>
        </div>
     </div>
     <div class="pop_input_wrap">
        <input type="email" name="verify_email" id="verify_email" placeholder="이메일을 입력해주세요." required value="<?php echo get_text($member['mb_email']); ?>" readonly>
        <input type="password" name="verify_password" id="verify_password" placeholder="비밀번호를 입력해주세요." required>
     </div>
     <div class="txt">
        <p>비밀번호 분실시 고객센터로 문의 주세요</p>
        <h6>T. 02-2273-3875</h6>
     </div>
     <div class="pop_btn_wrap">
        <div class="my_pop_close" onclick="closePopup();">취소</div>
        <a href="javascript:void(0)" class="my_pop_ok" onclick="verifyMember();">확인</a>
     </div>
     </div>
  </div>

  <script>
    function openPopup() {
      document.getElementById("overlay").style.display = "block";
      document.getElementById("popup").style.display = "block";
      document.body.classList.add("modal-open");
    }

    function closePopup() {
      document.getElementById("overlay").style.display = "none";
      document.getElementById("popup").style.display = "none";
      document.body.classList.remove("modal-open");
    }
  </script>

<!-- //비밀글 팝업 -->

    </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
