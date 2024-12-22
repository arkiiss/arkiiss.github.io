$('.slick-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true,
    prevArrow: $('.prev'),
    nextArrow: $('.next'),
    responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                slidesToShow: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                slidesToShow: 1
            }
        }
    ]
});
$('.slick-slider-two').slick({
    slidesToShow: 4,
    autoplay: true,
    autoplaySpeed: 2000,
    accessibility: false,
    responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                slidesToShow: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                slidesToShow: 2
            }
        }
    ]
});
$('.slick-slider-three').slick({
    slidesToShow: 4,
    autoplay: true,
    autoplaySpeed: 2500,
    accessibility: false,
    responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                slidesToShow: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                slidesToShow: 2
            }
        }
    ]
});

$(function() {

  
    $('.dropdown-toggle').on('mouseover', function(e) {
        $(this) 
            .parents('.dropdown') 
            .addClass('show'); 
        $(this)
            .siblings('.dropdown-menu-wrapper') 
            .find('.dropdown-menu')
            .addClass('show');
    })

   
    $('.dropdown-toggle').on('mouseout', function(e) {
        $(this).parents('.dropdown').removeClass('show');
        $(this).siblings('.dropdown-menu-wrapper').find('.dropdown-menu').removeClass('show');
    })
})


