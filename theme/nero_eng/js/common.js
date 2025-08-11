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
        if ($(window).scrollTop() >= 10) {
            $("#header").addClass("scroll");
            $("#header .head_menu .main_menu .dept1 > a ").addClass("color");
        } else {
            $("#header").removeClass("scroll");
            $("#header .head_menu .main_menu .dept1 > a ").addClass("color");
        }
    }, 400);
});

$(document).ready(function(){
  var mobile = false;
  $(".mobile_open").click(function(){
    if(mobile == false){
      $("#mobile_menu").css('display','block').stop().animate({right: 0}, 'slow');
      $(".mob_bg").stop().fadeIn(400);
      mobile = true;
    }
  });
  $(".mobile_close").click(function(){
    if(mobile == true){
      $("#mobile_menu").stop().animate({right: '-' + 100 + '%'}, 'slow');
      $(".mob_bg").stop().fadeOut(400);
      mobile = false;
    }
  });
  $(".mob_bg").click(function(){
    if(mobile == true){
      $("#mobile_menu").stop().animate({right: '-' + 100 + '%'}, 'slow');
      $(".mob_bg").stop().fadeOut(400);
      mobile = false;
    }
  });
  var subMenu = -1;
	$("#mobile_menu .mob_menu .sub_menu").slideUp(0);
	$("#mobile_menu .mob_menu .top_menu").removeClass("on");
	$("#mobile_menu .mob_menu .top_menu").each(function(q){
  var isNoSubMenu = $(this).hasClass("no-submenu");
  if (!isNoSubMenu) {
    $(this).click(function(e){
      e.preventDefault();
      if (subMenu != q) {
        $("#mobile_menu .mob_menu .top_menu").eq(subMenu).removeClass("on");
        $("#mobile_menu .mob_menu .sub_menu").eq(subMenu).stop().slideUp('fast');
        subMenu = q;
        $("#mobile_menu .mob_menu .top_menu").eq(subMenu).addClass("on");
        $("#mobile_menu .mob_menu .sub_menu").eq(subMenu).stop().slideDown('fast');
      } else {
        $("#mobile_menu .mob_menu .top_menu").eq(subMenu).removeClass("on");
        $("#mobile_menu .mob_menu .sub_menu").eq(subMenu).stop().slideUp('fast');
        subMenu = -1;
      }
    });
  }
});
});
