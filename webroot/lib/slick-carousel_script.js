$(document).ready(function () {
    $('.slick-slider').slick({
        arrows: false,
        dots: true,
        pauseOnDotsHover: true,
        // dotsClass: 'banner__slick-dots-indicators' ,
        fade: false,
        pauseOnFocus: false,
        pauseOnHover: false,
        zIndex: 1000,
        waitForAnimate: false,
        // appendArrows: '.banner__arrow-left',
        // appendDots: '.banner__slick-dots',
        // prevArrow: '.banner__arrow-left',
        // nextArrow: '.banner__arrow-right',
        // swipe: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 1000,
        responsive: [
            {
                breakpoint: 900,
                settings:{

                }
            },
            {
                breakpoint: 600,
                settings:{

                }
            },
        ]
    });
});