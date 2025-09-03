<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Accueil - IDS Maintenance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
</head>

<body>

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
    <!-- Main content -->
    
    <div class="main">
    <div class="container-fluid">

        {{-- Messages flash --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        {{-- En-tête de bienvenue --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <h2 class="card-title mb-2">Bienvenue sur IDS Maintenance</h2>
                <p class="card-text text-muted">
                    Utilisez le menu à gauche pour accéder aux différentes sections.
                </p>
            </div>
        </div>

        {{-- Titre Dashboard --}}
        <h3 class="mt-5 fw-bold">Tableau de Bord</h3>
        {{-- Statistiques globales --}}
        <div class="row g-3 mt-5">
            <div class="col-md-4">
                <div class="card text-center text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2">Total Clients</h6>
                        <h2 class="fw-bold">{{ $totalClients }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center text-white bg-success shadow-sm">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2">Total Agences</h6>
                        <h2 class="fw-bold">{{ $totalAgences }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center text-dark bg-warning shadow-sm">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2">Total Équipements</h6>
                        <h2 class="fw-bold">{{ $totalEquipements }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistiques par client --}}
        <h4 class="mt-5 fw-bold">Statistiques par Client</h4>
        <div class="row mt-5">
            @foreach($clients as $client)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $client->name }}</h5>
                            <p class="mb-1">
                                <strong>Agences :</strong> {{ $client->agences_count }}
                            </p>
                            <p class="mb-0">
                                <strong>Équipements :</strong> {{ $client->equipment_count }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>


    <!-- Bootstrap JS -->
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
</body>

</html>