<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Liste des agences</title>
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
            <div class="row">
                <div class="col-12">
                    <!-- En-tête avec titre et bouton d'ajout -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="h3 mb-1">Gestion des Agences</h2>
                            <p class="text-muted">Liste complète des agences enregistrées</p>
                        </div>
                        @if ((session('utilisateur') && session('utilisateur')->role === 'admin') ||
                        (Auth::check() && Auth::user()->role === 'admin'))
                        <a href="/agence" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle Agence
                        </a>
                        @endif
                    </div>
                    <!-- Statistiques rapides -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-building fa-2x"></i>
                                    </div>
                                    <h4 class="mb-1">{{ $agences->count() }}</h4>
                                    <small class="text-muted">Total Agences</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2">
                                        <i class="fas fa-tools fa-2x"></i>
                                    </div>
                                    <h4 class="mb-1">{{ $agences->sum(function($agence) { return $agence->equipment->count(); }) }}</h4>
                                    <small class="text-muted">Total Équipements</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des agences -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-table me-2"></i>Liste des Agences
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if($agences->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="ps-4">#</th>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Client</th>
                                            <th scope="col">Adresse</th>
                                            <th scope="col">Contact</th>
                                            <th scope="col">Téléphone</th>
                                            <th scope="col" class="text-center">Équipements</th>
                                            <th scope="col" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($agences as $agence)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px; font-size: 14px;">
                                                        {{ strtoupper(substr($agence->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $agence->name }}</h6>
                                                        <small class="text-muted">Créée {{ $agence->created_at->format('M Y') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($agence->client)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie text-muted me-2"></i>
                                                    <span class="badge bg-info text-white">{{ $agence->client->name }}</span>
                                                </div>
                                                @else
                                                <span class="text-muted">Aucun client</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $agence->address }}">
                                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                    {{ $agence->address }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-muted me-2"></i>
                                                    {{ $agence->contact_person }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-phone text-muted me-2"></i>
                                                    <a href="tel:{{ $agence->phone }}" class="text-decoration-none">
                                                        {{ $agence->phone }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($agence->equipment->count() > 0)
                                                <span class="badge bg-success">{{ $agence->equipment->count() }}</span>
                                                @else
                                                <span class="badge bg-secondary">0</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewAgenceModal{{ $agence->id }}"
                                                        title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
   @if ((session('utilisateur') && session('utilisateur')->role === 'admin') ||
                        (Auth::check() && Auth::user()->role === 'admin'))
                        
                       
                                                    <a href="{{ route('agences.edit', $agence->id) }}"
                                                        class="btn btn-sm btn-outline-warning"
                                                        title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteAgenceModal{{ $agence->id }}"
                                                        title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
 @endif
                                        <!-- Modal Voir les détails -->
                                        <div class="modal fade" id="viewAgenceModal{{ $agence->id }}" tabindex="-1" aria-labelledby="viewAgenceModalLabel{{ $agence->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-primary">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="viewAgenceModalLabel{{ $agence->id }}">
                                                            Détails de l'agence : {{ $agence->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item"><strong>Nom :</strong> {{ $agence->name }}</li>
                                                            <li class="list-group-item"><strong>Adresse :</strong> {{ $agence->address }}</li>
                                                            <li class="list-group-item"><strong>Contact :</strong> {{ $agence->contact_person ?? 'N/A' }}</li>
                                                            <li class="list-group-item"><strong>Téléphone :</strong> {{ $agence->phone ?? 'N/A' }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- MODALE DE SUPPRESSION -->
                                        <div class="modal fade" id="deleteAgenceModal{{ $agence->id }}" tabindex="-1" aria-labelledby="deleteAgenceModalLabel{{ $agence->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content border-danger">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="deleteAgenceModalLabel{{ $agence->id }}">Supprimer l'agence</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Nom caché de l’agence pour JS -->
                                                        <h6 class="d-none nom-agence">{{ $agence->nom_agence }}</h6>

                                                        <!-- Message de confirmation visible -->
                                                        <p class="message-confirmation">
                                                            Êtes-vous sûr de vouloir supprimer l'agence <strong>{{ $agence->nom_agence }}</strong> ?
                                                            Cette action est <span class="text-danger fw-bold">irréversible</span>.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('agences.destroy', $agence->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            @if($agences->hasPages())
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Affichage de {{ $agences->firstItem() }} à {{ $agences->lastItem() }}
                                        sur {{ $agences->total() }} résultats
                                    </div>
                                    {{ $agences->links() }}
                                </div>
                            </div>
                            @endif
                            @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-building fa-3x text-muted"></i>
                                </div>
                                <h5 class="text-muted">Aucune agence trouvée</h5>
                                <p class="text-muted mb-4">Commencez par ajouter votre première agence</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAgenceModal">
                                    <i class="fas fa-plus me-2"></i>Ajouter une Agence
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- CSS personnalisé -->
    <style>
        .avatar {
            font-weight: 600;
        }

        .table th {
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            border-radius: 0.375rem;
            margin: 0 1px;
        }

        .card {
            border-radius: 0.75rem;
        }

        .table-responsive {
            border-radius: 0.75rem;
        }
    </style>

    @push('scripts')
    <script>
        // Auto-refresh du tableau toutes les 30 secondes
        setTimeout(function() {
            location.reload();
        }, 30000);

        // Confirmation de suppression avec message personnalisé
        document.querySelectorAll('[data-bs-target^="#deleteAgenceModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const agenceId = this.getAttribute('data-bs-target').replace('#deleteAgenceModal', '');
                const modal = document.querySelector(`#deleteAgenceModal${agenceId}`);
                const nomAgence = modal?.querySelector('.nom-agence')?.textContent;

                const texte = modal?.querySelector('.modal-body p');
                if (texte && nomAgence) {
                    texte.textContent = `Êtes-vous sûr de vouloir supprimer l'agence "${nomAgence}" ? Cette action est irréversible.`;
                }
            });
        });
    </script>
    @endpush


</body>

</html>