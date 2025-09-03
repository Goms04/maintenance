<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Creer une intervention</title>
</head>
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


    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-tools me-2"></i>
                            Créer une Nouvelle Intervention
                        </h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="/intervention_traitement" method="POST">
                            @csrf

                            <!-- Nom du Technicien -->
                            <div class="mb-3">
                                <label for="nom_technicien" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nom et  Prenom du Technicien <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control @error('nom_technicien') is-invalid @enderror"
                                    id="nom_technicien"
                                    name="nom_technicien"
                                    value="{{ old('nom_technicien') }}"
                                    placeholder="Entrez le nom et le prenom du technicien"
                                    required>
                                @error('nom_technicien')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Date d'Intervention -->
                            <div class="mb-3">
                                <label for="date_intervention" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Date d'Intervention <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local"
                                    class="form-control @error('date_intervention') is-invalid @enderror"
                                    id="date_intervention"
                                    name="date_intervention"
                                    value="{{ old('date_intervention') }}"
                                    required>
                                @error('date_intervention')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Sélection client -->
                            <select class="form-select" id="clientSelect" name="client_id" required>
                                <option value="">-- Sélectionner un client --</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>

                            <!-- Sélection agence -->
                            <select class="form-select" id="agenceSelect" name="agence_id" required>
                                <option value="">-- Sélectionner une agence --</option>
                                @foreach($clients as $client)
                                @foreach($client->agences as $agence)
                                <option value="{{ $agence->id }}" data-client="{{ $client->id }}" style="display: none;">
                                    {{ $agence->name }}
                                </option>
                                @endforeach
                                @endforeach
                            </select>
                            <!-- Type d'Intervention -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-cog me-1"></i>
                                    Type d'Intervention <span class="text-danger">*</span>
                                </label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input @error('type_intervention') is-invalid @enderror"
                                                type="radio"
                                                name="type_intervention"
                                                id="preventive"
                                                value="PREVENTIVE"
                                                {{ old('type_intervention') == 'PREVENTIVE' ? 'checked' : '' }}
                                                required>
                                            <label class="form-check-label" for="preventive">
                                                <i class="fas fa-shield-alt text-success me-1"></i>
                                                Préventive
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input @error('type_intervention') is-invalid @enderror"
                                                type="radio"
                                                name="type_intervention"
                                                id="curative"
                                                value="CURATIVE"
                                                {{ old('type_intervention') == 'CURATIVE' ? 'checked' : '' }}
                                                required>
                                            <label class="form-check-label" for="curative">
                                                <i class="fas fa-wrench text-warning me-1"></i>
                                                Curative
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('type_intervention')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Description (Optionnel) -->
                            <div class="mb-4">
                                <label for="description" class="form-label">
                                    <i class="fas fa-comment me-1"></i>
                                    Description (Optionnel)
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description"
                                    name="description"
                                    rows="3"
                                    placeholder="Décrivez brièvement l'intervention...">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Boutons d'Action -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="/liste" class="btn btn-outline-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Créer l'Intervention
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script pour améliorer l'UX -->
    <script>
        const clientSelect = document.getElementById('clientSelect');
        const agenceSelect = document.getElementById('agenceSelect');

        clientSelect.addEventListener('change', function() {
            const clientId = this.value;

            for (let option of agenceSelect.options) {
                if (!option.value) {
                    option.style.display = '';
                    continue;
                }

                option.style.display = option.getAttribute('data-client') === clientId ? '' : 'none';
            }

            agenceSelect.value = ''; // Réinitialise l’agence
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Définir la date minimale à aujourd'hui
            const dateInput = document.getElementById('date_intervention');
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            dateInput.min = minDateTime;

            // Si aucune date n'est définie, utiliser la date actuelle
            if (!dateInput.value) {
                dateInput.value = minDateTime;
            }

            // Animation sur les radio buttons
            const radioButtons = document.querySelectorAll('input[name="type_intervention"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Retirer la classe active de tous les labels
                    document.querySelectorAll('.form-check-label').forEach(label => {
                        label.classList.remove('fw-bold', 'text-primary');
                    });

                    // Ajouter la classe active au label sélectionné
                    if (this.checked) {
                        const label = document.querySelector(`label[for="${this.id}"]`);
                        label.classList.add('fw-bold', 'text-primary');
                    }
                });
            });

            // Vérifier si un radio est déjà sélectionné au chargement
            const checkedRadio = document.querySelector('input[name="type_intervention"]:checked');
            if (checkedRadio) {
                const label = document.querySelector(`label[for="${checkedRadio.id}"]`);
                label.classList.add('fw-bold', 'text-primary');
            }
        });
    </script>

</body>

</html>