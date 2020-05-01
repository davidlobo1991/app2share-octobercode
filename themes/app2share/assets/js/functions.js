$('.slider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-nav',
    autoplay: true
});
$('.slider-nav').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.slider-for',
    dots: false,
    centerMode: true,
    focusOnSelect: true
});

$('.slick-home').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
});


$(".how-works a").on('click', function (e) {
    $(this).find('.modal-close').toggleClass('active');
    $(this).siblings('.modal-works').toggleClass('active');
});


