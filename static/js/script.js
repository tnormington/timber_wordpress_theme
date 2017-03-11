(function($) {
    $(document).ready(function() {

        var headerSvg = document.getElementById('header-svg');

        var parallaxHeader = new Parallax(headerSvg);

        var $headerLink = $('.hdr-logo-link');

        $headerLink.lettering();

        var $headerLinkLetters = $headerLink.find('span');

        $headerLinkLetters.on('mouseenter', function() {
            var $letter = $(this);
            $letter.addClass('flip');
            window.setTimeout(function() {
                $letter.removeClass('flip');
            }, 1000);
        });

    });
})(jQuery);