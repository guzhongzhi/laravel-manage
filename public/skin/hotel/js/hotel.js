
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
});
