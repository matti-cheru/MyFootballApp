<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

        <link rel="icon" type="image/png" href="{{ url('/') }}/img/ucl.png">

        <link rel="stylesheet" href="{{ url('/') }}/css/bootstrap.min.css">
        <link href="{{ url('/') }}/css/style.css" rel="stylesheet">
        <link href="{{ url('/') }}/css/delete.css" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="{{ url('/') }}/js/bootstrap.min.js"></script>

        <script src="{{ url('/') }}/js/paginationScript.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top" id="mainNavbar">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">MyFootballApp</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link @yield('active_home')" aria-current="page" href="{{ route('home')}}">Home</a>
                        </li>
                        @if((isset($_SESSION["logged"]))&&($_SESSION["logged"]))
                            @if($_SESSION["role"] === "registered_player")
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownOurFields" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        I nostri Campi
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownOurFields">
                                        <li><a class="dropdown-item" href="{{ route('field.index') }}">Lista dei Campi</a></li>
                                        <li><a class="dropdown-item" href="{{ route('reservation.create') }}">Prenota un Campo</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMyReservations" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Le mie Prenotazioni
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMyReservations">
                                        <li><a class="dropdown-item" href="{{ route('reservation.index') }}">Prenotazioni in Attesa</a></li>
                                        <li><a class="dropdown-item" href="{{ route('users.reservations.history', ['id' => $_SESSION['loggedID']]) }}">Le mie Prenotazioni Effettuate</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if($_SESSION["role"] === "admin")
                                <li class="nav-item"><a class="nav-link" href="{{ route('field.index') }}">Gestisci i Campi</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('reservation.index') }}">Gestisci le Prenotazioni</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Gestisci gli Utenti</a></li>
                            @endif
                        @endif
                    </ul>

                    <ul class="navbar-nav right-aligned-links">
                        @if(empty($_SESSION['logged']))
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light nav-login-btn" href="{{ route('user.login') }}">
                                <i class="bi bi-person-check-fill me-2"></i> Accedi
                            </a>
                        </li>
                        @else
                        <li class="nav-item welcome-message">
                            <i>Benvenuto **{{ $_SESSION['loggedName'] }}**</i>
                            <a href="{{ route('user.logout') }}" class="btn btn-outline-light nav-logout-btn ms-2">
                                <i class="bi bi-box-arrow-right me-2"></i> Esci
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="page-header-content bg-light @if(Request::routeIs('home')) d-none @endif">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <header class="header-sezione">
                            <h1 class="text-dark">
                                @yield('page_title_h1')
                            </h1>
                        </header>
                    </div>
                </div>
            </div>
        </div>

        <main class="content-wrapper">
            @yield('body')
        </main>

        <footer class="footer py-3 bg-dark text-white">
            <div class="container text-center">
                <p class="mb-0">&copy; {{ date('Y') }} MyFootballApp. Author: Mattia Cherubini</p>
            </div>
        </footer>

        {{-- Modali per messaggi di successo/errore (se vuoi mantenerli anche in questo layout) --}}
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">