@extends('layouts.master')

@section('title', 'MyFootballManager :: Autenticazione Utente')

@section('page_title_h1', 'Accedi o Registrati')

@section('body')
<script>
    $(document).ready(function(){
        $("#login-form").submit(function(event) {
            // Ottenere i valori dei campi email e password
            var email = $("input[name='email']").val();
            var password = $("input[name='password']").val();
            var error = false;

            // Pulisci i messaggi di errore precedenti
            $("#invalid-email").text("");
            $("#invalid-password").text("");
            $("input[name='email']").removeClass('is-invalid');
            $("input[name='password']").removeClass('is-invalid');

            // Verifica se il campo "password" è vuoto
            if (password.trim() === "") {
                error = true;
                $("#invalid-password").text("La password è obbligatoria.");
                $("input[name='password']").addClass('is-invalid');
            }

            // Verifica se il campo "email" è vuoto
            if (email.trim() === "") {
                error = true;
                $("#invalid-email").text("L'indirizzo email è obbligatorio.");
                $("input[name='email']").addClass('is-invalid');
            }

            if (error) {
                event.preventDefault(); // Impedisce l'invio del modulo
                // Se c'è un errore, metti il focus sul primo campo invalido
                if (email.trim() === "") {
                    $("input[name='email']").focus();
                } else if (password.trim() === "") {
                    $("input[name='password']").focus();
                }
            }
        });

        $("#register-form").submit(function(event) {
            // Ottenere i valori dei campi per la registrazione
            var name = $("input[name='name']").val();
            var email = $("input[name='registration-email']").val();
            var password = $("input[name='registration-password']").val();
            // Espressione regolare per la password (almeno 8 caratteri, almeno una cifra, almeno
            // un carattere speciale tra ! - * [ ] $ & /)
            var passwordRegex = /^(?=.*[0-9])(?=.*[!-\*\[\]\$&\/]).{8,}$/;
            var confirmPassword = $("input[name='confirm-password']").val();
            var error = false;

            // Pulisci i messaggi di errore precedenti e rimuovi le classi di invalidità
            $("#invalid-name").text("");
            $("#invalid-registrationEmail").text("");
            $("#invalid-registrationPassword").text("");
            $("#invalid-confirmPassword").text("");
            $("input[name='name']").removeClass('is-invalid');
            $("input[name='registration-email']").removeClass('is-invalid');
            $("input[name='registration-password']").removeClass('is-invalid');
            $("input[name='confirm-password']").removeClass('is-invalid');

            // Validazioni in ordine inverso per il focus corretto
            if (confirmPassword.trim() === "") {
                error = true;
                $("#invalid-confirmPassword").text("La re-immissione della password è obbligatoria.");
                $("input[name='confirm-password']").addClass('is-invalid');
            }

            if (password.trim() === "") {
                error = true;
                $("#invalid-registrationPassword").text("La password è obbligatoria.");
                $("input[name='registration-password']").addClass('is-invalid');
            } else if(!passwordRegex.test(password)) {
                error = true;
                $("#invalid-registrationPassword").text("Il formato della password è sbagliato (almeno 8 caratteri, di cui almeno una cifra e un carattere tra ! - * [ ] $ & /).");
                $("input[name='registration-password']").addClass('is-invalid');
            }

            if (email.trim() === "") {
                error = true;
                $("#invalid-registrationEmail").text("L'indirizzo email è obbligatorio.");
                $("input[name='registration-email']").addClass('is-invalid');
            }

            if (name.trim() === "") {
                error = true;
                $("#invalid-name").text("Il nome è obbligatorio.");
                $("input[name='name']").addClass('is-invalid');
            }

            if (error) {
                event.preventDefault(); // Impedisce l'invio del modulo
                // Imposta il focus sul primo campo con errore
                if (name.trim() === "") {
                    $("input[name='name']").focus();
                } else if (email.trim() === "") {
                    $("input[name='registration-email']").focus();
                } else if (password.trim() === "" || !passwordRegex.test(password)) {
                    $("input[name='registration-password']").focus();
                } else if (confirmPassword.trim() === "") {
                    $("input[name='confirm-password']").focus();
                }
                return; // Ferma l'esecuzione se ci sono errori di validazione iniziali
            }

            // Verifica che la password sia stata digitata due volte correttamente
            if(confirmPassword.trim() !== password.trim()) {
                $("#invalid-confirmPassword").text("Le password immesse non corrispondono.");
                $("input[name='confirm-password']").addClass('is-invalid');
                event.preventDefault(); // Impedisce l'invio del modulo
                $("input[name='confirm-password']").focus();
                return;
            } else {
                $("#invalid-confirmPassword").text("");
                $("input[name='confirm-password']").removeClass('is-invalid');
            }

            // Effettua chiamata AJAX per verificare che l'email dell'utente non sia già presente nel DB
            event.preventDefault(); // Impedisce preventivamente l'invio del modulo prima del controllo AJAX
            $.ajax({
                type: 'GET',
                url: '/ajaxUser', // Assicurati che questa route sia corretta e gestisca la verifica email
                data: {email: email.trim()},
                success: function (data) {
                    if (data.found) {
                        $("#invalid-registrationEmail").text("L'email esiste già nel database.");
                        $("input[name='registration-email']").addClass('is-invalid').focus();
                    } else {
                        $("#invalid-registrationEmail").text("");
                        $("input[name='registration-email']").removeClass('is-invalid');
                        // Se tutti i controlli passano, invia il modulo di registrazione
                        $("#register-form")[0].submit(); // [0] per accedere all'elemento DOM del form
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX error: " + textStatus, errorThrown);
                    // Gestisci l'errore AJAX, ad esempio mostrando un messaggio all'utente
                    $("#invalid-registrationEmail").text("Si è verificato un errore durante la verifica dell'email.");
                    $("input[name='registration-email']").addClass('is-invalid');
                }
            });
        });
    });
</script>

<div class="container custom-container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="fw-light my-4 mb-0"><i class="bi bi-person-circle me-2"></i>Accesso Utente</h3>
                </div>
                <div class="card-body p-4">
                    <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#login-tab" type="button" role="tab" aria-controls="login-tab" aria-selected="true">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-register-tab" data-bs-toggle="pill" data-bs-target="#register-tab" type="button" role="tab" aria-controls="register-tab" aria-selected="false">
                                <i class="bi bi-person-plus me-2"></i>Registrati
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="login-tab" role="tabpanel" aria-labelledby="pills-login-tab">
                            <form id="login-form" action="{{ route('user.login') }}" method="post">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control" id="loginEmail" placeholder="Email..."/>
                                    <label for="loginEmail">Indirizzo Email</label>
                                    <div class="invalid-feedback d-block" id="invalid-email"></div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Password..."/>
                                    <label for="loginPassword">Password</label>
                                    <div class="invalid-feedback d-block" id="invalid-password"></div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg custom-btn-icon">
                                        <i class="bi bi-door-open-fill me-2"></i> Accedi
                                    </button>
                                </div>

                            </form>
                        </div>

                        <div class="tab-pane fade" id="register-tab" role="tabpanel" aria-labelledby="pills-register-tab">
                            <form id="register-form" action="{{ route('user.register') }}" method="post">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="text" name="name" class="form-control" id="registerName" placeholder="Il tuo nome..."/>
                                    <label for="registerName">Nome</label>
                                    <div class="invalid-feedback d-block" id="invalid-name"></div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" name="registration-email" class="form-control" id="registerEmail" placeholder="La tua email..."/>
                                    <label for="registerEmail">Indirizzo Email</label>
                                    <div class="invalid-feedback d-block" id="invalid-registrationEmail"></div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" name="registration-password" class="form-control" id="registerPassword" placeholder="Digita password..."/>
                                    <label for="registerPassword">Password</label>
                                    <div class="invalid-feedback d-block" id="invalid-registrationPassword"></div>
                                    <small class="text-muted">La password deve avere almeno 8 caratteri, inclusa una cifra e un carattere speciale (!-*[]$&/).</small>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" name="confirm-password" class="form-control" id="confirmPassword" placeholder="Ripeti password..."/>
                                    <label for="confirmPassword">Conferma Password</label>
                                    <div class="invalid-feedback d-block" id="invalid-confirmPassword"></div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-success btn-lg custom-btn-icon">
                                        <i class="bi bi-person-plus-fill me-2"></i> Registrati
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">
                        MyFootballManager &copy; 2025
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection