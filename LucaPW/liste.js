$(document).ready(function() {

    // Ascundem sublistele
    $('.sublista').hide();

    $('.titlu-expandabil').off('click').on('click', function(e) {
        e.preventDefault();


        const $sublista = $(this).siblings('.sublista');
        const $parinte = $(this).parent();


        if ($sublista.is(':visible')) {
            $sublista.stop(true, true).slideUp('fast');
            $parinte.removeClass('deschis');
        } else {
            $sublista.stop(true, true).slideDown('fast');
            $parinte.addClass('deschis');
        }
    });

});