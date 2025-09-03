<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>Equipements</title>
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
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            Ajouter un Nouvel Équipement
                        </h2>
                        <a href="/listeequip" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                        </a>
                    </div>

                    <!-- Messages d'erreur globaux -->
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Erreurs détectées :</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Formulaire principal -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-server me-2"></i>Informations de l'Équipement
                            </h5>
                        </div>

                        <div class="card-body">
                            <form action="/equipement_traitement" method="POST" id="equipmentForm">
                                @csrf

                                <div class="row">
                                    <!-- Colonne gauche -->
                                    <div class="col-md-6">
                                        <!-- Agence -->
                                        <div class="mb-3">
                                            <label for="agency_id" class="form-label">
                                                <i class="fas fa-building text-info me-1"></i>
                                                Agence <span class="text-danger">*</span>
                                            </label>
                                          <select name="agency_id" id="agency_id" class="form-select" required>
    <option value="">-- Sélectionner une agence --</option>
    @foreach($agences as $agency)
        <option value="{{ $agency->id }}">
            {{ $agency->name }} ({{ optional($agency->client)->name ?? 'Client inconnu' }})
        </option>
    @endforeach
</select>

                                            @error('agency_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Choisissez l'agence où sera installé l'équipement</div>
                                        </div>

                                        <!-- Type d'équipement -->
                                        <div class="mb-3">
                                            <label for="type" class="form-label">
                                                <i class="fas fa-tags text-success me-1"></i>
                                                Type d'Équipement <span class="text-danger">*</span>
                                            </label>
                                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                                <option value="">-- Sélectionner un type --</option>
                                                <option value="onduleur" {{ old('type') == 'onduleur' ? 'selected' : '' }}>
                                                    <i class="fas fa-plug"></i> Onduleur
                                                </option>
                                                <option value="serveur" {{ old('type') == 'serveur' ? 'selected' : '' }}>
                                                    <i class="fas fa-server"></i> Serveur
                                                </option>
                                                <option value="switch" {{ old('type') == 'switch' ? 'selected' : '' }}>
                                                    <i class="fas fa-network-wired"></i> Switch
                                                </option>
                                                <option value="routeur" {{ old('type') == 'routeur' ? 'selected' : '' }}>
                                                    <i class="fas fa-wifi"></i> Routeur
                                                </option>
                                                <option value="firewall" {{ old('type') == 'firewall' ? 'selected' : '' }}>
                                                    <i class="fas fa-shield-alt"></i> Firewall
                                                </option>
                                                <option value="stockage" {{ old('type') == 'stockage' ? 'selected' : '' }}>
                                                    <i class="fas fa-hdd"></i> Stockage
                                                </option>
                                                <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>
                                                    <i class="fas fa-question"></i> Autre
                                                </option>
                                            </select>
                                            @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Marque -->
                                        <div class="mb-3">
                                            <label for="brand" class="form-label">
                                                <i class="fas fa-trademark text-warning me-1"></i>
                                                Marque <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                name="brand"
                                                id="brand"
                                                class="form-control @error('brand') is-invalid @enderror"
                                                value="{{ old('brand') }}"
                                                placeholder="Ex: Dell, HP, Cisco, APC..."
                                                required>
                                            @error('brand')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Modèle -->
                                        <div class="mb-3">
                                            <label for="model" class="form-label">
                                                <i class="fas fa-cube text-secondary me-1"></i>
                                                Modèle <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                name="model"
                                                id="model"
                                                class="form-control @error('model') is-invalid @enderror"
                                                value="{{ old('model') }}"
                                                placeholder="Ex: PowerEdge R740, Smart-UPS 3000VA..."
                                                required>
                                            @error('model')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Statut -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">
                                                <i class="fas fa-circle text-success me-1"></i>
                                                Statut <span class="text-danger">*</span>
                                            </label>
                                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                                <option value="">-- Sélectionner un statut --</option>
                                                <option value="actif" {{ old('status') == 'actif' ? 'selected' : '' }}>
                                                    <span class="text-success">●</span> Actif
                                                </option>
                                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                                                    <span class="text-warning">●</span> En maintenance
                                                </option>
                                                <option value="hors_service" {{ old('status') == 'hors_service' ? 'selected' : '' }}>
                                                    <span class="text-danger">●</span> Hors service
                                                </option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Colonne droite -->
                                    <div class="col-md-6">
                                        <!-- Numéro de série -->
                                        <div class="mb-3">
                                            <label for="serial_number" class="form-label">
                                                <i class="fas fa-barcode text-primary me-1"></i>
                                                Numéro de Série <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                name="serial_number"
                                                id="serial_number"
                                                class="form-control @error('serial_number') is-invalid @enderror"
                                                value="{{ old('serial_number') }}"
                                                placeholder="Ex: ABC123456789"
                                                required>
                                            @error('serial_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Identifiant unique de l'équipement</div>
                                        </div>

                                        <!-- Part Number -->
                                        <div class="mb-3">
                                            <label for="part_number" class="form-label">
                                                <i class="fas fa-hashtag text-info me-1"></i>
                                                Part Number <span class="text-muted">(optionnel)</span>
                                            </label>
                                            <input type="text"
                                                name="part_number"
                                                id="part_number"
                                                class="form-control @error('part_number') is-invalid @enderror"
                                                value="{{ old('part_number') }}"
                                                placeholder="Ex: PN-ABC-123">
                                            @error('part_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Référence constructeur du composant</div>
                                        </div>

                                        <!-- Date d'installation -->
                                        <div class="mb-3">
                                            <label for="installation_date" class="form-label">
                                                <i class="fas fa-calendar text-success me-1"></i>
                                                Date d'Installation <span class="text-danger">*</span>
                                            </label>
                                            <input type="date"
                                                name="installation_date"
                                                id="installation_date"
                                                class="form-control @error('installation_date') is-invalid @enderror"
                                                value="{{ old('installation_date') }}"
                                                max="{{ date('Y-m-d') }}"
                                                required>
                                            @error('installation_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Date de fin de garantie -->
                                        <div class="mb-3">
                                            <label for="warranty_end_date" class="form-label">
                                                <i class="fas fa-shield-alt text-warning me-1"></i>
                                                Fin de Garantie <span class="text-muted">(optionnel)</span>
                                            </label>
                                            <input type="date"
                                                name="warranty_end_date"
                                                id="warranty_end_date"
                                                class="form-control @error('warranty_end_date') is-invalid @enderror"
                                                value="{{ old('warranty_end_date') }}">
                                            @error('warranty_end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Date limite de la garantie constructeur</div>
                                        </div>

                                        <!-- Zone de preview des informations -->
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="fas fa-eye text-primary me-1"></i>Aperçu
                                                </h6>
                                                <div id="equipmentPreview" class="text-muted">
                                                    <small>Les informations apparaîtront ici au fur et à mesure de la saisie...</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <hr class="my-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                                <i class="fas fa-times me-1"></i>Annuler
                                            </button>
                                            <div>
                                                <button type="button" class="btn btn-outline-info me-2" onclick="resetForm()">
                                                    <i class="fas fa-undo me-1"></i>Réinitialiser
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i>Enregistrer l'Équipement
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript pour améliorer l'UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mise à jour de l'aperçu en temps réel
            const form = document.getElementById('equipmentForm');
            const preview = document.getElementById('equipmentPreview');

            function updatePreview() {
                const brand = document.getElementById('brand').value;
                const model = document.getElementById('model').value;
                const type = document.getElementById('type').value;
                const serialNumber = document.getElementById('serial_number').value;
                const status = document.getElementById('status').value;
                const agency = document.getElementById('agency_id').selectedOptions[0]?.text || '';

                let previewText = '';

                if (brand || model || type) {
                    previewText += `<strong>Équipement:</strong> ${brand} ${model}<br>`;
                }
                if (type) {
                    previewText += `<strong>Type:</strong> ${type}<br>`;
                }
                if (serialNumber) {
                    previewText += `<strong>N° Série:</strong> ${serialNumber}<br>`;
                }
                if (agency && agency !== '-- Sélectionner une agence --') {
                    previewText += `<strong>Agence:</strong> ${agency}<br>`;
                }
                if (status) {
                    const statusColors = {
                        'actif': 'success',
                        'maintenance': 'warning',
                        'hors_service': 'danger'
                    };
                    previewText += `<strong>Statut:</strong> <span class="badge bg-${statusColors[status]}">${status}</span>`;
                }

                preview.innerHTML = previewText || '<small class="text-muted">Les informations apparaîtront ici au fur et à mesure de la saisie...</small>';
            }

            // Écouter les changements sur tous les champs
            const fields = ['brand', 'model', 'type', 'serial_number', 'status', 'agency_id'];
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', updatePreview);
                    field.addEventListener('change', updatePreview);
                }
            });

            // Validation de la date de garantie
            const installationDate = document.getElementById('installation_date');
            const warrantyDate = document.getElementById('warranty_end_date');

            installationDate.addEventListener('change', function() {
                if (warrantyDate.value && new Date(this.value) > new Date(warrantyDate.value)) {
                    warrantyDate.setCustomValidity('La date de fin de garantie doit être postérieure à la date d\'installation');
                } else {
                    warrantyDate.setCustomValidity('');
                }
            });

            warrantyDate.addEventListener('change', function() {
                if (installationDate.value && new Date(installationDate.value) > new Date(this.value)) {
                    this.setCustomValidity('La date de fin de garantie doit être postérieure à la date d\'installation');
                } else {
                    this.setCustomValidity('');
                }
            });

            // Auto-complétion intelligente pour la marque
            const brandInput = document.getElementById('brand');
            const commonBrands = ['Dell', 'HP', 'Cisco', 'APC', 'Lenovo', 'IBM', 'Juniper', 'Fortinet', 'Palo Alto', 'VMware'];

            brandInput.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                // Logique d'auto-complétion ici si besoin
            });
        });

        function resetForm() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les données saisies seront perdues.')) {
                document.getElementById('equipmentForm').reset();
                document.getElementById('equipmentPreview').innerHTML = '<small class="text-muted">Les informations apparaîtront ici au fur et à mesure de la saisie...</small>';
            }
        }

        // Validation côté client avant soumission
        document.getElementById('equipmentForm').addEventListener('submit', function(e) {
            const requiredFields = ['agency_id', 'type', 'brand', 'model', 'serial_number', 'status', 'installation_date'];
            let isValid = true;

            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
                document.querySelector('.is-invalid').focus();
            }
        });
    </script>

</body>

</html>