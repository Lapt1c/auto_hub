
$(document).ready(function() {

    const $masiniDraggable = $('.masina-drag');
    const $zoneDrop = $('.zona-drop');

    const $contorDisponibil = $('#contor-disponibil');
    const $contorService = $('#contor-service');

    // Verificam daca exista elementele
    if ($contorDisponibil.length === 0 || $masiniDraggable.length === 0) return;

    function actualizeazaStatistici() {
        // numaram copii unui element
        const nrDisponibile = $('#zona-disponibil').find('.masina-drag').length;
        const nrService = $('#zona-service').find('.masina-drag').length;


        $contorDisponibil.text(nrDisponibile);
        $contorService.text(nrService);
    }

    // Atasam evenimentele pt masini
    $masiniDraggable.on('dragstart', function(e) {
        $(this).addClass('in-miscare');

        // extragem evenimentul pentru a folosi dataTransfer
        e.originalEvent.dataTransfer.setData('text/plain', this.id);
    });

    $masiniDraggable.on('dragend', function() {
        $(this).removeClass('in-miscare');
        actualizeazaStatistici();
    });

    // Atasam evenimentele pt zona drop
    $zoneDrop.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('hover-activ');
    });

    $zoneDrop.on('dragleave', function() {
        $(this).removeClass('hover-activ');
    });

    $zoneDrop.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('hover-activ');

        // extragem id-ul tot din evenimentul nativ
        const idMasina = e.originalEvent.dataTransfer.getData('text/plain');

        // Selectam masina
        const $elementMasina = $('#' + idMasina);

        if ($elementMasina.length > 0) {
            // mutam elementul in noua zona
            $(this).append($elementMasina);
        }
    });

});