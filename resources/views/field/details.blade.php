@extends('layouts.master')

@section('title')
    MyFootball :: Dettagli "{{ $field->nome_campo }}"
@endsection

@section('page_title_h1')
    @if(isset($field->id))
        Dettagli "{{ $field->nome_campo }}"
    @endif
@endsection

@section('body')
<div class="container custom-container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Dettagli Campo</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <dl class="row">
                        <dt class="col-sm-4">Nome:</dt>
                        <dd class="col-sm-8">{{ $field->nome_campo }}</dd>

                        <dt class="col-sm-4">Descrizione:</dt>
                        <dd class="col-sm-8">{{ $field->descrizione }}</dd>

                        <dt class="col-sm-4">Superficie:</dt>
                        <dd class="col-sm-8">{{ $field->surface->nome }}</dd>

                        <dt class="col-sm-4">Orario Apertura:</dt>
                        <dd class="col-sm-8">{{ $field->orario_apertura }}</dd>

                        <dt class="col-sm-4">Orario Chiusura:</dt>
                        <dd class="col-sm-8">{{ $field->orario_chiusura }}</dd>
                    </dl>
                </div>
                <div class="col-md-4 d-flex flex-column justify-content-start align-items-stretch gap-2">
                    @if((isset($_SESSION["logged"])) && ($_SESSION["logged"]) && ($_SESSION["role"] === "admin"))
                        <a class="btn custom-btn-icon btn-warning" href="{{ route('field.edit', ['field' => $field->id]) }}">
                            <i class="bi bi-pencil-square"></i> Modifica Campo
                        </a>
                        <a class="btn custom-btn-icon btn-danger" href="{{ route('field.destroy.confirm', ['id' => $field->id]) }}">
                            <i class="bi bi-trash"></i> Elimina Campo
                        </a>
                    @endif

                    @if((isset($_SESSION["logged"])) && ($_SESSION["logged"]) && ($_SESSION["role"] === "registered_player"))
                        <a class="btn custom-btn-icon btn-primary" href="{{ route('field.book', ['id' => $field->id]) }}">
                            <i class="bi bi-book"></i> Prenota questo campo
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a class="btn btn-secondary custom-btn-icon" href="{{ route('field.index') }}">
                <i class="bi bi-box-arrow-left"></i> Torna alla lista
            </a>
        </div>
    </div>
</div>
@endsection