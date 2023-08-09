
// Updated to use event delegation
jQuery(document).ready(function($) {
    var $document = $(document);
    $document.on('click', '.gf-section-start', function() {
        $(this).nextUntil('.gf-section-end').slideToggle(300);
    });
});
