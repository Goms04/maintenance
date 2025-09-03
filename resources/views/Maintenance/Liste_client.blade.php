<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Liste des clients</title>
</head>

<body>
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
.sidebar .active {
    background-color: rgba(255, 255, 255, 0.1);
}
        

        /* Main content */
        .main {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }

        /* Carte */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .badge {
            font-size: 0.7rem;
        }

        .btn-group .btn {
            flex: 1;
        }

        .alert {
            margin-top: 1rem;
        }

        /* Horizontal scroll container */
        .cards-container {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 1rem;
            scroll-behavior: smooth;
        }

        .cards-container::-webkit-scrollbar {
            height: 8px;
        }

        .cards-container::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
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
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 text-dark">Services</h1>

                @if ((session('utilisateur') && session('utilisateur')->role === 'admin') ||
                (Auth::check() && Auth::user()->role === 'admin'))
                <a href="/services" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouveau Client
                </a>
                @endif


            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($clients->count() > 0)
            <div class="row">
                @foreach($clients as $client)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-truncate me-2">{{ $client->name }}</h5>
                            <span class="badge bg-primary">{{ $client->category }}</span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Description -->
                            @if($client->description)
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($client->description, 100) }}
                            </p>
                            @endif

                            <!-- Statistiques -->
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="text-success mb-1">{{ $client->agences->count() }}</h6>
                                        <small class="text-muted">Agences</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-warning mb-1">{{ $client->equipment->count() }}</h6>
                                    <small class="text-muted">Équipements</small>
                                </div>
                            </div>

                            <!-- Agences -->
                            <div class="mb-3">
                                <h6 class="small fw-bold text-secondary mb-2">
                                    <i class="fas fa-building me-1"></i>Agences
                                </h6>
                                @if($client->agences->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($client->agences->take(3) as $agence)
                                    <span class="badge bg-success bg-opacity-10 text-success small">
                                        {{ $agence->name }}
                                    </span>
                                    @endforeach
                                    @if($client->agences->count() > 3)
                                    <span class="badge bg-light text-muted small">
                                        +{{ $client->agences->count() - 3 }} autres
                                    </span>
                                    @endif
                                </div>
                                @else
                                <small class="text-muted fst-italic">Aucune agence assignée</small>
                                @endif
                            </div>

                            <!-- Équipements -->
                            <div class="mb-3 flex-grow-1">
                                <h6 class="small fw-bold text-secondary mb-2">
                                    <i class="fas fa-tools me-1"></i>Équipements
                                </h6>
                                @if($client->equipment->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($client->equipment->take(3) as $equipement)
                                    <span class="badge bg-warning bg-opacity-10 text-warning small">
                                        {{ $equipement->name }}
                                    </span>
                                    @endforeach
                                    @if($client->equipment->count() > 3)
                                    <span class="badge bg-light text-muted small">
                                        +{{ $client->equipment->count() - 3 }} autres
                                    </span>
                                    @endif
                                </div>
                                @else
                                <small class="text-muted fst-italic">Aucun équipement disponible</small>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card-footer bg-transparent">
                            <div class="btn-group w-100" role="group">
                                <a href="#"
                                    class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#quickViewModal"
                                    data-name="{{ $client->name }}"
                                    data-description="{{ $client->description }}"
                                    data-type="{{ $client->type }}"
                                    data-agences="{{ $client->agences->pluck('name')->implode(',') }}"
                                    data-equipements="{{ $client->equipment->pluck('name')->implode(',') }}"
                                    onclick="loadQuickView(this)">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                                @if ((session('utilisateur') && session('utilisateur')->role === 'admin') ||
                                (Auth::check() && Auth::user()->role === 'admin'))
                                <a href="{{ url('/editclient/' . $client->id) }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-edit me-1"></i>Modifier
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $client->id }}">
                                    <i class="fas fa-trash me-1"></i>Supprimer
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmation de suppression -->
            <div class="modal fade" id="deleteModal{{ $client->id }}" tabindex="-1"
                aria-labelledby="deleteModalLabel{{ $client->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel{{ $client->id }}">
                                Confirmer la suppression
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir supprimer le client <strong>{{ $client->name }}</strong> ?</p>
                            <div class="alert alert-warning">
                                <small>
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Cette action supprimera également toutes les associations avec les agences et équipements.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <form action="{{ route('suppression', $client->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($clients instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center mt-4">
            {{ $clients->links() }}
        </div>
        @endif
        @else
        <!-- État vide -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="fas fa-concierge-bell fa-3x text-muted mb-3"></i>
                        <h4 class="card-title text-muted">Aucun client disponible</h4>
                        <p class="card-text text-muted mb-4">
                            Vous n'avez encore créé aucun client. Commencez par ajouter votre premier client.
                        </p>
                        <a href="/services" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Créer mon premier client
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal de détails rapides (optionnel) -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickViewModalLabel">Détails du client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="quickViewContent">
                    <!-- Contenu chargé dynamiquement -->
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .badge {
            font-size: 0.7rem;
        }

        .btn-group .btn {
            flex: 1;
        }
    </style>
    @endpush

    <script>

        
 // Sélection de tous les liens du sidebar
    
 
 document.addEventListener('DOMContentLoaded', function() {
    const sidebarLinks = document.querySelectorAll('.sidebar a');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Retirer la classe active de tous les liens
            sidebarLinks.forEach(l => l.classList.remove('active'));

            // Ajouter la classe active au lien cliqué
            this.classList.add('active');
        });
    });
});

        // Fonction appelée par onclick sur le bouton "Voir"
        window.loadQuickView = function(button) {
            const name = button.getAttribute('data-name');
            const description = button.getAttribute('data-description');
            const type = button.getAttribute('data-type');
            const agences = button.getAttribute('data-agences')?.split(',') || [];
            const equipements = button.getAttribute('data-equipements')?.split(',') || [];

            let agencesHtml = agences.length ?
                agences.map(a => `<span class="badge bg-success bg-opacity-10 text-success small me-1">${a}</span>`).join('') :
                '<small class="text-muted fst-italic">Aucune agence assignée</small>';

            let equipementsHtml = equipements.length ?
                equipements.map(e => `<span class="badge bg-warning bg-opacity-10 text-warning small me-1">${e}</span>`).join('') :
                '<small class="text-muted fst-italic">Aucun équipement assigné</small>';

            const content = `
            <p><strong>Nom du client :</strong> ${name}</p>
           
            <hr>
            <p><strong>Agences :</strong><br>${agencesHtml}</p>
            <p><strong>Équipements :</strong><br>${equipementsHtml}</p>
        `;

            document.getElementById('quickViewContent').innerHTML = content;
        };
    </script>


</body>

</html>