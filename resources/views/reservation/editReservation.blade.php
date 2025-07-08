@extends('layouts.master')

@section('title','MyFootballManager :: Edit a Reservation')

@section('header','Modifica la tua prenotazione') {{-- Ho lasciato l'header come hai specificato tu inizialmente --}}

@section('body')
<script>
    $(document).ready(function(){
        // Assicurati che il form abbia id="reservationForm"
        $("#reservationForm").submit(function(event) {
            // Impedisce subito il submit nativo del browser.
            // Il submit avverrà solo programaticamente dopo la validazione e la verifica AJAX.
            event.preventDefault();

            var form = $(this);
            var error = false;
            var firstErrorField = null; // Per il focus sul primo campo con errore

            // Seleziona i campi del form
            var campoSelect = $("#id_campo");
            var dataSelect = $("#data_prenotazione");
            var inizioSelect = $("#ora_inizio");

            // Rimuovi precedenti messaggi di errore e classi 'is-invalid'
            // Questo ciclo è più robusto per pulire tutti i campi rilevanti
            $("select, input[type='date'], input[type='time']").each(function() {
                $(this).removeClass("is-invalid");
                $(this).next(".invalid-feedback").text("");
            });

            // --- Validazione Campo selezionato (id_campo) ---
            if (campoSelect.val() === "" || campoSelect.val() === null) {
                error = true;
                campoSelect.addClass('is-invalid'); // Aggiunge la classe di errore
                campoSelect.next('.invalid-feedback').text("Devi selezionare un campo da prenotare."); // Aggiorna il testo del feedback
                if (firstErrorField === null) firstErrorField = campoSelect;
            } else {
                campoSelect.removeClass('is-invalid'); // Rimuove la classe se valido
                campoSelect.next('.invalid-feedback').text(""); // Pulisce il testo del feedback
            }

            // --- Validazione Data prenotazione ---
            if (dataSelect.val() === "" || dataSelect.val() === null) {
                error = true;
                dataSelect.addClass('is-invalid');
                dataSelect.next('.invalid-feedback').text("Seleziona una data."); // Aggiorna il testo del feedback
                if (firstErrorField === null) firstErrorField = dataSelect;
            } else {
                dataSelect.removeClass('is-invalid');
                dataSelect.next('.invalid-feedback').text(""); // Pulisce il testo del feedback
            }

            // --- Validazione Orario Inizio ---
            if (inizioSelect.val() === "" || inizioSelect.val() === null) {
                error = true;
                inizioSelect.addClass('is-invalid');
                inizioSelect.next('.invalid-feedback').text("Seleziona un orario."); // Aggiorna il testo del feedback
                if (firstErrorField === null) firstErrorField = inizioSelect;
            } else {
                inizioSelect.removeClass('is-invalid');
                inizioSelect.next('.invalid-feedback').text(""); // Pulisce il testo del feedback
            }

            // Se ci sono errori di validazione frontend, blocca il submit e metti il focus.
            if (error) {
                if (firstErrorField !== null) {
                    firstErrorField.focus();
                }
                return; // Ferma l'esecuzione dello script qui
            }

            // Se arriviamo qui, i campi sono tutti compilati correttamente (a livello frontend).
            // Ora gestiamo la logica di submit, inclusa la verifica AJAX.

            // Rileggi i valori dai campi DOPO la validazione, assicurandoti che siano corretti e non null.
            var idCampoValue = campoSelect.val();
            var dataValue = dataSelect.val();
            var oraInizioValue = inizioSelect.val();

            // Verifica se il form è in modalità 'creazione' (POST)
            var metodoHttp = $('input[name="_method"]').val(); // Sarà 'PUT' o undefined

            // --- LOGICA AGGIUNTA PER GESTIRE LA VERIFICA DUPLICATI ANCHE IN MODALITÀ PUT ---
            if (metodoHttp === undefined) { // Siamo in modalità 'creazione' (POST)
                $.ajax({
                    type: 'GET',
                    url: '/ajaxReservation',
                    data: {
                        id_campo: idCampoValue,
                        data_prenotazione: dataValue,
                        ora_inizio: oraInizioValue
                    },
                    success: function (data) {
                        if (data.found) {
                            inizioSelect.addClass('is-invalid');
                            inizioSelect.next('.invalid-feedback').text("Esiste già una prenotazione per questo campo, data e ora.");
                            inizioSelect.focus();
                        } else {
                            inizioSelect.removeClass('is-invalid');
                            inizioSelect.next('.invalid-feedback').text("");
                            form.off('submit').submit();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Errore AJAX nella verifica della prenotazione:", textStatus, errorThrown);
                        inizioSelect.addClass('is-invalid');
                        inizioSelect.next('.invalid-feedback').text("Errore di rete o server durante la verifica della prenotazione. Riprova più tardi.");
                    }
                });
            } else { // Siamo in modalità 'modifica' (PUT/PATCH)
                // Recupera l'ID della prenotazione corrente e i valori originali per il confronto
                // Assicurati che $reservation->id sia disponibile in Blade per questo script
                var currentReservationId = "{{ $reservation->id ?? '' }}";
                var originalIdCampo = campoSelect.data('original-value-id-campo');
                var originalDataPrenotazione = dataSelect.data('original-value-data');
                var originalOraInizio = inizioSelect.data('original-value-ora');

                // Esegui la verifica AJAX SOLO se i valori chiave (campo, data, ora) sono cambiati
                if (idCampoValue != originalIdCampo ||
                    dataValue != originalDataPrenotazione ||
                    oraInizioValue != originalOraInizio) {

                    $.ajax({
                        type: 'GET',
                        url: '/ajaxReservation', // Lo stesso URL per la verifica
                        data: {
                            id_campo: idCampoValue,
                            data_prenotazione: dataValue,
                            ora_inizio: oraInizioValue,
                            // Passa l'ID della prenotazione corrente, così il backend può escluderla dal controllo
                            current_reservation_id: currentReservationId
                        },
                        success: function (data) {
                            if (data.found) {
                                inizioSelect.addClass('is-invalid');
                                inizioSelect.next('.invalid-feedback').text("Esiste già una prenotazione per questo campo, data e ora.");
                                inizioSelect.focus();
                            } else {
                                inizioSelect.removeClass('is-invalid');
                                inizioSelect.next('.invalid-feedback').text("");
                                form.off('submit').submit(); // Nessun duplicato, procedi con il submit
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Errore AJAX nella verifica della prenotazione:", textStatus, errorThrown);
                            inizioSelect.addClass('is-invalid');
                            inizioSelect.next('.invalid-feedback').text("Errore di rete o server durante la verifica della prenotazione. Riprova più tardi.");
                        }
                    });
                } else {
                    // Se siamo in modalità modifica e i campi chiave non sono stati modificati,
                    // non è necessaria una verifica AJAX. Sottometti direttamente il form.
                    form.off('submit').submit();
                }
            }
            // --- FINE LOGICA AGGIUNTA ---
        });
    });
</script>

<div class="container custom-container mt-4 mb-5">
    <div class="card shadow-sm border-0 rounded-lg"> {{-- Rimosso border-0 se vuoi il bordo sottile, altrimenti tienilo --}}
        <div class="card-header bg-primary text-white py-3"> {{-- Aggiunto py-3 per padding verticale --}}
            <h5 class="mb-0 fw-normal"><i class="bi bi-calendar-check-fill me-2"></i>Dettagli Prenotazione</h5> {{-- Icona e testo più adatto --}}
        </div>
        <div class="card-body p-4"> {{-- Aumentato il padding interno --}}
            <form class="form-horizontal" method="post" action="{{ route('reservation.update', ['id' => $reservation->id]) }}" id="reservationForm">
                @method('PUT')
                @csrf

                <div class="mb-3 row"> {{-- Utilizziamo il layout a riga per coerenza con l'esempio --}}
                    <label for="id_campo" class="col-md-3 col-form-label">Campo:</label> {{-- Colonna per la label --}}
                    <div class="col-md-9"> {{-- Colonna per il campo input/select --}}
                        <select class="form-select" id="id_campo" name="id_campo"
                            data-original-value-id-campo="{{ $reservation->id_campo }}">
                            <option value="" disabled {{ !isset($reservation->id_campo) ? 'selected' : '' }}>
                                Seleziona il campo
                            </option>
                            @foreach($fields as $fieldOption)
                                <option value="{{ $fieldOption->id }}"
                                    {{ (isset($reservation) && $reservation->id_campo == $fieldOption->id) ? 'selected' : '' }}>
                                    {{ $fieldOption->nome_campo }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="invalid-campo"></div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="data_prenotazione" class="col-md-3 col-form-label">Data:</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" id="data_prenotazione" name="data_prenotazione"
                            value="{{ old('data_prenotazione', isset($reservation) ? $reservation->data_prenotazione : '') }}"
                            data-original-value-data="{{ isset($reservation) ? $reservation->data_prenotazione : '' }}">
                        <div class="invalid-feedback" id="invalid-data-prenotazione"></div>
                    </div>
                </div>

                <div class="mb-4 row"> {{-- Margine maggiore prima dei pulsanti --}}
                    <label for="ora_inizio" class="col-md-3 col-form-label">Ora Inizio:</label>
                    <div class="col-md-9">
                        <input type="time" class="form-control" id="ora_inizio" name="ora_inizio"
                            value="{{ old('ora_inizio', isset($reservation) ? \Carbon\Carbon::parse($reservation->ora_inizio)->format('H:i') : '') }}"
                            data-original-value-ora="{{ isset($reservation) ? \Carbon\Carbon::parse($reservation->ora_inizio)->format('H:i') : '' }}">
                        <div class="invalid-feedback" id="invalid-ora-inizio"></div>
                    </div>
                </div>

                <div class="d-grid gap-2"> {{-- Utilizza d-grid e gap-2 per i bottoni a tutta larghezza e con spazio tra loro --}}
                    <label for="mySubmit" class="btn btn-primary btn-lg custom-btn-icon">
                        <i class="bi bi-floppy2-fill me-2"></i> Salva Modifiche
                    </label>
                    <input id="mySubmit" class="d-none" type="submit" value="Save">

                    <a class="btn btn-secondary btn-lg custom-btn-icon" href="{{ route('reservation.index') }}">
                        <i class="bi bi-x-circle-fill me-2"></i> Annulla
                    </a>
                </div>
            </form>
        </div>
        <div class="card-footer text-center py-3 bg-light"> {{-- Footer centrato --}}
            <small class="text-muted">MyFootballManager &copy; 2025</small>
        </div>
    </div>
</div>
@endsection