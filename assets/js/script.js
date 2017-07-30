(function($) {
    $(document).ready(function() {

        var $headerSvg = $('#header-svg');

        var $headerSvgMobile = $('#header-svg-mobile');

        console.log($headerSvg);
        if($headerSvg[0].clientHeight) {
            var parallaxHeader = new Parallax($headerSvg[0]);
        } else {
            var parallaxHeader = new Parallax($headerSvgMobile[0]);
        }


        var $mobileMenuToggle = $('#mobile-menu-toggle');
        var $mobileMenu = $('#nav-main');
        var $headerLink = $('.hdr-logo-link');

        $mobileMenuToggle.on('click', function() {
            $(this).toggleClass('active');
            $mobileMenu.toggleClass('active');
            $headerLink.toggleClass('flip');
        });


        var $vectorPortraitProductSteps = $('.post-323 .wccpf_fields_table');
        var $vectorPortraitAddToCart = $('.post-323 [type=submit]');

        // console.log($vectorPortraitAddToCart);

        $vectorPortraitAddToCart.prop('disabled', true);

        // Give first step active class
        $vectorPortraitProductSteps.first().addClass('active');


        var $stepOne = $vectorPortraitProductSteps.first();
        var $stepOneInput = $stepOne.find('input');

        // Loop through each product step and setup event handlers with closure
        for(var i = 0; i < $vectorPortraitProductSteps.length; i++) {
            (function(i) {
                $($vectorPortraitProductSteps[i]).find('input, textarea').on('change', function() {
                    if($(this).val().length) {
                        progressProductSteps(i);
                        // If this is step 2, light up the submit button
                        if(i >= 0) {
                            $vectorPortraitAddToCart.addClass('active').prop('disabled', false);
                        }
                    }
                });
            })(i);
        }
        

        // $stepOneInput.on

        // $vectorPortraitProductSteps.last().on('change', function() {
        // });



        function progressProductSteps(i) {
            // Using the index of the current step, remove the active class,
            // and then add active to the next step
            $($vectorPortraitProductSteps[i]).removeClass('active').addClass('valid').next().addClass('active');
        }


        // SINGLE PRODUCT SLIDER
        var $productGallery = $('.single-product__gallery');

        $productGallery.slick({
            infinite: true,
            speed: 2000,
            autoplay: true,
            dots: false,
            arrows: false,
            autoplaySpeed: 0,
            slidesToShow: 8,
            cssEase: 'linear',
            slidesToScroll: 1,
            pauseOnFocus: false,
            pauseOnHover: false,
            responsive: [
                {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 6,
                }
                },
                {
                breakpoint: 600,
                settings: {
                    slidesToShow: 4,
                }
                },
                {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });


    });
})(jQuery);