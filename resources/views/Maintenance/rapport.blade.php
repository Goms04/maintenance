<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports d'Intervention</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-width: 250px;
        }

        body {
            margin: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-gradient);
            color: white;
            position: fixed;
            top: 0;
            transition: all 0.3s ease;
            left: 0;
            padding-top: 2rem;
            overflow-y: auto;
        }

        .sidebar a {
            color: white;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .main {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .alert {
            margin-top: 1rem;
        }
    </style>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <h4><i class="fas fa-tools"></i> IDS Maintenance</h4>
        </div>

        {{-- Cas ADMIN (email/password statique ou utilisateur avec rôle admin) --}}
        @if(session('utilisateur') && session('utilisateur')->role == 'admin')
        <a href="{{ url('/') }}" class="active"><i class="fas fa-home me-2"></i> Accueil</a>
        <a href="liste"><i class="fas fa-wrench me-2"></i> Gérer Interventions</a>
        <a href="liste_client"><i class="fas fa-users me-2"></i> Gérer Clients</a>
        <a href="listeag"><i class="fas fa-building me-2"></i> Gérer Agences</a>
        <a href="listeequip"><i class="fas fa-boxes me-2"></i> Gérer Équipements</a>
        <a href="liste2"><i class="fas fa-chart-bar me-2"></i> Tous les Rapports</a>
        <hr>
        <div class="text-white px-3">
            Bienvenue, Administrateur |
            <a href="/logout" class="text-danger">
                <i class="fas fa-sign-out-alt me-2"></i>
                Déconnexion
            </a>
            <a href="/insc"><i class="fas fa-user-plus me-2"></i> Inscription</a>

        </div>

        {{-- Cas utilisateur connecté depuis la base --}}
        @elseif(Auth::check() && Auth::user()->role === 'technicien')
        <a href="{{ url('/') }}" class="active"><i class="fas fa-home me-2"></i> Accueil</a>
        <a href="liste"><i class="fas fa-wrench me-2"></i> Gérer Interventions</a>
        <a href="liste_client"><i class="fas fa-users me-2"></i> Gérer Clients</a>
        <a href="listeag"><i class="fas fa-building me-2"></i> Gérer Agences</a>
        <a href="listeequip"><i class="fas fa-boxes me-2"></i> Gérer Équipements</a>
        <a href="liste2"><i class="fas fa-chart-bar me-2"></i> Tous les Rapports</a>
        <hr>
        <div class="text-white px-3">
            Bienvenue, {{ Auth::user()->first_name }} |
            <a href="/logout" class="text-danger">
                <i class="fas fa-sign-out-alt me-2"> </i>
                Déconnexion
            </a>
            <a href="{{ route('password.edit') }}">
                <i class="fas fa-key me-2"></i>Changer votre mot de passe
            </a>
        </div>

        {{-- Aucun utilisateur connecté --}}
        @else
        <a href="/conn"><i class="fas fa-sign-in-alt me-2"></i> Connexion</a>
        <!--   <a href="/insc"><i class="fas fa-user-plus me-2"></i> Inscription</a> -->
        @endif
    </div>


    <div class="main">
        <div class="container-fluid py-4">

            <!-- En-tête -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h1 class="card-title mb-2">
                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                Rapports d'Intervention
                            </h1>
                            <p class="text-muted mb-0">Gestion des rapports d'intervention technique</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire d'ajout -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-plus-circle me-2"></i>
                                Nouveau rapport
                            </h5>
                        </div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif


                        <div class="card-body">
                            <form id="rapport-form" action="/Rapport_traitement" method="post">
                                @csrf
                                <input type="hidden" name="agence_id" value="{{ $agence->id ?? '' }}">

                                <!-- ID de l'intervention injecté par JS -->
                                <input type="hidden" name="intervention_id" value="{{ $interventionId }}">




                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-user me-1"></i> Client</label>
                                    <input type="text" class="form-control" value="{{ $client->name ?? 'Inconnu' }}" readonly>
                                </div>


                                <!-- Agence -->
                                <div class="mb-3">


                                    <label class="form-label"><i class="fas fa-building me-1"></i> Nom de l'agence</label>
                                    <input type="text" class="form-control" value="{{ $intervention->Nom_site?? 'Inconnue' }}" readonly>
                                </div>



                                <!-- Équipements -->
                                <div class="col-md-6 mb-3">
                                    <label for="materiel_id" class="form-label">
                                        Sélectionner un équipement existant
                                    </label>
                                    <select name="materiel_id" id="materiel_id" class="form-select">
                                        <option value="">-- Choisir un équipement --</option>
                                        @foreach($equipements as $equipement)
                                        <option value="{{ $equipement->id }}">
                                            {{ $equipement->type }} - {{ $equipement->brand }} - {{ $equipement->model }}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>


                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="observation" class="form-label">Observation <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="observation" name="observation" rows="3"
                                            placeholder="Décrivez le problème observé..." required></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="recommandation" class="form-label">Recommandations</label>
                                        <textarea class="form-control" id="recommandation" name="recommandation" rows="3"
                                            placeholder="Actions recommandées..."></textarea>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i>
                                        Enregistrer
                                    </button>
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.btn-nouveau-rapport').forEach(btn => {
                btn.addEventListener('click', function() {
                    const interventionId = this.getAttribute('data-id');
                    document.getElementById('intervention_id').value = interventionId;
                    // Scroll vers le formulaire automatiquement
                    document.getElementById('rapport-form').scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const selectAgence = document.getElementById('nom_site');
            const selectMateriel = document.getElementById('materiel_id');

            function filterEquipements(agenceId) {
                let options = selectMateriel.querySelectorAll('option');

                options.forEach(option => {
                    if (option.value === "") return; // garder option vide
                    option.style.display = (option.dataset.agence == agenceId || agenceId === '') ? 'block' : 'none';
                });

                selectMateriel.value = "";
            }

            // Filtrer au chargement avec l'agence sélectionnée
            filterEquipements(selectAgence.value);

            // Quand l'agence change, filtrer les équipements
            selectAgence.addEventListener('change', function() {
                filterEquipements(this.value);
            });
        });
    </script>
</body>

</html>