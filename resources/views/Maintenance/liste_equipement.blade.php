<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Liste des equipements</title>
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
        <div class="container-fluid mt-5">

            <!-- filtrage -->
            <form method="GET" action="{{ url('/listeequip') }}" class="row g-3 px-3 py-2">
                <div class="col-md-3">
                    <select name="client" class="form-select">
                        <option value="">-- Tous les clients --</option>
                        @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="brand" class="form-control" placeholder="Marque" value="{{ request('brand') }}">
                </div>
                <div class="col-md-3">
                    <select name="agence" class="form-select">
                        <option value="">-- Toutes les agences --</option>
                        @foreach ($agences as $agence)
                        <option value="{{ $agence->id }}" {{ request('agence') == $agence->id ? 'selected' : '' }}>
                            {{ $agence->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" name="type" class="form-control" placeholder="Type équipement" value="{{ request('type') }}">
                </div>

                <div class="col-md-3 d-flex">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-filter me-1"></i>Filtrer
                    </button>
                    <a href="{{ url('/listeequip') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt me-1"></i>Réinitialiser
                    </a>
                </div>
            </form>

            <div class="col-lg-12">
                <!-- Liste des équipements -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-toolbox me-2"></i>Liste des Équipements</h5>
                        <a href="/equip" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>Ajouter un équipement
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Marque</th>
                                    <th>Modèle</th>
                                    <th>N° Série</th>
                                    <th>Date installation</th>
                                    <th>Fin garantie</th>
                                    <th>Client</th>
                                    <th>Agence</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($equipements as $item)
                                <tr>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->brand }}</td>
                                    <td>{{ $item->model }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>{{ $item->installation_date }}</td>
                                    <td>{{ $item->warranty_end_date }}</td>
                                    <td>{{ optional(optional($item->agency)->client)->name ?? 'Aucun client' }}</td>
                                    <td>{{ optional($item->agency)->name ?? 'Aucune agence' }}</td>



                                    <td>
                                        <a href="{{ url('/modifier/' . $item->id) }}" class="btn btn-outline-primary btn-sm me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ url('/supprimer/' . $item->id) }}" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Voulez-vous vraiment supprimer cet équipement ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-muted">Aucun équipement disponible.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>