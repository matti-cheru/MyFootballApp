@extends('layouts.master')

@section('title', 'MyFootballManager :: Storico Prenotazioni Utente')

@section('page_title_h1')
    Storico Prenotazioni di {{ $userName }}
@endsection

@section('active_MyCentre','active')

@section('body')
<div class="container custom-container mt-4 mb-5"> {{-- Container per limitare la larghezza e centrare --}}

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
            <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Dettagli Storico Prenotazioni</h5>
        </div>
        <div class="card-body p-0">
            @if($reservations->isEmpty())
                <div class="alert alert-info m-3" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> Questo utente non ha ancora effettuato nessuna prenotazione.
                </div>
            @else
                <div class="table-responsive">
                    <table id="reservationsTable" class="table table-striped table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col">Campo</th>
                                <th scope="col">Data</th>
                                <th scope="col">Ora Inizio</th>
                                <th scope="col">Ora Fine</th>
                                <th scope="col">Stato</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->field->nome_campo ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->data_prenotazione)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->ora_inizio)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->ora_fine)->format('H:i') }}</td>
                                <td>
                                    @php
                                        $reservationDateTime = Carbon\Carbon::parse($reservation->data_prenotazione . ' ' . $reservation->ora_inizio);
                                    @endphp

                                    @if($reservationDateTime->isPast())
                                        <span class="badge bg-secondary"><i class="bi bi-clock-history"></i> Completata</span>
                                    @elseif($reservation->stato === 'attesa')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> In Attesa</span>
                                    @elseif($reservation->stato === 'accettata')
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Accettata</span>
                                    @else
                                        <span class="badge bg-info"><i class="bi bi-question-circle"></i> Sconosciuto</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="card-footer text-end">
            <a class="btn btn-secondary custom-btn-icon" href="{{ route('users.index') }}">
                <i class="bi bi-box-arrow-left me-2"></i> Torna a Lista Giocatori
            </a>
        </div>
    </div>
</div>
@endsection