@extends('layouts.master')

@section('title', 'MyFootball :: Prenota un Campo')

@section('page_title_h1', 'Prenota un campo')

@section('body')
<script>
    $(document).ready(function(){
        $("#reservationForm").submit(function(event) {
            event.preventDefault();

            var form = $(this);
            var error = false;
            var firstErrorField = null;

            var campoSelect = $("#id_campo");
            var dataSelect = $("#data_prenotazione");
            var inizioSelect = $("#ora_inizio");

            // Rimuovi precedenti messaggi di errore
            $(".invalid-feedback").text(""); // Utilizza la classe standard di Bootstrap
            $("select, input[type='date']").removeClass("is-invalid"); // Rimuovi la classe di errore visivo

            if (campoSelect.val() === "" || campoSelect.val() === null) {
                error = true;
                campoSelect.addClass("is-invalid"); // Aggiungi la classe di errore visivo
                campoSelect.next(".invalid-feedback").text("Devi selezionare un campo da prenotare.");
                if (firstErrorField === null) firstErrorField = campoSelect;
            } else {
                campoSelect.removeClass("is-invalid");
            }

            if (dataSelect.val() === "" || dataSelect.val() === null) {
                error = true;
                dataSelect.addClass("is-invalid");
                dataSelect.next(".invalid-feedback").text("Seleziona una data.");
                if (firstErrorField === null) firstErrorField = dataSelect;
            } else {
                dataSelect.removeClass("is-invalid");
            }

            if (inizioSelect.val() === "" || inizioSelect.val() === null) {
                error = true;
                inizioSelect.addClass("is-invalid");
                inizioSelect.next(".invalid-feedback").text("Seleziona un orario.");
                if (firstErrorField === null) firstErrorField = inizioSelect;
            } else {
                inizioSelect.removeClass("is-invalid");
            }

            if (error) {
                if (firstErrorField !== null) {
                    firstErrorField.focus();
                }
                return;
            }

            var idCampoValue = campoSelect.val();
            var dataValue = dataSelect.val();    
            var oraInizioValue = inizioSelect.val(); 

            var metodoHttp = $('input[name="_method"]').val();
            if (metodoHttp === undefined) { // POST
                $.ajax({
                    type: 'GET',
                    url: '/ajaxReservation', // Assicurati che questo percorso sia corretto e gestisca la richiesta AJAX
                    data: {
                        id_campo: idCampoValue,
                        data_prenotazione: dataValue,
                        ora_inizio: oraInizioValue
                    },
                    success: function (data) {
                        if (data.found) {
                            inizioSelect.addClass("is-invalid");
                            inizioSelect.next(".invalid-feedback").text("Esiste già una prenotazione per questo campo, data e ora.");
                            inizioSelect.focus();
                        } else {
                            form.off('submit').submit();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Errore AJAX nella verifica della prenotazione:", textStatus, errorThrown);
                        inizioSelect.addClass("is-invalid");
                        inizioSelect.next(".invalid-feedback").text("Errore di rete o server durante la verifica della prenotazione. Riprova più tardi.");
                    }
                });
            } else { // Invio form (ad es. per un'ipotetica modifica futura che non è in questo caso)
                form.off('submit').submit();
            }
        });
    });
</script>

<div class="container custom-container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Inserisci i dati della prenotazione</h5>
        </div>
        <div class="card-body">
            <form class="form-horizontal" id="reservationForm" name="reservation" method="post" action="{{ route('reservation.store') }}">
                @csrf
                
                <div class="mb-3 row">
                    @if(isset($field->id))
                        <label for="id_campo" class="col-md-2 col-form-label">Campo selezionato:</label>
                        <div class="col-md-10">
                            <p class="form-control-plaintext">{{ $field->nome_campo }}</p>
                            <input type="hidden" id="id_campo" name="id_campo" value="{{ $field->id }}">
                        </div>
                    @else
                        <label for="id_campo" class="col-md-2 col-form-label">Seleziona il campo:</label>
                        <div class="col-md-10">
                            <select class="form-select" id="id_campo" name="id_campo">
                                <option value="" disabled selected>Seleziona il campo</option>
                                @foreach($fields as $f)
                                    <option value="{{ $f->id }}">{{ $f->nome_campo}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="invalid-id-campo"></div>
                        </div>
                    @endif
                </div>

                <div class="mb-3 row">
                    <label for="data_prenotazione" class="col-md-2 col-form-label">Data:</label>
                    <div class="col-md-10">
                        <input type="date" class="form-control" id="data_prenotazione" name="data_prenotazione"/>
                        <div class="invalid-feedback" id="invalid-data-prenotazione"></div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="ora_inizio" class="col-md-2 col-form-label">Ora di Inizio:</label>
                    <div class="col-md-10">
                        <select class="form-select" id="ora_inizio" name="ora_inizio">
                            <option value="" disabled selected>Seleziona l'ora di inizio</option>
                            @for ($hour = 9; $hour <= 22; $hour++)
                                <option value="{{ sprintf('%02d:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                            @endfor
                        </select>
                        <div class="invalid-feedback" id="invalid-ora-inizio"></div>
                    </div>
                </div>

                <div class="mt-4 row">
                    <div class="col-md-10 offset-md-2 d-grid gap-2"> {{-- Usa d-grid e gap-2 per i bottoni --}}
                        <label for="mySubmit" class="btn btn-primary custom-btn-icon">
                            <i class="bi bi-book"></i> Prenota questo campo
                        </label>
                        <input id="mySubmit" class="d-none" type="submit" value="Save">
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('field.index') }}" class="btn btn-secondary custom-btn-icon">
                <i class="bi bi-box-arrow-left"></i> Torna alla lista dei campi
            </a>
        </div>
    </div>
</div>
@endsection