@extends('layouts.delete')

@section('title', 'MyFootball :: Elimina Campo')

@section('page_title_h1', 'Elimina Campo')

@section('body')
<div class="container custom-container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white text-center">
            <h5 class="mb-0">Conferma Eliminazione Campo</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Sei sicuro di voler rimuovere "<strong>{{ $field->nome_campo }}</strong>"? Questa operazione non può essere annullata.
            </div>

            <div class="card mb-3 border-secondary">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Dettagli Campo</h6>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Nome: </strong> {{ $field->nome_campo }}</p>
                    <p class="card-text"><strong>Descrizione: </strong> {{ $field->descrizione ?? 'N/D' }}</p>
                    <p class="card-text"><strong>Superficie: </strong> {{ $field->surface->nome ?? 'N/D' }}</p>
                    <p class="card-text"><strong>Orario Apertura: </strong> {{ \Carbon\Carbon::parse($field->orario_apertura)->format('H:i') ?? 'N/D' }}</p>
                    <p class="card-text"><strong>Orario Chiusura: </strong> {{ \Carbon\Carbon::parse($field->orario_chiusura)->format('H:i') ?? 'N/D' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a class="btn btn-secondary custom-btn-icon text-center" href="{{ route('field.index') }}">
                <i class="bi bi-box-arrow-left"></i> Annulla
            </a>

            <form name="field_delete_form" method="post" action="{{ route('field.destroy', ['id' => $field->id]) }}" class="d-inline">
                @method('DELETE')
                @csrf
                <label for="submit_delete_button" class="btn btn-danger custom-btn-icon text-center mb-0">
                    <i class="bi bi-trash"></i> Elimina Campo
                </label>
                <input id="submit_delete_button" class="d-none" type="submit" value="Delete">
            </form>
        </div>
    </div>
</div>
@endsection