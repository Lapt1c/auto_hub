$(document).ready(function() {

    const $containerCarusel = $('#carusel-oferte');

    // verificam daca elementul exista
    if ($containerCarusel.length === 0) return;

    const slideuri = [
        {
            imagine: "https://images.unsplash.com/photo-1604395924490-a3a18bb7193e?q=80&w=1302&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            link: "https://www.dacia.ro",
            text: "🚗 Descoperă noul Dacia Duster 2024 - Ofertă limitată la închiriere!"
        },
        {
            imagine: "https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=1200&q=80",
            link: "#leasing",
            text: "🏢 Leasing Operațional pentru Flote Corporate - Cere Ofertă Acum"
        },
        {
            imagine: "https://images.unsplash.com/photo-1617704548623-340376564e68?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            link: "https://www.tesla.com/ro_ro",
            text: "⚡ Treci pe electric! Modele noi cu autonomie ridicată în stoc."
        },
        {
            imagine: "https://images.unsplash.com/photo-1605810230434-7631ac76ec81?auto=format&fit=crop&w=1200&q=80",
            link: "#premium",
            text: "🌟 Secțiunea VIP: Închirieri auto premium pentru evenimente speciale."
        }
    ];

    let indexCurent = 0;
    let intervalCarusel;
    const arrayElementeDOM = [];


    $.each(slideuri, function(index, slide) {

        // creez elementele, le pun clasele si stilurile
        const $divSlide = $('<div></div>')
            .addClass('carousel-slide')
            .css('background-image', `url('${slide.imagine}')`);

        if (index === 0) {
            $divSlide.addClass('activ');
        }

        const $divContent = $('<div></div>').addClass('carousel-content');

        // Setam atributele href, target
        const $link = $('<a></a>')
            .attr('href', slide.link)
            .attr('target', '_blank')
            .text(slide.text);//adaug si textul

        ///adaug link
        $divContent.append($link);
        $divSlide.append($divContent);


        $('#btn-prev').before($divSlide);

        arrayElementeDOM.push($divSlide);
    });

    function schimbaSlide(noulIndex) {
        // actualizam slideul curent
        arrayElementeDOM[indexCurent].removeClass('activ');
        indexCurent = noulIndex;
        arrayElementeDOM[indexCurent].addClass('activ');
    }

    function slideUrmator() {
        let urmatorul = (indexCurent === slideuri.length - 1) ? 0 : indexCurent + 1;
        schimbaSlide(urmatorul);
    }

    function slideAnterior() {
        let anteriorul = (indexCurent === 0) ? slideuri.length - 1 : indexCurent - 1;
        schimbaSlide(anteriorul);
    }

    function pornesteAutoPlay() {
        intervalCarusel = setInterval(slideUrmator, 3000);
    }

    function reseteazaAutoPlay() {
        clearInterval(intervalCarusel);
        pornesteAutoPlay();
    }

    // Adaug ascultatori pentru butoanele de navigare
    $('#btn-next').on('click', function() {
        slideUrmator();
        reseteazaAutoPlay();
    });

    $('#btn-prev').on('click', function() {
        slideAnterior();
        reseteazaAutoPlay();
    });

    pornesteAutoPlay();
});