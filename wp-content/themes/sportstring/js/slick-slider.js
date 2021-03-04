$('.team-images').slick({
  // autoplay: true,
  // autoplaySpeed: 4000,
  arrows: true,
  focusOnSelect: true,
  centerMode: true,
  // centerPadding: '60px',
  slidesToShow: 3,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1
      }
    }
  ]
});

$('.productsFront').slick({
  // autoplay: true,
  // autoplaySpeed: 4000,
  dots: true,
  arrows: false,
  focusOnSelect: true,
  centerMode: true,
  // centerPadding: '60px',
  slidesToShow: 3,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        centerMode: true
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        slidesToShow: 1
      }
    }
  ]
});

$(document).ready(function() {
  if (!$(this).hasClass('current-menu-item')) {
    // check if current page
    $(this)
      .find('.sub-menu')
      .hide(); // hide if not current page
  }
});

$('li.menu-item').hover(
  function() {
    // mouse enter
    $(this)
      .find(' > .sub-menu')
      .show(); // display immediate child
  },
  function() {
    // mouse leave
    // if (!$(this).hasClass('current-menu-item')) {
    // check if current page
    $(this)
      .find('.sub-menu')
      .hide(); // hide if not current page
    // }
  }
);

$(document).on('click', '.categories li', function() {
  $('.categories li').removeClass('active');
  $(this).addClass('active');
});

$(document).ready(function() {
  $('.questions li').click(function() {
    $('.questions li .questions-info-wrap').removeClass('active');
    $(this)
      .children('.questions-info-wrap')
      .addClass('active');
  });
});
