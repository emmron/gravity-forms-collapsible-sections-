
jQuery(document).ready(function($) {
    $('.gf-section-start').on('click', function() {
        $(this).nextUntil('.gf-section-end').slideToggle(300);
    });
});
