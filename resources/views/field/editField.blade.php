@extends('layouts.master')

@section('title')
    @if(isset($field->id))
        MyFootball :: Modifica "{{ $field->nome_campo }}"
    @else
        MyFootball :: Crea un nuovo Campo
    @endif
@endsection

@section('page_title_h1')
    @if(isset($field->id))
        Modifica "<b>{{ $field->nome_campo }}</b>"
    @else
        Crea un nuovo Campo
    @endif
@endsection

@section('body')
<script>
    $(document).ready(function(){
        $("form").submit(function(event) {
            event.preventDefault();

            var form = $(this);
            var error = false;
            var firstErrorField = null; 

            var nomeCampoInput = $("input[name='nome_campo']");
            var superficieSelect = $("#id_superficie");
            var aperturaSelect = $("#orario_apertura");
            var chiusuraSelect = $("#orario_chiusura");

            // Rimuovi precedenti stati di validazione e messaggi di errore
            nomeCampoInput.removeClass('is-invalid').next('.invalid-feedback').text("");
            superficieSelect.removeClass('is-invalid').next('.invalid-feedback').text("");
            aperturaSelect.removeClass('is-invalid').next('.invalid-feedback').text("");
            chiusuraSelect.removeClass('is-invalid').next('.invalid-feedback').text("");

            if (nomeCampoInput.val().trim() === "") {
                error = true;
                nomeCampoInput.addClass('is-invalid');
                nomeCampoInput.next('.invalid-feedback').text("Il nome del campo è obbligatorio.");
                if (firstErrorField === null) firstErrorField = nomeCampoInput;
            }

            if (superficieSelect.val() === "" || superficieSelect.val() === null) {
                error = true;
                superficieSelect.addClass('is-invalid');
                superficieSelect.next('.invalid-feedback').text("La superficie è obbligatoria.");
                if (firstErrorField === null) firstErrorField = superficieSelect;
            }

            if (aperturaSelect.val() === "" || aperturaSelect.val() === null) {
                error = true;
                aperturaSelect.addClass('is-invalid');
                aperturaSelect.next('.invalid-feedback').text("Seleziona un orario di apertura del campo.");
                if (firstErrorField === null) firstErrorField = aperturaSelect;
            }

            if (chiusuraSelect.val() === "" || chiusuraSelect.val() === null) {
                error = true;
                chiusuraSelect.addClass('is-invalid');
                chiusuraSelect.next('.invalid-feedback').text("Seleziona un orario di chiusura del campo.");
                if (firstErrorField === null) firstErrorField = chiusuraSelect;
            }

            if (error) {
                if (firstErrorField !== null) {
                    firstErrorField.focus();
                }
                return;
            }

            var nomeCampoValue = nomeCampoInput.val().trim();
            var metodoHttp = $('input[name="_method"]').val();

            if (metodoHttp === undefined) { // POST (creazione)
                $.ajax({
                    type: 'GET',
                    url: '/ajaxField',
                    data: {nome_campo: nomeCampoValue},
                    success: function (data) {
                        if (data.found) {
                            nomeCampoInput.addClass('is-invalid');
                            nomeCampoInput.next('.invalid-feedback').text("Un campo con questo nome esiste già nel database.");
                            nomeCampoInput.focus();
                        } else {
                            // Se non ci sono errori o duplicati, procede con il submit del form
                            form.off('submit').submit();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Errore AJAX nella verifica del nome (creazione):", textStatus, errorThrown);
                        nomeCampoInput.addClass('is-invalid');
                        nomeCampoInput.next('.invalid-feedback').text("Errore di rete o server durante la verifica. Riprova.");
                    }
                });
            } else { // PUT (modifica)
                var currentFieldId = "{{ $field->id ?? '' }}";
                var originalNomeCampo = nomeCampoInput.data('original-value');

                if (nomeCampoValue !== originalNomeCampo) {
                    $.ajax({
                        type: 'GET', 
                        url: '/ajaxField',
                        data: {
                            nome_campo: nomeCampoValue,
                            current_field_id: currentFieldId
                        },
                        success: function (data) {
                            if (data.found) {
                                nomeCampoInput.addClass('is-invalid');
                                nomeCampoInput.next('.invalid-feedback').text("Un altro campo con questo nome esiste già nel database.");
                                nomeCampoInput.focus();
                            } else {
                                // Se non ci sono errori o duplicati, procede con il submit del form
                                form.off('submit').submit();
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Errore AJAX nella verifica del nome (modifica):", textStatus, errorThrown);
                            nomeCampoInput.addClass('is-invalid');
                            nomeCampoInput.next('.invalid-feedback').text("Errore di rete o server durante la verifica. Riprova.");
                        }
                    });
                } else {
                    form.off('submit').submit();
                }
            }
        });
    });
</script>

<div class="container custom-container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                @if(isset($field->id))
                    Modifica Campo
                @else
                    Aggiungi Nuovo Campo
                @endif
            </h5>
        </div>
        <div class="card-body">
            @if(isset($field->id))
                <form class="form-horizontal" name="field" method="post" action="{{ route('field.update', ['field' => $field->id]) }}">
                @method('PUT')
            @else
                <form class="form-horizontal" name="field" method="post" action="{{ route('field.store') }}">
            @endif
            @csrf

                <div class="mb-3 row">
                    <label for="nome_campo" class="col-md-2 col-form-label">Nome del Campo</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="nome_campo" name="nome_campo" 
                               value="{{ $field->nome_campo ?? '' }}" 
                               placeholder="Nome del campo"
                               @if(isset($field->nome_campo)) data-original-value="{{ $field->nome_campo }}" @endif 
                               />
                        <div class="invalid-feedback" id="invalid-nome-campo"></div> 
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="descrizione" class="col-md-2 col-form-label">Descrizione</label>
                    <div class="col-md-10">
                        <textarea class="form-control" id="descrizione" name="descrizione" rows="3" placeholder="Inserisci una descrizione">{{ $field->descrizione ?? '' }}</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="id_superficie" class="col-md-2 col-form-label">Tipo di Superficie</label>
                    <div class="col-md-10">
                        <select class="form-select" id="id_superficie" name="id_superficie">
                            <option value="" disabled {{ (!isset($field) || !$field->id_superficie) ? 'selected' : ''}}>
                                Seleziona la superficie
                            </option>
                            @foreach($surfaces as $surf)
                                <option value="{{ $surf->id }}"
                                    {{ (isset($field) && $field->id_superficie == $surf->id) ? 'selected' : '' }}>
                                    {{ $surf->nome }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="invalid-superficie"></div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="orario_apertura" class="col-md-2 col-form-label">Orario di Apertura</label>
                    <div class="col-md-10">
                        <select class="form-select" id="orario_apertura" name="orario_apertura">
                            <option value="" disabled {{ (!isset($field) || !$field->orario_apertura) ? 'selected' : '' }}>
                                Seleziona orario di apertura
                            </option>
                            @for ($hour = 9; $hour <= 10; $hour++)
                                @php $formattedHour = sprintf('%02d:00', $hour); @endphp
                                <option value="{{ $formattedHour }}"
                                    {{ (isset($field) && $field->orario_apertura == $formattedHour) ? 'selected' : '' }}>
                                    {{ $formattedHour }}
                                </option>
                            @endfor
                        </select>
                        <div class="invalid-feedback" id="invalid-apertura"></div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="orario_chiusura" class="col-md-2 col-form-label">Orario di Chiusura</label>
                    <div class="col-md-10">
                        <select class="form-select" id="orario_chiusura" name="orario_chiusura">
                            <option value="" disabled {{ (!isset($field) || !$field->orario_chiusura) ? 'selected' : '' }}>
                                Seleziona orario di chiusura
                            </option>
                            @for ($hour = 21; $hour <= 22; $hour++)
                                @php $formattedHour = sprintf('%02d:00', $hour); @endphp
                                <option value="{{ $formattedHour }}"
                                    {{ (isset($field) && $field->orario_chiusura == $formattedHour) ? 'selected' : '' }}>
                                    {{ $formattedHour }}
                                </option>
                            @endfor
                        </select>
                        <div class="invalid-feedback" id="invalid-chiusura"></div>
                    </div>
                </div>
            
                <div class="mt-4 row">
                    <div class="col-md-10 offset-md-2 d-grid gap-2">
                        <label for="mySubmit" class="btn btn-primary custom-btn-icon">
                            <i class="bi bi-floppy2-fill"></i>
                            @if(isset($field->id))
                                Salva Modifiche
                            @else
                                Crea Campo
                            @endif
                        </label>
                        <input id="mySubmit" class="d-none" type="submit" value="Save">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-10 offset-md-2 d-grid">
                        <a class="btn btn-secondary custom-btn-icon" href="{{ route('field.index') }}">
                            <i class="bi bi-box-arrow-left"></i> Annulla
                        </a>
                    </div>
                </div>
            </form>       
        </div>
    </div>
</div>
@endsection