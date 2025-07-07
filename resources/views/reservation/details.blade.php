@extends('layouts.master')

@section('title', 'Gestisci la Prenotazione')

@section('page_title_h1', 'Gestisci Prenotazione')

@section('active_MyCentre','active')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reservation.index')}}">Prenotazioni</a></li>
    <li class="breadcrumb-item active" aria-current="page">Gestisci una Prenotazione</li>
@endsection

@section('body')
<div class="container custom-container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">Dettagli Prenotazione e Azioni</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="mb-3 text-primary"><i class="bi bi-info-circle-fill me-2"></i>Informazioni sulla Prenotazione</h6>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <b><i class="bi bi-person-fill"></i> Utente:</b>
                        </div>
                        <div class="col-md-8">
                            {{ $reservation->user->email }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <b><i class="bi bi-geo-alt-fill"></i> Campo:</b>
                        </div>
                        <div class="col-md-8">
                            {{ $reservation->field->nome_campo }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <b><i class="bi bi-calendar-date-fill"></i> Data Prenotazione:</b>
                        </div>
                        <div class="col-md-8">
                            {{ \Carbon\Carbon::parse($reservation->data_prenotazione)->format('d/m/Y') }} {{-- Formattazione data --}}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <b><i class="bi bi-clock-fill"></i> Ora Prenotazione:</b>
                        </div>
                        <div class="col-md-8">
                            {{ \Carbon\Carbon::parse($reservation->ora_inizio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->ora_fine)->format('H:i') }} {{-- Formattazione orari --}}
                        </div>
                    </div>
                </div>

                <div class="col-md-4 border-start ps-4">
                    <h6 class="mb-3 text-primary"><i class="bi bi-gear-fill me-2"></i>Azioni</h6>
                    <div class="d-grid gap-2">
                        <form action="{{ route('reservation.accept', ['id' => $reservation->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 custom-btn-icon">
                                <i class="bi bi-check-circle me-2"></i> Accetta Prenotazione
                            </button>
                        </form>

                        <form action="{{ route('reservation.reject.confirm', ['id' => $reservation->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 custom-btn-icon">
                                <i class="bi bi-x-circle me-2"></i> Rifiuta Prenotazione
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <a class="btn btn-secondary custom-btn-icon" href="{{ route('reservation.index') }}">
                <i class="bi bi-arrow-left-circle me-2"></i> Torna alle Prenotazioni
            </a>
        </div>
    </div>
</div>
@endsection