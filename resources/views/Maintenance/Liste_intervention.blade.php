<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Liste des interventions</title>
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

        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-12">
                    <!-- En-tête avec bouton d'ajout -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">
                            <i class="fas fa-list-alt text-primary me-2"></i>
                            Liste des Interventions
                        </h2>
                        <a href="/inter" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Nouvelle Intervention
                        </a>
                    </div>

                    <!-- Filtres -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" action="/liste">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label for="filter_agence" class="form-label">Client</label>
                                        <select name="agence" id="filter_agence" class="form-select">
                                            <option value="">Toutes les agences</option>
                                            <option value="UTB(Union Togolaise des Banques" {{ request('agence') == 'UTB(Union Togolaise des Banques' ? 'selected' : '' }}>UTB(Union Togolaise des Banques</option>

                                            <option value="ORABANK" {{ request('agence') == 'ORABANK' ? 'selected' : '' }}>ORABANK</option>
                                            <option value="BSIC" {{ request('agence') == 'BSIC' ? 'selected' : '' }}>BSIC</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filter_type" class="form-label">Type</label>
                                        <select name="type" id="filter_type" class="form-select">
                                            <option value="">Tous les types</option>
                                            <option value="PREVENTIVE" {{ request('type') == 'PREVENTIVE' ? 'selected' : '' }}>Préventive</option>
                                            <option value="CURATIVE" {{ request('type') == 'CURATIVE' ? 'selected' : '' }}>Curative</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filter_statut" class="form-label">Statut</label>
                                        <select name="statut" id="filter_statut" class="form-select">
                                            <option value="">Tous les statuts</option>
                                            <option value="a_venir" {{ request('statut') == 'a_venir' ? 'selected' : '' }}>À venir</option>

                                            <option value="terminee" {{ request('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                                            <option value="non_effectuee" {{ request('statut') == 'non_effectuee' ? 'selected' : '' }}>Non effectuée</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filter_realisation" class="form-label">Réalisation</label>
                                        <select name="realisation" id="filter_realisation" class="form-select">
                                            <option value="">Tous</option>
                                            <option value="effectuee" {{ request('realisation') == 'effectuee' ? 'selected' : '' }}>Effectuée</option>
                                            <option value="non_effectuee" {{ request('realisation') == 'non_effectuee' ? 'selected' : '' }}>Non effectuée</option>

                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-outline-primary me-2">
                                            <i class="fas fa-filter me-1"></i>Filtrer
                                        </button>
                                        <a href="/liste" class="btn btn-outline-secondary">
                                            <i class="fas fa-undo me-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Messages de succès/erreur -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Tableau des interventions -->
                    <div class="card shadow">
                        <div class="card-body">
                            @if($interventions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">
                                                <i class="fas fa-user me-1"></i>Technicien
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-calendar me-1"></i>Date
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-building me-1"></i>Client
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-building me-1"></i>Agence
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-cog me-1"></i>Type
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-info-circle me-1"></i>Statut
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-check-circle me-1"></i>Réalisation
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-comment me-1"></i>Description
                                            </th>
                                            <th scope="col" class="text-center">
                                                <i class="fas fa-tools me-1"></i>Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($interventions as $index => $intervention)
                                        @php
                                        // Convertir la date en objet Carbon
                                        try {
                                        $dateIntervention = \Carbon\Carbon::parse($intervention->Date);
                                        } catch (Exception $e) {
                                        $dateIntervention = \Carbon\Carbon::now();
                                        }

                                        $now = \Carbon\Carbon::now();
                                        $isPasse = $dateIntervention->isPast();
                                        $isToday = $dateIntervention->isToday();
                                        $isFuture = $dateIntervention->isFuture();
                                        $isDeuxJoursDepasse = $dateIntervention->diffInDays($now) > 2 && $isPasse;

                                        // Logique de réalisation automatique
                                        $estEffectuee = $intervention->est_effectuee ?? null;
                                        $peutEtreMarquee = $isPasse || $isToday;

                                        // Marquer automatiquement comme non effectuée si > 2 jours et pas cochée
                                        if ($isDeuxJoursDepasse && is_null($estEffectuee)) {
                                        $estEffectuee = false;
                                        // Ici vous devriez mettre à jour la base de données
                                        // DB::table('interventions')->where('id', $intervention->id)->update(['est_effectuee' => false]);
                                        }


                                        // Déterminer le statut - CORRECTION
                                        if ($isFuture) {
                                        $statut = 'a_venir';
                                        $statutLabel = 'À venir';
                                        $statutClass = 'info';
                                        $statutIcon = 'fas fa-calendar-plus';
                                        } elseif ($estEffectuee === true) {
                                        $statut = 'terminee';
                                        $statutLabel = 'Terminée';
                                        $statutClass = 'success';
                                        $statutIcon = 'fas fa-check-circle';
                                        } elseif ($estEffectuee === false) {
                                        $statut = 'non_effectuee';
                                        $statutLabel = 'Non effectuée';
                                        $statutClass = 'danger';
                                        $statutIcon = 'fas fa-times-circle';
                                        } else {
                                        // Cas par défaut pour est_effectuee === null
                                        $statut = 'en_attente';
                                        $statutLabel = 'En attente';
                                        $statutClass = 'warning';
                                        $statutIcon = 'fas fa-clock';
                                        }



                                        // Classe de ligne selon le statut
                                        $rowClass = '';
                                        if ($estEffectuee === true) {
                                        $rowClass = 'table-success';
                                        } elseif ($estEffectuee === false) {
                                        $rowClass = 'table-danger';
                                        } elseif ($isToday) {
                                        $rowClass = 'table-warning';
                                        } elseif ($isDeuxJoursDepasse && is_null($estEffectuee)) {
                                        $rowClass = 'table-light';
                                        }

                                        @endphp

                                        <tr class="{{ $rowClass }}">
                                            <th scope="row">{{ $index + 1 }}</th>

                                            <!-- Technicien -->
                                            <td>
                                                <strong>{{ $intervention->Nom }}</strong>
                                            </td>

                                            <!-- Date -->
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">
                                                        {{ $dateIntervention->format('d/m/Y') }}
                                                    </span>
                                                    <small class="text-muted">
                                                        {{ $dateIntervention->format('H:i') }}
                                                    </small>
                                                    @if($isPasse && !$isToday)
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $dateIntervention->diffForHumans() }}
                                                    </small>
                                                    @elseif($isToday)
                                                    <small class="text-warning">
                                                        <i class="fas fa-calendar-day me-1"></i>
                                                        Aujourd'hui
                                                    </small>
                                                    @elseif($isFuture)
                                                    <small class="text-info">
                                                        <i class="fas fa-calendar-plus me-1"></i>
                                                        {{ $dateIntervention->diffForHumans() }}
                                                    </small>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Agence -->
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $intervention->Nom_Agence }}
                                                </span>
                                            </td>

                                            <!-- Nom du site de l'Agence -->
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $intervention->Nom_site }}
                                                </span>
                                            </td>

                                            <!-- Type -->
                                            <td>
                                                @if($intervention->Type_intervention == 'PREVENTIVE')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-shield-alt me-1"></i>
                                                    Préventive
                                                </span>
                                                @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-wrench me-1"></i>
                                                    Curative
                                                </span>
                                                @endif
                                            </td>

                                            <!-- Statut -->



                                            <td>
                                                <span class="badge bg-{{ $statutClass }} d-flex align-items-center" style="width: fit-content;">
                                                    <i class="{{ $statutIcon }} me-1"></i>
                                                    {{ $statutLabel }}
                                                </span>
                                            </td>



                                            <!-- Réalisation -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($intervention->est_effectuee === true)
                                                    <span class="badge bg-success me-2">
                                                        <i class="fas fa-check me-1"></i> Effectuée
                                                    </span>
                                                    @elseif($intervention->est_effectuee === false)
                                                    <span class="badge bg-danger me-2">
                                                        <i class="fas fa-times me-1"></i> Non effectuée
                                                    </span>
                                                    @endif

                                                    {{-- Toujours afficher les boutons de changement --}}
                                                    <button type="button"
                                                        class="btn btn-outline-success btn-sm me-2"
                                                        onclick="marquerEffectuee({{ $intervention->id }}, true)"
                                                        title="Marquer comme effectuée">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="marquerEffectuee({{ $intervention->id }}, false)"
                                                        title="Marquer comme non effectuée">
                                                        <i class="fas fa-times"></i>
                                                    </button>


                                                </div>
                                            </td>

                                            <!-- Description -->
                                            <td style="max-width: 200px;">
                                                @if($intervention->Description)
                                                <span class="text-truncate d-block" title="{{ $intervention->Description }}">
                                                    {{ Str::limit($intervention->Description, 50) }}
                                                </span>
                                                @else
                                                <span class="text-muted fst-italic">Aucune description</span>
                                                @endif
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <!-- Voir -->
                                                    <button type="button"
                                                        class="btn btn-outline-info btn-sm"
                                                        title="Voir les détails"
                                                        onclick="voirDetails(
        {{ $intervention->id }},
        @js($intervention->Nom),
        @js($dateIntervention->format('d/m/Y H:i')),
        @js($intervention->Nom_Agence),
        @js($intervention->Nom_site),
        @js($intervention->Type_intervention),
        @js($intervention->Description),
        @js($intervention->statut),
        @js($intervention->est_effectuee)
    )">
                                                        <i class="fas fa-eye"></i>
                                                    </button>


                                                    <!-- Modifier (seulement si pas terminé définitivement) -->
                                                    @if($statut != 'terminee' )
                                                    <a href="/update/{{$intervention->id}}"
                                                        class="btn btn-outline-warning btn-sm"
                                                        title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endif

                                                    <!-- Supprimer -->
                                                    @if ((session('utilisateur') && session('utilisateur')->role === 'admin') ||
                                                    (Auth::check() && Auth::user()->role === 'admin'))
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                        title="Supprimer"
                                                        onclick="confirmerSuppression({{ $intervention->id }}, '{{ $intervention->Nom }}', '{{ $dateIntervention->format('d/m/Y') }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                    @endif


                                                    <!-- Créer un rapport -->
                                                    @if(auth()->check() && auth()->user()->role !== 'admin')
                                                    @if($statut == 'terminee' && $statut != 'non_effectuee')
                                                    <button type="button"
                                                        class="btn btn-outline-primary btn-sm"
                                                        title="Créer un rapport"
                                                        onclick="window.location.href='/rap?intervention_id={{ $intervention->id }}'">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    @endif
                                                    @endif




                                                    <!-- Importer un rapport depuis la base -->
                                                    @if($statut == 'terminee' && $statut != 'non_effectuee')
                                                    <a href="{{ route('rapports.export', $intervention->id) }}"
                                                        class="btn btn-outline-success btn-sm"
                                                        title="Télécharger rapport Excel">
                                                        <i class="fas fa-file-excel"></i>
                                                    </a>
                                                    @endif
                                                    <!-- Voir les rapports -->
                                                    @if($statut == 'terminee' && $statut != 'non_effectuee')
                                                    <a href="/rapports/{{ $intervention->id }}" class="btn btn-outline-info btn-sm" title="Voir rapport">

                                                        <i class="fas fa-file-lines"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Statistiques -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">
                                                <i class="fas fa-chart-pie me-2"></i>
                                                Résumé des interventions
                                            </h6>
                                            <div class="row text-center">
                                                @php
                                                $total = $interventions->count();
                                                $aVenir = $interventions->filter(function($item) {
                                                return \Carbon\Carbon::parse($item->Date)->isFuture();
                                                })->count();
                                                $enCours = $interventions->filter(function($item) {
                                                return \Carbon\Carbon::parse($item->Date)->isToday();
                                                })->count();
                                                $effectuees = $interventions->filter(function($item) {
                                                return $item->est_effectuee === true;
                                                })->count();
                                                $nonEffectuees = $interventions->filter(function($item) {
                                                return $item->est_effectuee === false;
                                                })->count();
                                                $enAttente = $interventions->filter(function($item) {
                                                $date = \Carbon\Carbon::parse($item->Date);
                                                return ($date->isPast() || $date->isToday()) && is_null($item->est_effectuee);
                                                })->count();
                                                @endphp

                                                <div class="col-md-2">
                                                    <div class="d-flex flex-column">
                                                        <span class="h4 text-primary">{{ $total }}</span>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="d-flex flex-column">
                                                        <span class="h4 text-info">{{ $aVenir }}</span>
                                                        <small class="text-muted">À venir</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="d-flex flex-column">
                                                        <span class="h4 text-success">{{ $effectuees }}</span>
                                                        <small class="text-muted">Effectuées</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="d-flex flex-column">
                                                        <span class="h4 text-danger">{{ $nonEffectuees }}</span>
                                                        <small class="text-muted">Non effectuée</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucune intervention trouvée</h5>
                                <p class="text-muted">Commencez par créer votre première intervention.</p>
                                <a href="/inter" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Créer une intervention
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de détails -->
        <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Détails de l'intervention
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Technicien:</strong>
                                <p id="detailTechnicien" class="text-muted"></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Date et heure:</strong>
                                <p id="detailDate" class="text-muted"></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Client:</strong>
                                <p id="detailClient" class="text-muted"></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Agence:</strong>
                                <p id="detailAgence" class="text-muted"></p>
                            </div>


                            <div class="col-md-6">
                                <strong>Type d'intervention:</strong>
                                <p id="detailType" class="text-muted"></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Statut:</strong>
                                <p id="detailStatut" class="text-muted"></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Réalisation:</strong>
                                <p id="detailRealisation" class="text-muted"></p>
                            </div>
                            <div class="col-12">
                                <strong>Description:</strong>
                                <p id="detailDescription" class="text-muted"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation de suppression -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Confirmer la suppression
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer l'intervention du technicien <strong id="technicienNom"></strong> prévue le <strong id="dateIntervention"></strong> ?</p>
                        <p class="text-muted">Cette action est irréversible.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        <form id="deleteForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Fonction pour confirmer la suppression
        document.addEventListener('DOMContentLoaded', function() {
            window.confirmerSuppression = function(interventionId, technicienNom, dateIntervention) {
                document.getElementById('technicienNom').textContent = technicienNom;
                document.getElementById('dateIntervention').textContent = dateIntervention;
                document.getElementById('deleteForm').action = `/interventions/${interventionId}`;
                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            };

            // Fonction pour voir les détails
            window.voirDetails = function(id, technicien, date, client, agence, type, description, statut, realisation) {
                document.getElementById('detailTechnicien').textContent = technicien;
                document.getElementById('detailDate').textContent = date;
                document.getElementById('detailClient').textContent = client;
                document.getElementById('detailAgence').textContent = agence;
                document.getElementById('detailType').textContent = type === 'PREVENTIVE' ? 'Préventive' : 'Curative';
                document.getElementById('detailStatut').textContent = statut || 'Non précisé';
                document.getElementById('detailDescription').textContent = description || 'Aucune description';

                let realisationText = '';
                if (realisation === 'true') {
                    realisationText = 'Effectuée';
                } else if (realisation === 'false') {
                    realisationText = 'Non effectuée';
                } else {
                    realisationText = 'Non précisée';
                }
                document.getElementById('detailRealisation').textContent = realisationText;

                const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                modal.show();
            };


            // Fonction pour marquer une intervention comme effectuée/non effectuée
            window.marquerEffectuee = function(interventionId, statut) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/interventions/${interventionId}/marquer-effectuee`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            est_effectuee: statut
                        }),
                    })
                    .then(async response => {
                        if (!response.ok) {
                            const text = await response.text();
                            console.error('Réponse erreur HTML :', text);
                            throw new Error('Réponse serveur non valide');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erreur lors de la mise à jour');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors de la mise à jour');
                    });
            };

            // Auto-refresh de la page toutes les 5 minutes pour mettre à jour les statuts
            setTimeout(function() {
                if (!document.hidden) {
                    location.reload();
                }
            }, 300000)
        })
    </script>
</body>

</html>