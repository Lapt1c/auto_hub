
$(document).ready(function() {

    // Selectam formularele
    const $formAdmin = $('#form-admin');
    const $formRezervare = $('#form-rezervare');

    // functii de afisare erori
    function afiseazaEroare($element, mesaj) {
        $element.addClass('eroare-validare');

        // Cream un mesaj de eroare
        const $mesaj = $('<span></span>')
            .addClass('mesaj-eroare-text')
            .text(mesaj);

        $element.after($mesaj);
    }

    // curatam erori anterioare
    function curataErori() {
        $('.eroare-validare').removeClass('eroare-validare');
        $('.mesaj-eroare-text').remove();
    }

    // pt formularul de administrare
    if ($formAdmin.length > 0) {
        $formAdmin.on('submit', function(e) {
            let esteValid = true;
            curataErori();

            // Validare marca
            const $marca = $('#select-marca');
            if ($marca.val() === "") {
                afiseazaEroare($marca, "Te rugăm să selectezi o marcă.");
                esteValid = false;
            }

            // validare pret
            const $pret = $('input[name="pret"]');
            if (parseFloat($pret.val()) <= 0 || $pret.val() === "") {
                afiseazaEroare($pret, "Prețul trebuie să fie mai mare decât 0.");
                esteValid = false;
            }

            // Daca avem erori oprim trimiterea formularului
            if (!esteValid) {
                e.preventDefault();
                alert("Există erori în formular. Te rugăm să le corectezi.");
            } else {
                alert("Datele vehiculului au fost salvate cu succes!");
            }
        });
    }

    // Formularul de rezervare
    if ($formRezervare.length > 0) {
        $formRezervare.on('submit', function(e) {
            let esteValid = true;
            curataErori();

            const $nume = $('input[name="nume_client"]');
            ///Numele min 3 caractere
            if ($nume.val().trim().length < 3) {
                afiseazaEroare($nume, "Numele trebuie să aibă cel puțin 3 caractere.");
                esteValid = false;
            }
            //checkbox bifat
            const $termeni = $('input[name="termeni"]');
            if (!$termeni.is(':checked')) {
                alert("Trebuie să accepți termenii și condițiile.");
                esteValid = false;
            }

            if (!esteValid) {
                e.preventDefault();
            } else {
                alert("Programarea a fost trimisă!");
            }
        });
    }
});