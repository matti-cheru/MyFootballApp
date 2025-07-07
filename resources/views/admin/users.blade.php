@extends('layouts.master')

@section('title', 'MyFootballManager :: Giocatori Registrati')

@section('page_title_h1', 'Elenco dei Giocatori Registrati')

@section('active_MyCentre','active')

@section('body')
<div class="container custom-container mt-4 mb-5">

    <nav aria-label="Page navigation" id="paginationNav" class="mt-4 mb-4">
        <ul class="pagination justify-content-center custom-pagination">
            <li class="page-item" id="previousPage"><a class="page-link" href="#">Precedente</a></li>
            <li class="page-item" id="nextPage"><a class="page-link" href="#">Successivo</a></li>

            <li class="page-item ms-3">
                <select id="rowsPerPage" class="form-select custom-select-pagination">
                    <option value="5">5 giocatori per pagina</option>
                    <option value="10">10 giocatori per pagina</option>
                    <option value="15">15 giocatori per pagina</option>
                    <option value="20">20 giocatori per pagina</option>
                </select>
            </li>
        </ul>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Dettagli dei Giocatori Registrati</h5>
        </div>
        <div class="card-body p-0"> 
            @if($players->isEmpty())
                <div class="alert alert-info m-3" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> Non ci sono giocatori registrati.
                </div>
            @else
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col">Giocatore</th>
                                <th scope="col">Email</th>
                                <th scope="col" class="text-center">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $player)
                                <tr>
                                    <td>{{ $player->name }}</td>
                                    <td>{{ $player->email }}</td>
                                    <td class="d-flex justify-content-center flex-wrap gap-2">
                                        <a class="btn custom-action-btn btn-secondary" href="{{ route('users.reservations.history', ['id' => $player->id]) }}" title="Visualizza storico prenotazioni">
                                            <i class="bi bi-clock-history"></i> Storico Prenotazioni
                                        </a>
                                        <a class="btn custom-action-btn btn-danger" href="{{ route('users.destroy.confirm', ['id' => $player->id]) }}" title="Elimina questo giocatore">
                                            <i class="bi bi-trash"></i> Elimina Giocatore
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="card-footer text-end">
            <a class="btn btn-secondary custom-btn-icon" href="{{ route('home') }}">
                <i class="bi bi-box-arrow-left me-2"></i> Torna alla Home
            </a>
        </div>
    </div>
</div>
@endsection