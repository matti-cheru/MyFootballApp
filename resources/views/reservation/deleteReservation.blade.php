@extends('layouts.delete')

@section('title', 'MyFootball :: Annulla Prenotazione')

@section('body')
<div class="container custom-container mt-4 mb-5">
    <h1 class="text-center mb-4">Annulla Prenotazione</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white text-center">
            <h5 class="mb-0">Conferma Annullamento Prenotazione</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Sei sicuro di voler annullare questa prenotazione? Questa azione non può essere annullata.
            </div>

            <div class="card mb-3 border-secondary">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Dettagli della Prenotazione</h6>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Campo: </strong> {{ $reservation->field->nome_campo }}</p>
                    <p class="card-text"><strong>Data: </strong> {{ \Carbon\Carbon::parse($reservation->data_prenotazione)->format('d/m/Y') }}</p>
                    <p class="card-text"><strong>Ora: </strong> {{ \Carbon\Carbon::parse($reservation->ora_inizio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->ora_fine)->format('H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a class="btn btn-secondary custom-btn-icon text-center" href="{{ route('reservation.index') }}">
                <i class="bi bi-box-arrow-left"></i> Annulla
            </a>

            <form name="reservation_delete_form" method="post" action="{{ route('reservation.destroy', ['id' => $reservation->id]) }}" class="d-inline">
                @method('DELETE')
                @csrf
                <label for="submit_delete_button" class="btn btn-danger custom-btn-icon text-center mb-0">
                    <i class="bi bi-trash"></i> Elimina Prenotazione
                </label>
                <input id="submit_delete_button" class="d-none" type="submit" value="Delete">
            </form>
        </div>
    </div>
</div>
@endsection