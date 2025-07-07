@extends('layouts.delete') @section('title','MyFootballApp :: Error Page')

@section('active_MyLibrary','active')

@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('home') }}">Home</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('field.index') }}">My Centre</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('field.index') }}">Fields</a></li>
<li class="breadcrumb-item active" aria-current="page">Delete field</li>
@endsection

@section('body')

<div class="container-fluid text-center mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-danger shadow-lg rounded-lg">
                <div class='card-header bg-danger text-white py-3'>
                    <h3 class="fw-light my-2 mb-0"><i class="bi bi-exclamation-octagon-fill me-2"></i>Accesso alla Pagina Non Consentito</h3> {{-- Titolo H3 più leggero, con icona e margin verticale --}}
                </div>
                <div class='card-body p-4'>
                    <p class="lead text-danger fw-bold mb-3">
                        Qualcosa **è andato storto** durante l'accesso a questa pagina!
                    </p>
                    <p class="text-muted fs-5">
                        {{ $message }}
                    </p>
                    <hr class="my-4">
                    <p>
                        <a class="btn btn-danger btn-lg px-4" href="{{ route('home') }}">
                            <i class="bi bi-box-arrow-left me-2"></i> Torna alla Home
                        </a>
                    </p>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <small class="text-muted">MyFootballApp &copy; 2025</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection