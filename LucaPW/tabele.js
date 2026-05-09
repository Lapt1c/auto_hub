
$(document).ready(function() {

    // tabel dashboard
    const $corpTabelFlota = $('#corp-tabel-flota');
    const $anteteTabelFlota = $('#tabel-flota th.sortabil');
    let directieSortareClasic = 1;

    if ($corpTabelFlota.length > 0 && typeof dateFlota !== 'undefined') {

        function afiseazaTabelClasic(date) {
            $corpTabelFlota.empty(); // Golim tabelul

            $.each(date, function(index, rand) {
                let statusBadge = rand.status === "Disponibil" ? "badge-verde" : (rand.status === "Rezervat" ? "badge-galben" : "badge-rosu");

                // cream randul
                const randHTML = `
                    <tr>
                        <td>${rand.ora}</td>
                        <td><strong>${rand.model}</strong></td>
                        <td><span class="badge ${statusBadge}">${rand.status}</span></td>
                        <td>${rand.client}</td>
                        <td>${rand.actiune}</td>
                    </tr>
                `;
                $corpTabelFlota.append(randHTML); //inseram randul
            });
        }

        afiseazaTabelClasic(dateFlota);

        // Adaugam evenimentul de click pe toate antetele sortabile
        $anteteTabelFlota.on('click', function() {
            const $th = $(this);
            const proprietate = $th.data('prop'); //preluam atributul

            $anteteTabelFlota.removeClass('sort-asc sort-desc');
            directieSortareClasic *= -1;
            $th.addClass(directieSortareClasic === 1 ? 'sort-asc' : 'sort-desc');

            dateFlota.sort((a, b) => {
                let valA = a[proprietate].toLowerCase();
                let valB = b[proprietate].toLowerCase();
                if (valA < valB) return -1 * directieSortareClasic;
                if (valA > valB) return 1 * directieSortareClasic;
                return 0;
            });

            //Efect de animație la sortare
            $corpTabelFlota.fadeOut(150, function() {
                afiseazaTabelClasic(dateFlota);
                $(this).fadeIn(150);
            });
        });
    }


    // tabel vertical catalog

    const $containerVertical = $('#container-tabel-vertical');
    let directieSortareVertical = 1;

    if ($containerVertical.length > 0 && typeof dateComparatie !== 'undefined') {

        function afiseazaTabelVertical() {
            $containerVertical.empty();
            const $tabel = $('<table class="tabel-vertical"></table>');

            const structuraRanduri = [
                { cheie: "model", titlu: "Model Auto", sortabil: false },
                { cheie: "pret", titlu: "Preț (€)", sortabil: true },
                { cheie: "putere", titlu: "Putere (CP)", sortabil: true },
                { cheie: "portbagaj", titlu: "Portbagaj (Litri)", sortabil: true }
            ];

            $.each(structuraRanduri, function(index, randConfig) {
                const $tr = $('<tr></tr>');
                const $th = $('<th class="header-vertical"></th>').text(randConfig.titlu);

                if (randConfig.sortabil) {
                    $th.addClass('sortabil').data('prop', randConfig.cheie);
                    $th.on('click', function() { sorteazaVertical(randConfig.cheie); });
                }
                $tr.append($th);

                $.each(dateComparatie, function(i, masina) {
                    const continut = randConfig.cheie === "model" ? `<strong>${masina[randConfig.cheie]}</strong>` : masina[randConfig.cheie];
                    const $td = $('<td></td>').html(continut);
                    $tr.append($td);
                });
                $tabel.append($tr);
            });
            $containerVertical.append($tabel);
        }

        function sorteazaVertical(proprietate) {
            directieSortareVertical *= -1;
            dateComparatie.sort((a, b) => {
                if (a[proprietate] < b[proprietate]) return -1 * directieSortareVertical;
                if (a[proprietate] > b[proprietate]) return 1 * directieSortareVertical;
                return 0;
            });

            // Animatie
            $containerVertical.fadeOut(150, function() {
                afiseazaTabelVertical();
                setTimeout(() => {
                    $containerVertical.find('th.sortabil').each(function() {
                        if($(this).data('prop') === proprietate) {
                            $(this).addClass(directieSortareVertical === 1 ? 'sort-asc' : 'sort-desc');
                        }
                    });
                }, 10);
                $(this).fadeIn(150);
            });
        }

        afiseazaTabelVertical();
    }
});