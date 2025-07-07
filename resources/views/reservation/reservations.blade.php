@extends('layouts.master')

@section('title', 'MyFootballManager :: Storico Prenotazioni')

@section('page_title_h1', 'Storico Prenotazioni')

@section('active_MyCentre','active')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('reservation.index')}}">Prenotazioni</a></li>
<li class="breadcrumb-item active" aria-current="page">Storico Prenotazioni</li>
@endsection

@section('body')
<div class="container custom-container mt-4 mb-5"> 

<nav aria-label="Page navigation" id="paginationNav" class="mt-4 mb-4"> 
        <ul class="pagination justify-content-center custom-pagination">
            <li class="page-item" id="previousPage"><a class="page-link" href="#">Precedente</a></li>
            <li class="page-item" id="nextPage"><a class="page-link" href="#">Successivo</a></li>

            <li class="page-item ms-3">
                <select id="rowsPerPage" class="form-select custom-select-pagination">
                    <option value="5">5 prenotazioni per pagina</option>
                    <option value="10">10 prenotazioni per pagina</option>
                    <option value="15">15 prenotazioni per pagina</option>
                    <option value="20">20 prenotazioni per pagina</option>
                </select>
            </li>
        </ul>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Dettagli delle Tue Prenotazioni</h5>
        </div>
        <div class="card-body p-0">
            @if($reservations->isEmpty())
                <div class="alert alert-info m-3" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> Non hai prenotazioni nella tua cronologia.
                </div>
            @else
                <div class="table-responsive">
                    <table id="reservationsTable" class="table table-striped table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col">Campo</th>
                                <th scope="col">Data</th>
                                <th scope="col">Ora Inizio</th>
                                @if(isset($_SESSION["logged"]) && $_SESSION["logged"] && $_SESSION["role"] === "admin")
                                    <th scope="col">Utente</th>
                                    <th scope="col">Azioni</th>
                                @else
                                    <th scope="col">Stato</th>
                                    <th scope="col" class="text-center">Modifica</th>
                                    <th scope="col" class="text-center">Annulla</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->field->nome_campo }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->data_prenotazione)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->ora_inizio)->format('H:i') }}</td>

                                    @if(isset($_SESSION["logged"]) && $_SESSION["logged"] && $_SESSION["role"] === "admin")
                                        <td>{{ $reservation->user->name }}</td>
                                        <td>
                                            <a href="{{ route('reservation.show', ['reservation' => $reservation->id]) }}" class="btn btn-info btn-sm custom-btn-icon" title="Gestisci Prenotazione">
                                                <i class="bi bi-info-circle"></i> Gestisci
                                            </a>
                                        </td>
                                    @elseif(isset($_SESSION["logged"]) && $_SESSION["logged"] && $_SESSION["role"] === "registered_player")
                                        <td>
                                            @php
                                                $now = Carbon\Carbon::now();
                                                $reservationDateTime = Carbon\Carbon::parse($reservation->data_prenotazione . ' ' . $reservation->ora_inizio);
                                                $diffHours = $reservationDateTime->diffInHours($now);
                                            @endphp

                                            @if($reservation->stato === 'attesa')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> In Attesa</span>
                                            @elseif($reservation->stato === 'accettata')
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Accettata</span>
                                            @else
                                                <span class="badge bg-info"><i class="bi bi-question-circle"></i> Sconosciuto</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('reservation.edit', ['reservation' => $reservation->id]) }}" class="btn btn-primary btn-sm custom-btn-icon" title="Modifica Prenotazione">
                                                <i class="bi bi-pencil-square"></i> Modifica
                                            </a>
                                        </td>
                                        <td class="text-center">
                                                <a class="btn btn-danger btn-sm custom-btn-icon" href="{{ route('reservation.destroy.confirm', ['id' => $reservation->id]) }}" title="Annulla Prenotazione">
                                                    <i class="bi bi-x-octagon"></i> Annulla
                                                </a>
                                        </td>
                                    @else
                                        <td></td> 
                                        <td></td> 
                                        <td></td> 
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="card-footer text-end"> {{-- Footer della card per il bottone Indietro --}}
            <a class="btn btn-secondary custom-btn-icon" href="{{ route('home') }}">
                <i class="bi bi-box-arrow-left me-2"></i> Torna alla Home
            </a>
        </div>
    </div>
</div>
@endsection