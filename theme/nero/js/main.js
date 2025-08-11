$("document").ready(function () {
  var swiperAnimation = new SwiperAnimation();
var mainswiper = new Swiper('.swiper_visual', {
loop: true,
effect: 'fade',
speed: 1000,
    //autoplay: {
    //delay: 5000,
    //disableOnInteraction: false,
    //},
on: {
  slideChange: function() {
  },
  slideChangeTransitionStart: function() {
    progressbar();
  },
  slideChangeTransitionEnd: function() {
    prev_url=$(".swiper_visual .swiper-slide-prev").data("value");
    next_url=$(".swiper_visual .swiper-slide-next").data("value");
    $("#thumbPrev").html('<img src="'+prev_url+'">');
    $("#thumbNext").html('<img src="'+next_url+'">');
  },
  init: function () {
    swiperAnimation.init(this).animate();
  },
  slideChange: function () {
    swiperAnimation.init(this).animate();
  }
},

pagination: {
  el: '.swiper-pagination',
  type: 'custom',
  renderCustom: function (swiper, current, total) {
    if(total<10){
    current = '0' + current;
    total = '0'+ total;
  }
    return '<span class="current">'+('0' + current).slice(-2) + ' </span><span class="total"> ' + ('0' + total).slice(-2)+'</span>';
  }
    /**
  clickable: true,
  renderBullet: function (index, className) {
    return '<span class="' + className + '">' + (index + 1) + '</span>';
  },
  */

},
navigation: {
  nextEl: '.swiper-button-next',
  prevEl: '.swiper-button-prev',
},

});

// 슬라이더 할당한 swiper로 슬라이더 제어
$(".auto-start").on("click", function() {
    // 기본 설정으로 autoplay 시작
    mainswiper.autoplay.start();
mainslide_flag=true;
$(".auto-start").hide();
$(".auto-stop").show();

});

$(".auto-stop").on("click", function() {
    mainswiper.autoplay.stop();
mainslide_flag=false;
$(".auto-start").show();
$(".auto-stop").hide();
});

var tick;
var mainslide_flag=false;
function progressbar(){
var  w=0;
function startProgressBar() {
  resetProgressBar();
  tick = setInterval(progress, 50);
}

function progress() {
  if ( mainswiper.autoplay.running && !mainswiper.autoplay.paused ) {
    //console.log("runing");
    w=w+1;
    if (w > 100) {
      resetProgressBar();
    }else{
      $(".slide_progress-bar").css("width",w+"%");
    }
  }
  if ( mainswiper.autoplay.paused || mainslide_flag ) {
    $(".slide_progress-bar").css("width","0%");
    w=0;
    mainslide_flag=false;
  }


}

function resetProgressBar() {
  $(".slide_progress-bar").css("width","0%");
  w=0;
  mainslide_flag=false;
  clearInterval(tick);
}

startProgressBar();
}


});


$("document").ready(function () {
  var swiper = new Swiper('.products_list_wrap', {
    slidesPerView: 4,
    // freeMode: true,
      loop: true,
    // slidesPerView: 'auto',
    spaceBetween: 20,
    autoplay: {
        delay:3000,
        disableOnInteraction: false,
    },

     navigation: {
      nextEl: '.product_next',
      prevEl: '.product_prev',
      },
       breakpoints: {
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      320: {
        slidesPerView: 1,
        spaceBetween: 10,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 20,
      }
    }
  })
});
