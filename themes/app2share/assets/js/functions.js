$(".how-works a").on('click', function (e) {
    $(this).find('.modal-close').toggleClass('active');
    $(this).siblings('.modal-works').toggleClass('active');
});
