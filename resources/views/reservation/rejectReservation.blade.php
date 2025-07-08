@extends('layouts.delete') {{-- Assumendo che 'layouts.master' sia un layout più completo rispetto a 'layouts.delete' per uniformità --}}

@section('title','MyFootballManager :: Rifiuta una prenotazione')

@section('header','Gestisci la prenotazione')

@section('body')
<div class="container custom-container mt-4 mb-5"> {{-- Contenitore principale con margini --}}
    <div class="row justify-content-center"> {{-- Centra il contenuto orizzontalmente --}}
        <div class="col-md-8 col-lg-6"> {{-- Larghezza del card per vari schermi --}}
            <div class="card shadow-lg border-0 rounded-lg"> {{-- Card con ombra e bordi arrotondati --}}
                <div class="card-header bg-danger text-white text-center py-3"> {{-- Header rosso, testo bianco, centrato --}}
                    <h4 class="fw-light my-0"><i class="bi bi-x-circle-fill me-2"></i>Rifiuta Prenotazione</h4> {{-- Titolo con icona --}}
                </div>
                <div class="card-body p-4"> {{-- Padding all'interno del corpo della card --}}

                    <div class="alert alert-danger d-flex align-items-center" role="alert"> {{-- Alert più moderno con icona --}}
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" style="font-size: 1.5rem;"></i>
                        <div>
                            Sei sicuro di voler **rifiutare** questa prenotazione? Questa azione non può essere annullata.
                        </div>
                    </div>

                    <h5 class="mb-3 text-primary">Dettagli della Prenotazione:</h5> {{-- Titolo per i dettagli --}}
                    <p class="card-text mb-2"><strong>Campo:</strong> {{ $reservation->field->nome_campo }}</p>
                    <p class="card-text mb-2"><strong>Data:</strong> {{ \Carbon\Carbon::parse($reservation->data_prenotazione)->format('d/m/Y') }}</p> {{-- Formattazione data italiana --}}
                    <p class="card-text mb-4"><strong>Ora:</strong> {{ \Carbon\Carbon::parse($reservation->ora_inizio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->ora_fine)->format('H:i') }}</p> {{-- Formattazione ora --}}

                    <div class="d-grid gap-3"> {{-- Utilizza d-grid per pulsanti a tutta larghezza e con spazio --}}
                        <form name="reservation" method="post" action="{{ route('reservation.reject', ['id' => $reservation->id]) }}">
                            @method('DELETE')
                            @csrf
                            <label for="mySubmit" class="btn btn-danger btn-lg w-100 custom-btn-icon"> {{-- Pulsante a tutta larghezza --}}
                                <i class="bi bi-trash-fill me-2"></i> Rifiuta Prenotazione
                            </label>
                            <input id="mySubmit" class="d-none" type="submit" value="Delete">
                        </form>

                        <a class="btn btn-secondary btn-lg w-100 custom-btn-icon" href="{{ route('reservation.index') }}"> {{-- Pulsante a tutta larghezza --}}
                            <i class="bi bi-box-arrow-left me-2"></i> Annulla
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center py-3 bg-light"> {{-- Footer della card con sfondo chiaro --}}
                    <small class="text-muted">MyFootballManager &copy; 2025</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection