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

        $mobileMenuToggle.on('click', function() {
            $(this).toggleClass('active');
        $mobileMenu.toggleClass('active');
        });

        var $headerLink = $('.hdr-logo-link');

        // $headerLink.lettering();

        // var $headerLinkLetters = $headerLink.find('span');

        // var headerLinkLetters = [];

        // headerLinkLetters['the'] = $headerLink.find('.char1').nextUntil('.char4').andSelf();

        // headerLinkLetters['timnormington'] = $headerLink.find('.char3').nextUntil();

        // headerLinkLetters['the'].wrapAll('<div></div>');
        // headerLinkLetters['timnormington'].wrapAll('<div></div>');

        // $headerLinkLetters.on('mouseenter touchmove', function() {
        //     var $letter = $(this);
        //     $letter.addClass('flip');
        //     window.setTimeout(function() {
        //         $letter.removeClass('flip');
        //     }, 1000);
        // });

        // $headerLink.on('mouseenter touchmove', function() {
        //     $(this).addClass('flip');
        //     // window.setTimeout(function() {
        //     //     $letter.removeClass('flip');
        //     // }, 1000);
        // });

        // console.log(window.sessionStorage.getItem('flipped'));

        // if(window.sessionStorage.getItem('flipped')) {
        //     $headerLink.addClass('flip');
        // }

        // $headerLink.on('click', function() {
        //     console.log(window.sessionStorage.getItem('flipped'));
        //     $(this).toggleClass('flip');
        //     window.sessionStorage.setItem('flipped', !window.sessionStorage.getItem('flipped'));
        // });



    });
})(jQuery);