$(document).ready(function(){
    $.request('onCountdownDate',{
        success: function(data){
            $('#countdown').countdown(data.date).on('update.countdown', function(event) {
                var countdown = $(this).html(event.strftime(''
                    + '<span>%-w</span> %!w:week,weeks; '
                    + '<span>%-d</span> %!d:day,days; '
                    + '<span>%H</span> hours '
                    + '<span>%M</span> min '
                    + '<span>%S</span> sec'));

                $('#countdown').addClass('show');
            });
        }
    })
});
