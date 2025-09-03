<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>page de connexion</title>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Connexion
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Messages d'alerte -->
                        @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form method="POST" action="/traitement connexion" id="loginForm">
                            @csrf
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Adresse email <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    autofocus
                                    placeholder="Entrez votre adresse email">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Mot de passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Mot de passe <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Entrez votre mot de passe">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role">Se connecter en tant que :</label>
                                <select name="role" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="admin">Administrateur</option>
                                    <option value="technicien">Technicien</option>
                                </select>
                            </div>

                            <!-- Se souvenir de moi -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                            </div>

                            <!-- Bouton de connexion -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                                    <i class="fas fa-sign-in-alt me-1"></i>
                                    Se connecter
                                </button>
                            </div>

                            <!-- Liens -->
                            <div class="text-center">
                                @if (Route::has('password.request'))
                                <a href="" class="text-decoration-none">
                                    <i class="fas fa-question-circle me-1"></i>
                                    Mot de passe oublié ?
                                </a>
                                @endif
                            </div>
                        </form>

                        <!-- Lien d'inscription -->
                     <!--     <div class="text-center">
                            <p class="mb-0">
                                Pas encore de compte ?
                                <a href="/insc" class="text-primary text-decoration-none fw-bold">
                                    <i class="fas fa-user-plus me-1"></i>
                                    S'inscrire
                                </a>
                            </p>
                        </div>
                    </div>
                     -->
                </div>

                <!-- Informations supplémentaires -->
                <div class="card mt-4 border-0">
                    <div class="card-body bg-light text-center">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Vos données sont sécurisées et protégées
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de chargement -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 mb-0">Connexion en cours...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });

            // Form submission avec loading
            const form = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

            form.addEventListener('submit', function(e) {
                // Validation basique
                const email = document.getElementById('email').value;
                const passwordValue = document.getElementById('password').value;

                if (!email || !passwordValue) {
                    e.preventDefault();
                    showAlert('Veuillez remplir tous les champs obligatoires', 'danger');
                    return;
                }

                // Validation email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    showAlert('Veuillez entrer une adresse email valide', 'danger');
                    return;
                }

                // Afficher le modal de chargement
                loginBtn.disabled = true;
                loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Connexion...';
                loadingModal.show();

                // Simuler un délai (à supprimer en production)
                setTimeout(() => {
                    loadingModal.hide();
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt me-1"></i>Se connecter';
                }, 2000);
            });

            // Connexion sociale (exemple - à adapter selon vos besoins)
            document.getElementById('googleLogin').addEventListener('click', function() {
                showAlert('Connexion Google en cours de développement', 'info');
            });

            document.getElementById('facebookLogin').addEventListener('click', function() {
                showAlert('Connexion Facebook en cours de développement', 'info');
            });

            // Animation d'entrée
            const card = document.querySelector('.card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });

        // Fonction pour afficher des alertes
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
        <i class="fas fa-${type === 'danger' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

            const form = document.getElementById('loginForm');
            form.insertBefore(alertDiv, form.firstChild);

            // Supprimer l'alerte après 5 secondes
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>

    <style>
        .card {
            border: none;
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .btn-primary:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .invalid-feedback {
            display: block;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .input-group .btn {
            border-color: #ced4da;
        }

        /* Séparateur */
        .separator {
            position: relative;
            text-align: center;
            margin: 20px 0;
        }

        .separator:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }

        .separator-text {
            background: white;
            color: #6c757d;
            padding: 0 15px;
            font-size: 0.875rem;
        }

        /* Boutons sociaux */
        .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Animations */
        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .container {
                padding: 10px;
            }

            .card {
                margin: 10px 0;
            }

            .btn-lg {
                padding: 0.75rem 1.25rem;
                font-size: 1.1rem;
            }
        }

        /* Loading spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Focus visible pour l'accessibilité */
        .btn:focus-visible {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .form-control:focus-visible {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>

</body>

</html>