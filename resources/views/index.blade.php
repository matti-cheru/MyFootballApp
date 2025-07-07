@extends('layouts.master') 

@section('title', 'My Football Centre')

@section('active_home','active')

{{-- Il breadcrumb e l'header della sezione non sono visibili su questa pagina,
     in quanto il carosello è a schermo intero come sfondo. --}}
@section('breadcrumb')
@endsection

@section('body')
<div id="backgroundCarousel" class="carousel slide carousel-fade background-carousel" data-bs-touch="false" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ url('/') }}/img/slide3.jpg" class="d-block w-100 carousel-image" alt="Prenota il tuo campo con semplicità.">
        </div>

        <div class="carousel-item">
            <img src="{{ url('/') }}/img/slide2.jpg" class="d-block w-100 carousel-image" alt="Gestisci al meglio i campi, le prenotazioni e i giocatori.">
        </div>

        <div class="carousel-item">
            <img src="{{ url('/') }}/img/slides.jpg" class="d-block w-100 carousel-image" alt="Assicuriamo campi e strutture curate al dettaglio per il tuo divertimento.">
        </div>
    </div>

    {{-- I controlli delle frecce non sono visibili perché il carosello va da solo,
         ma li lasciamo per la funzionalità automatica di Bootstrap. --}}
    <button class="carousel-control-prev" type="button" data-bs-target="#backgroundCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#backgroundCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

{{-- Contenuto in primo piano sopra il carosello --}}
<div class="overlay-content">
    <div class="carousel-indicators-overlay">
        <button type="button" data-bs-target="#backgroundCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#backgroundCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#backgroundCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    {{-- Ho creato un div per ogni slide per gestire testo e bottone --}}
    <div class="slide-content active-slide-content">
        <h5 class="animate-text">PRENOTAZIONE CAMPI ONLINE</h5>
        <p class="animate-text">Se non l'hai già fatto, registrati e prenota un campo per la tua partita.</p>
        @if(!isset($_SESSION["logged"]) || !$_SESSION["logged"])
        <a href="{{ route('user.login') }}" class="btn btn-primary btn-lg mt-3 animate-button">Accedi</a>
        @endif
    </div>

    <div class="slide-content">
        <h5 class="animate-text">SERVIZIO DI PRENOTAZIONE CAMPI SPORTIVI</h5>
        <p class="animate-text">Semplifica la gestione ed agevola i Giocatori....</p>
        @if(!isset($_SESSION["logged"]) || !$_SESSION["logged"])
        <a href="{{ route('user.login') }}" class="btn btn-primary btn-lg mt-3 animate-button">Accedi</a>
        @endif
    </div>

    <div class="slide-content">
        <h5 class="animate-text">BUON DIVERTIMENTO</h5>
        <p class="animate-text">Assicuriamo campi e strutture curate al dettaglio.</p>
        @if(!isset($_SESSION["logged"]) || !$_SESSION["logged"])
        <a href="{{ route('user.login') }}" class="btn btn-primary btn-lg mt-3 animate-button">Accedi</a>
        @endif
    </div>
</div>

<script>
    $(document).ready(function() {
        var backgroundCarousel = $('#backgroundCarousel');
        var slideContents = $('.slide-content');
        var carouselIndicatorsOverlay = $('.carousel-indicators-overlay button');

        // Ascolta l'evento 'slid.bs.carousel' per sincronizzare i testi
        backgroundCarousel.on('slid.bs.carousel', function () {
            var currentIndex = $('div.carousel-item.active').index();
            
            // Rimuovi la classe 'active-slide-content' e le animazioni da tutti i contenuti
            slideContents.removeClass('active-slide-content');
            slideContents.find('.animate-text, .animate-button').removeClass('animated fadeInRight fadeIn');

            // Aggiungi la classe 'active-slide-content' al contenuto corrente
            slideContents.eq(currentIndex).addClass('active-slide-content');

            // Ri-applica le animazioni al contenuto corrente dopo un breve ritardo
            setTimeout(function() {
                slideContents.eq(currentIndex).find('h5, p').addClass('animated fadeInRight');
                slideContents.eq(currentIndex).find('.btn').addClass('animated fadeIn');
            }, 50); // Piccolo ritardo per l'animazione

            // Aggiorna gli indicatori nell'overlay
            carouselIndicatorsOverlay.removeClass('active').eq(currentIndex).addClass('active');
        });

        // Gestisci il click sugli indicatori dell'overlay per cambiare slide del carosello
        carouselIndicatorsOverlay.on('click', function() {
            var slideTo = $(this).data('bs-slide-to');
            backgroundCarousel.carousel(slideTo);
        });

        // Animazioni iniziali per la prima slide
        $('.slide-content.active-slide-content').find('h5, p').addClass('animated fadeInRight');
        $('.slide-content.active-slide-content').find('.btn').addClass('animated fadeIn');
    });
</script>
@endsection