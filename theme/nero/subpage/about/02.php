<?php
include "../../../../common.php";

$tNum="학회소개";
$sNum="연혁";
$bNum="102";

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


include_once(G5_THEME_PATH.'/head.php');
?>

<div class="about_2  common">
    <h2 class="contents_title">치과계를 선도하는 학회, <span> 그 성장의 여정</span></h2>

    <div class="his_wrap">
        <span class="line"></span>
        <div class="filled-line"></div>
        <span class="dot"></span> <!-- 새로 추가 -->
        <div class="box wow fadeInUp" data-wow-duration="1s" data-wow-offset="200">
            <div class="img">
                <img src="<?php echo G5_THEME_IMG_URL ?>/history_img1.png" alt="">
            </div>
            <div class="text">
                <h2>2013 ~ 현재</h2>
                <h3>도약 및 국제 교류 확대</h3>
                <ul>
                    <li><span>2025.05</span> <p>22대 김성민 회장 선출</p></li>
                    <li><span>2017.07</span> <p>독일 임플란트학회와 상호교류협력 혜약 체결</p></li>
                    <li><span>2017.05</span> <p>21대 김영균 회장 선출</p></li>
                    <li><span>2016.03</span> <h4>학회 창립 40주년 기념 학술대회</h4></li>
                    <li><span>2015.05</span> <p>20대 김현철 회장 선출</p></li>
                    <li><span>2014.10</span> <p>우수임플란트임상의 제도 신설</p></li>
                    <li><span>2013.05</span> <p>19대 류인철 회장 선출</p></li>
                </ul>
            </div>
        </div>
        <div class="box box2 wow fadeInUp" data-wow-duration="1s" data-wow-offset="200">
            <div class="img">
                <img src="<?php echo G5_THEME_IMG_URL ?>/history_img2.png" alt="">
            </div>
            <div class="text">
                <h2>2000~2011</h2>
                <h3>제도 정비 및 디지털 확장</h3>
                <ul>
                    <li><span>2011.11</span> <p>“대한치과이식임플란트학회”로 명칭 개정</p></li>
                    <li><span>2011.05</span> <p>18대 박일해 회장 선출</p></li>
                    <li><span>2009.05</span> <p>17대 김명진 회장 선출</p></li>
                    <li><span>2007.03</span> <p>16대 정재영 회장 선출</p></li>
                    <li><span>2006.04</span> <p>학회 창립 30주년 기념 학술대회</p></li>
                    <li><span>2005.03</span> <p>15대 양재호 회장 선출</p></li>
                    <li><span>2003.04</span> <p>14대 이원철 회장 선출</p></li>
                    <li><span>2001.08</span> <p>학회 홈페이지 개설</p></li>
                </ul>
            </div>
        </div>
        <div class="box wow fadeInUp" data-wow-duration="1s" data-wow-offset="200">
            <div class="img">
                <img src="<?php echo G5_THEME_IMG_URL ?>/history_img3.png" alt="">
            </div>
            <div class="text">
                <h2>1982~1999</h2>
                <h3>성장 및 활동 확대</h3>
                <ul>
                    <li><span>1999.04</span> <p>인정 교육지도 제도 신설</p></li>
                    <li><span>1997.04</span> <p>11대 김화규 회장 선출, 임플란트 시술 상담실 설치</p></li>
                    <li><span>1996.03</span> <p>학회창립 20주년 및 한국 치과임플란트 30주년 학술대회</p></li>
                    <li><span>1993.04</span> <p>9대 최광철 회장 선출</p></li>
                    <li><span>1991.03</span> <p>8대 유광희 회장 선출</p></li>
                    <li><span>1989.04</span> <p>7대 최목균 회장 선출</p></li>
                    <li><span>1988.05</span> <p>서울 국제 임플란트 심포지움 개최</p></li>
                    <li><span>1985.05</span> <p>학회 창립 10주년 기념 학술대회</p></li>
                    <li><span>1982.04</span> <p>4대 김홍기 회장 선출 </p></li>
                </ul>
            </div>
        </div>
        <div class="box box2 wow fadeInUp" data-wow-duration="1s" data-wow-offset="200">
            <div class="img">
                <img src="<?php echo G5_THEME_IMG_URL ?>/history_img4.png" alt="">
            </div>
            <div class="text">
                <h2>1975~1981</h2>
                <h3>창립 및 기틀 마련</h3>
                <ul>
                    <li><span>1981.04</span> <p>“대한치과인공장기이식학회”로 명칭 변경(약칭 설정)</p></li>
                    <li><span>1980.12</span> <p>“대한치과이식학회”로 명칭 변경 및 분과학회 인준</p></li>
                    <li><span>1980.06</span> <p>임플란트 학회지 창간호 발간</p></li>
                    <li><span>1980.05</span> <p>3대 김광현 회장 선출</p></li>
                    <li><span>1976.05</span> <p>창립총회 및 학술 대회</p></li>
                    <li><span>1976.01</span> <p>초도 평위원회에서 유양석 초대 회장 선출</p></li>
                    <li><span>1975.12</span> <p>“대한치과임플란트학회”  창립 발기 준비위원 모임</p></li>
                </ul>
            </div>
        </div>
     
        

  </div>
</div>


<script>
gsap.registerPlugin(ScrollTrigger);

const hisWrap = document.querySelector('.his_wrap');
const line = document.querySelector('.line');
const filledLine = document.querySelector('.filled-line');
const dot = document.querySelector('.dot');

const totalHeight = line.offsetHeight;
const minHeight = 200;
const maxFillable = totalHeight - minHeight;

function updateProgress(self) {
  const scrollY = self.scroll();
  const scrollStart = self.start;
  const scrollEnd = self.end;

  const scrollProgress = (scrollY - scrollStart) / (scrollEnd - scrollStart);
  const clampedProgress = Math.min(Math.max(scrollProgress, 0), 1);

  const currentHeight = minHeight + (maxFillable * clampedProgress);

  filledLine.style.height = `${currentHeight}px`;
  dot.style.top = `${currentHeight}px`;
}

const trigger = ScrollTrigger.create({
  trigger: hisWrap,
  start: "top top",
  end: "bottom bottom",
  scrub: true,
  onUpdate: updateProgress,
});

// ✅ 강제로 한 번 실행
updateProgress(trigger);


</script>


<?php
include_once(G5_THEME_PATH.'/tail.php');
