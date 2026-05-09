
$(document).ready(function() {

    //1 Dependenta marca model
    const $selectMarca = $('#select-marca');
    const $selectModel = $('#select-model');

    // Verificaam daca avem date si exista elemente
    if ($selectMarca.length > 0 && $selectModel.length > 0 && typeof dateAuto !== 'undefined') {

        // Populam primul select
        $.each(dateAuto, function(marca, modele) {
            //creez elementul si il adaug intr un singur rand
            $selectMarca.append($('<option></option>').val(marca).text(marca));
        });

        // Ascultam evenimentul change
        $selectMarca.on('change', function() {
            const marcaSelectata = $(this).val(); // preluam valoarea

            //Golim selectul secundar
            $selectModel.empty().append('<option value="">-- Selectează Modelul --</option>');

            if (marcaSelectata !== "") {
                $selectModel.prop('disabled', false);

                // parcurgem modelele pt marca selectata
                $.each(dateAuto[marcaSelectata], function(index, model) {
                    $selectModel.append($('<option></option>').val(model).text(model));
                });
            } else {
                // daca nu am selectat marca golim iar
                $selectModel.empty().append('<option value="">-- Alegeți întâi marca --</option>');
                $selectModel.prop('disabled', true);
            }
        });
    }

    //2. Dependenta an fabricatie calendar
    const $inputAn = $('#an-fabricatie');
    const $inputData = $('#data-intrare');

    if ($inputAn.length > 0 && $inputData.length > 0) {

        $inputAn.on('input', function() {
            const anIntrodus = parseInt($(this).val());

            if (!isNaN(anIntrodus) && anIntrodus >= 1990 && anIntrodus <= 2026) {
                const minDate = `${anIntrodus}-01-01`;

                // modificăm atributul HTML 'min'
                $inputData.attr('min', minDate);

                // Verificăam daca data curenta e valida
                const dataSelectata = $inputData.val();
                if (dataSelectata && dataSelectata < minDate) {
                    $inputData.val(""); // golim campul
                    alert(`Atenție! Data intrării în stoc nu poate fi înainte de anul fabricației (${anIntrodus}).`);
                }
            } else {
                // Daca anul e sters  stergem restrictia
                $inputData.removeAttr('min');
            }
        });

        // declansam evenimentul input pt initializare
        $inputAn.trigger('input');
    }

});