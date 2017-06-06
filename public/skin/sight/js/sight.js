$( document ).ready(function( $ ) {
    $( '#example5' ).sliderPro({
        width: '100%',
        height: 500,
        orientation: 'vertical',
        loop: false,
        arrows: true,
        buttons: false,
        thumbnailsPosition: 'right',
        thumbnailPointer: true,
        thumbnailWidth: 170,
        breakpoints: {
            800: {
                thumbnailsPosition: 'bottom',
                thumbnailWidth: 170,
                thumbnailHeight: 100
            },
            500: {
                thumbnailsPosition: 'bottom',
                thumbnailWidth: 120,
                thumbnailHeight: 50
            }
        }
    });
    $(".slider-pro").css("max-width","100%");


    $('.toggle_btn a').each(
        function(i){
            $(this).bind("click",{MenuIndex:i},function(event){
                var toggle_s = $(this).parent().parent().children(".toggle_s");
                var toggle_l = $(this).parent().parent().children(".toggle_l");
                if($(this).text() == "查看全部"){
                    toggle_s.hide();
                    toggle_l.show();
                    $(this).text('收起');
                }else{
                    toggle_s.show();
                    toggle_l.hide();
                    $(this).text('查看全部');
                }
            });
        }
    );

});
