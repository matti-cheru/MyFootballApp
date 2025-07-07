@extends('layouts.delete')

@section('title', 'MyFootballManager :: Elimina un Giocatore')

@section('page_title_h1', 'Elimina un Giocatore')

@section('body')
    <script>
        $(document).ready(function() {
            $('#deletePlayerForm').submit(function(event) {
                var motivationInput = $('#motivation');
                var motivationValue = motivationInput.val().trim();
                var error = false;

                motivationInput.removeClass('is-invalid');
                $('#invalid-motivation').text('');

                if (motivationValue === '') {
                    error = true;
                    motivationInput.addClass('is-invalid');
                    $('#invalid-motivation').text("La motivazione è obbligatoria per l'eliminazione.");
                    motivationInput.focus();
                }

                if (error) {
                    event.preventDefault();
                }
            });
        });
    </script>

    <div class="container custom-container mt-4 mb-5">

        <div class="alert alert-warning shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Sei sicuro di voler eliminare questo giocatore? Questa azione **non può essere annullata**.
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-person-x me-2"></i>Dettagli Giocatore da Eliminare</h5>
            </div>
            <div class="card-body">
                <p class="card-text"><strong>Nome:</strong> {{ $player->name }}</p>
                <p class="card-text"><strong>Email:</strong> {{ $player->email }}</p>
            </div>
        </div>

        <form name="deletePlayer" method="post" action="{{ route('users.destroy', ['user' => $player->id]) }}" id="deletePlayerForm" class="mt-4">
            @method('DELETE')
            @csrf

            <div class="mb-3">
                <label for="motivation" class="form-label">Motivazione dell'eliminazione (obbligatoria):</label>
                <textarea class="form-control" id="motivation" name="motivation" rows="4" placeholder="Spiega dettagliatamente il motivo per cui stai eliminando questo giocatore."></textarea>
                <div class="invalid-feedback" id="invalid-motivation"></div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4"> {{-- Allineamento a destra dei bottoni --}}
                <a class="btn btn-secondary custom-btn-icon" href="{{ route('users.index') }}">
                    <i class="bi bi-x-circle me-2"></i> Annulla
                </a>

                <button type="submit" class="btn btn-danger custom-btn-icon">
                    <i class="bi bi-trash-fill me-2"></i> Conferma Eliminazione
                </button>
            </div>
        </form>
    </div>
@endsection