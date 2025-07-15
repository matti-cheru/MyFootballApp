@extends('layouts.master')

@section('active_MyCentre','active')

@section('title', 'MyFootball :: Fields List')

@section('page_title_h1', 'Lista dei Campi')

@section('body')
<script>
    $(document).ready(function(){
        // Searching feature
        $(".searchOptions").on("click", function(e) {
            e.preventDefault();
            var column = $(this).attr("data-column");
            $("#searchInput").attr("data-column", column);
            $("#searchInput").attr("placeholder", "Cerca " + $(this).text().toLowerCase() + "...");
            $("#searchInput").trigger("keyup"); // Riesegui la ricerca quando viene selezionata una colonna
        });

        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();

            // Reimposta completamente la paginazione se il campo di ricerca viene svuotato
            if (value !== "") {
                $("#paginationNav").hide();
            } else {
                $("#paginationNav").show();
                return;
            }

            var column = $("#searchInput").attr("data-column");

            $("#fieldTable tbody tr").each(function() {
                var found = false;
                if ((column == -1)||(column === undefined)) { // Selezionato "Nome o Superficie" o nessuna opzione
                    // Cerca in tutte le colonne visibili escluse le ultime tre colonne (azioni)
                    $(this).find("td").slice(0, -3).each(function() {
                        var text = $(this).text().toLowerCase();
                        if (text.indexOf(value) > -1) {
                            found = true;
                        }
                    });
                } else {
                    var $td = $(this).find("td:eq(" + column + ")");
                    if ($td.length > 0) {
                        var text = $td.text().toLowerCase();
                        if (text.indexOf(value) > -1) {
                            found = true;
                        }
                    }
                }
                $(this).toggle(found);
            });
        });
    });
</script>

<div class="container custom-container mt-4 mb-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            {{-- Barra di ricerca --}}
            <div class="input-group search-input-group">
                <div class="input-group-prepend">
                    <button class="btn search-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Cerca per</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item searchOptions" href="#" data-column="0">Nome</a></li>
                        <li><a class="dropdown-item searchOptions" href="#" data-column="1">Superficie</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item searchOptions" href="#" data-column="-1">Nome o Superficie</a></li>
                    </ul>
                </div>
                <input type="text" id="searchInput" class="form-control custom-search-input" aria-label="Text input with dropdown button" placeholder="Cerca...">
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            @if((isset($_SESSION["logged"]))&&($_SESSION["logged"]))
                @if($_SESSION["role"]==="admin")
                    <a class="btn custom-btn-icon btn-success" href="{{ route('field.create') }}">
                        <i class="bi bi-plus-circle"></i> Aggiungi Campo
                    </a>
                @elseif($_SESSION["role"]==="registered_player")
                    <a class="btn custom-btn-icon btn-primary" href="{{ route('reservation.create') }}">
                        <i class="bi bi-calendar-plus"></i> Prenota un Campo
                    </a>
                @endif
            @endif
        </div>
    </div>

    <div class="custom-table-container">
        @if($fields->isEmpty())
            <div class="alert alert-info text-center no-fields-message" role="alert">
                Non ci sono campi disponibili in questo momento.
            </div>
        @else
            <table id="fieldTable" class="table custom-table table-responsive">
                <col width='35%'>
                <col width='35%'>
                <col width='30%'>
                <thead>
                    <tr>
                        <th>Nome Campo</th>
                        <th>Superficie</th>
                        <th class="text-center">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fields as $field)
                        <tr>
                            <td>{{ $field->nome_campo }}</td>
                            <td>{{ $field->surface->nome }}</td>
                            <td class="d-flex justify-content-center flex-wrap gap-2">
                                <a class="btn custom-action-btn btn-info" href="{{ route('field.show', ['id' => $field->id]) }}">
                                    <i class="bi bi-info-circle"></i> Dettagli
                                </a>
                                @if((isset($_SESSION["logged"]))&&($_SESSION["logged"]))
                                    @if($_SESSION["role"] === "admin")
                                        <a class="btn custom-action-btn btn-warning" href="{{ route('field.edit', ['id' => $field->id]) }}">
                                            <i class="bi bi-pencil-square"></i> Modifica
                                        </a>
                                        <a class="btn custom-action-btn btn-danger" href="{{ route('field.destroy.confirm', ['id' => $field->id]) }}">
                                            <i class="bi bi-trash"></i> Elimina
                                        </a>
                                    @elseif($_SESSION["role"] === "registered_player")
                                        <a class="btn custom-action-btn btn-primary" href="{{ route('field.book', ['id' => $field->id]) }}">
                                            <i class="bi bi-book"></i> Prenota
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <nav aria-label="Page navigation" id="paginationNav" class="mt-4">
        <ul class="pagination justify-content-center custom-pagination">
            <li class="page-item" id="previousPage"><a class="page-link" href="#">Precedente</a></li>
            <li class="page-item" id="nextPage"><a class="page-link" href="#">Successivo</a></li>
            <li class="page-item ms-3">
                <select id="rowsPerPage" class="form-select custom-select-pagination">
                    <option value="5">5 campi per pagina</option>
                    <option value="10">10 campi per pagina</option>
                    <option value="15">15 campi per pagina</option>
                    <option value="20">20 campi per pagina</option>
                </select>
            </li>
        </ul>
    </nav>
</div>
@endsection