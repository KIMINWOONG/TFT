$(document).ready(function() {
    var gnb = $('#mild_menu');

    $('.dept1').mouseenter(function() {
        let subMenu = $(this).find('.sub_menu');
    
        subMenu.stop(true, true).css({
            display: 'block',
            opacity: 0,
            transform: 'translateY(0px)' // 아래에서 시작
        }).animate({
            opacity: 1,
            transform: 'translateY(0)' // 자연스럽게 위로 올라옴
        }, 300);
    });
    
    $('.dept1').mouseleave(function() {
        $(this).find('.sub_menu').stop(true, true).animate({
            opacity: 0,
            transform: 'translateY(0px)' // 다시 아래로 사라지는 느낌
        }, 300, function() {
            $(this).css('display', 'none');
        });
    });
    
      // 기존 전체 메뉴 보이게 하는 이벤트 삭제
  /* 햄버거 메뉴 */
  $('.menu_bar').click(function(){
    $(this).toggleClass('active');
    $('.menu_open').slideToggle();
  });
});

$(function() {
    setInterval(function() {
        if ($(window).scrollTop() <= 0) {
            // 스크롤이 맨 위에 있을 때
            $("#header").addClass("scroll");
            $("#header .head_menu .main_menu .dept1 > a").addClass("color");
        } else {
            // 조금이라도 스크롤 내리면
            $("#header").removeClass("scroll");
            $("#header .head_menu .main_menu .dept1 > a").removeClass("color");
        }
    }, 100);
});

$(document).ready(function(){
  var mobile = false;

  $(".mobile_open").click(function(){
    if (!mobile) {
      $("#mobile_menu").css('display','block').stop().animate({right: 0}, 'slow');
      $(".mob_bg").stop().fadeIn(400);
      mobile = true;
    }
  });

  $(".mobile_close, .mob_bg").click(function(){
    if (mobile) {
      $("#mobile_menu").stop().animate({right: '-100%'}, 'slow');
      $(".mob_bg").stop().fadeOut(400);
      mobile = false;
    }
  });

  var subMenu = -1;
  $("#mobile_menu .mob_menu .sub_menu").slideUp(0);
  $("#mobile_menu .mob_menu .top_menu").removeClass("on");

  $("#mobile_menu .mob_menu .top_menu").each(function(q){
    var isNoSubMenu = $(this).hasClass("no-submenu");
    var link = $(this).attr("href");
    var target = $(this).attr("target");

    if (!isNoSubMenu) {
      // 서브메뉴가 있는 경우 토글
      $(this).click(function(e){
		console.log(subMenu+":"+q);
        e.preventDefault();
        if (subMenu !== q) {
          $("#mobile_menu .mob_menu .top_menu").eq(subMenu).removeClass("on");
          $("#mobile_menu .mob_menu .sub_menu").eq(subMenu).stop().slideUp('fast');
          subMenu = q;
          $(this).addClass("on");
		  $(this).next().stop().slideDown('fast');
          //$("#mobile_menu .mob_menu .sub_menu").eq(subMenu).stop().slideDown('fast');
        } else {
          $(this).removeClass("on");
		  $(this).next().stop().slideUp('fast');
          //$("#mobile_menu .mob_menu .sub_menu").eq(subMenu).stop().slideUp('fast');
          subMenu = -1;
        }
      });
    } else {
      // 서브메뉴가 없을 경우 바로 이동 (내부든 외부든)
      $(this).click(function(e){
        // 외부링크면 새 창, 아니면 현재 창 이동
        if (target === "_blank") {
          window.open(link, "_blank");
        } else {
          location.href = link;
        }
      });
    }
  });
});
