<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Rapports</title>

    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        <div class="container py-4">

            <!-- Liste des rapports -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-list-ul me-2"></i> Liste des rapports
                            </h5>
                            <!--   <div class="d-flex gap-2">
                                @if (Auth::check() && Auth::user()->role === 'technicien')
                                {{-- Afficher uniquement pour les techniciens --}}
                                <a href="/rap" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-plus me-1"></i> Nouveau rapport
                                </a>
                                @endif
    -->




                            <a href="{{ route('rapports.tous_export') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-file-excel me-1"></i> Exporter tout
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Agence</th>
                                        <th>Équipement</th>
                                        <th class="text-center" width="180">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rapports as $rapport)
                                    <tr>
                                        <td><span class="badge bg-primary rounded-pill">{{ $loop->iteration }}</span></td>

                                        <td>{{ optional(optional($rapport->agency)->client)->name ?? 'Aucun client' }}</td>

                                        <td class="text-nowrap">
                                            {{ optional($rapport->agency)->name ?? 'Aucune agence' }}
                                        </td>
                                        </td>

                                        <td class="text-truncate" style="max-width: 250px;">
                                            {{ $rapport->materiel }}
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                @auth
                                                @if(auth()->user()->role !== 'admin')
                                                <a href="{{ url('/rapport/edit/' . $rapport->id) }}"
                                                    class="btn btn-outline-primary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif
                                                @endauth




                                                <!--     <form method="POST" action="{{ url('/rapport/delete/' . $rapport->id) }}"
                                                        onsubmit="return confirm('Supprimer ce rapport ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                        rkrk i                    <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>  -->

                                                <a href="{{ route('intervention.show', $rapport->intervention_id) }}"
                                                    class="btn btn-outline-secondary"
                                                    title="Voir l’intervention">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-exclamation-circle fs-4"></i><br>
                                            Aucun rapport trouvé.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    </div>

    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
</body>

</html>