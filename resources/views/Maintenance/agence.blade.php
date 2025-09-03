<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agence</title>
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
        <div class="col-md-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-building text-primary me-2"></i>
                    Ajouter une Nouvelle Agence
                </h2>
                <a href="/listeag" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste des agences
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

            <!-- Message de succès (si redirection après création) -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Formulaire principal -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations de l'Agence
                    </h5>
                </div>

                <div class="card-body">
                    <form action="/agence_traitement" method="POST" id="agencyForm">
                        @csrf
                        
                        <!-- Client associé -->
                        <div class="mb-4">
                            <label for="client_id" class="form-label">
                                <i class="fas fa-user-tie text-info me-1"></i>
                                Client <span class="text-danger">*</span>
                            </label>
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner un client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                      
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-lightbulb text-warning"></i>
                                Sélectionnez le client propriétaire de cette agence
                            </div>
                        </div>

                        <div class="row">
                            <!-- Colonne gauche -->
                            <div class="col-md-6">
                                <!-- Nom de l'agence -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-building text-primary me-1"></i>
                                        Nom de l'Agence <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" 
                                           placeholder="Ex: Agence Centrale, Succursale Nord..."
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Nom d'identification de l'agence</div>
                                </div>

                                <!-- Personne de contact -->
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">
                                        <i class="fas fa-user text-success me-1"></i>
                                        Personne de Contact <span class="text-muted">(optionnel)</span>
                                    </label>
                                    <input type="text" 
                                           name="contact_person" 
                                           id="contact_person" 
                                           class="form-control @error('contact_person') is-invalid @enderror" 
                                           value="{{ old('contact_person') }}" 
                                           placeholder="Ex: Jean Dupont, Marie Martin...">
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Responsable ou contact principal de l'agence</div>
                                </div>

                                <!-- Téléphone -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone text-warning me-1"></i>
                                        Téléphone <span class="text-muted">(optionnel)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone-alt"></i>
                                        </span>
                                        <input type="tel" 
                                               name="phone" 
                                               id="phone" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone') }}" 
                                               placeholder="Ex: +228 XX XX XX XX"
                                               pattern="[+]?[0-9\s\-\(\)]+">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Numéro de téléphone principal de l'agence</div>
                                </div>
                            </div>

                            <!-- Colonne droite -->
                            <div class="col-md-6">
                                <!-- Adresse -->
                                <div class="mb-3">
                                    <label for="address" class="form-label">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        Adresse Complète <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="address" 
                                              id="address" 
                                              rows="4" 
                                              class="form-control @error('address') is-invalid @enderror" 
                                              placeholder="Adresse complète de l'agence...&#10;Rue, Avenue, Boulevard&#10;Quartier, Ville&#10;Code postal (si applicable)"
                                              required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Adresse physique complète pour les interventions
                                    </div>
                                </div>

                                <!-- Zone d'aperçu -->
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted">
                                            <i class="fas fa-eye text-primary me-1"></i>Aperçu de l'Agence
                                        </h6>
                                        <div id="agencyPreview" class="text-muted">
                                            <small>Les informations apparaîtront ici au fur et à mesure...</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section informations supplémentaires -->
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-cogs me-1"></i>Informations Complémentaires
                                </h6>
                                
                                <div class="row d-flex justify-content-between">
                                    <div class="col-md-4">
                                        <div class="card border-primary">
                                            <div class="card-body text-center">
                                                <i class="fas fa-server fa-2x text-primary mb-2"></i>
                                                <h6>Équipements</h6>
                                                <small class="text-muted">Seront ajoutés après création</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border-success">
                                            <div class="card-body text-center">
                                                <i class="fas fa-tools fa-2x text-success mb-2"></i>
                                                <h6>Maintenances</h6>
                                                <small class="text-muted">Planning automatique</small>
                                            </div>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="fas fa-times me-1"></i>Annuler
                            </button>
                            
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-info" onclick="resetForm()">
                                    <i class="fas fa-undo me-1"></i>Réinitialiser
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i>Créer l'Agence
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Aide contextuelle -->
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle me-1"></i>Aide
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-info">Champs obligatoires :</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-1"></i> Client propriétaire</li>
                                <li><i class="fas fa-check text-success me-1"></i> Nom de l'agence</li>
                                <li><i class="fas fa-check text-success me-1"></i> Adresse complète</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">Conseils :</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-1"></i> Utilisez des noms d'agence explicites</li>
                                <li><i class="fas fa-lightbulb text-warning me-1"></i> L'adresse doit être précise pour les interventions</li>
                                <li><i class="fas fa-lightbulb text-warning me-1"></i> Le contact facilite la communication</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- JavaScript pour l'interactivité -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('agencyForm');
    const preview = document.getElementById('agencyPreview');
    
    // Mise à jour de l'aperçu en temps réel
    function updatePreview() {
        const clientSelect = document.getElementById('client_id');
        const clientText = clientSelect.selectedOptions[0]?.text || '';
        const name = document.getElementById('name').value;
        const address = document.getElementById('address').value;
        const contact = document.getElementById('contact_person').value;
        const phone = document.getElementById('phone').value;
        
        let previewHTML = '';
        
        if (name) {
            previewHTML += `<div class="mb-2">
                <i class="fas fa-building text-primary me-1"></i>
                <strong>${name}</strong>
            </div>`;
        }
        
        if (clientText && clientText !== '-- Sélectionner un client --') {
            previewHTML += `<div class="mb-2">
                <i class="fas fa-user-tie text-info me-1"></i>
                <small>Client: ${clientText.replace(/^\s*\S+\s*/, '')}</small>
            </div>`;
        }
        
        if (address) {
            previewHTML += `<div class="mb-2">
                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                <small>${address.substring(0, 60)}${address.length > 60 ? '...' : ''}</small>
            </div>`;
        }
        
        if (contact) {
            previewHTML += `<div class="mb-1">
                <i class="fas fa-user text-success me-1"></i>
                <small>Contact: ${contact}</small>
            </div>`;
        }
        
        if (phone) {
            previewHTML += `<div class="mb-1">
                <i class="fas fa-phone text-warning me-1"></i>
                <small>${phone}</small>
            </div>`;
        }
        
        preview.innerHTML = previewHTML || '<small class="text-muted">Les informations apparaîtront ici au fur et à mesure...</small>';
    }
    
    // Écouter les changements sur tous les champs
    ['client_id', 'name', 'address', 'contact_person', 'phone'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', updatePreview);
            field.addEventListener('change', updatePreview);
        }
    });
    
    // Validation du téléphone
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
        const phonePattern = /^[+]?[0-9\s\-\(\)]+$/;
        if (this.value && !phonePattern.test(this.value)) {
            this.setCustomValidity('Format de téléphone invalide');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Auto-resize du textarea
    const addressTextarea = document.getElementById('address');
    addressTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Animation du bouton de soumission
    form.addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Création en cours...';
        submitBtn.disabled = true;
    });
});

// Fonction pour réinitialiser le formulaire
function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les données saisies seront perdues.')) {
        document.getElementById('agencyForm').reset();
        document.getElementById('agencyPreview').innerHTML = '<small class="text-muted">Les informations apparaîtront ici au fur et à mesure...</small>';
        
        // Réinitialiser la hauteur du textarea
        document.getElementById('address').style.height = 'auto';
    }
}

// Validation côté client
document.getElementById('agencyForm').addEventListener('submit', function(e) {
    const requiredFields = ['client_id', 'name', 'address'];
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
        
        // Scroll vers le premier champ invalide
        const firstInvalid = document.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        
        // Afficher une alerte
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning alert-dismissible fade show mt-3';
        alertDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            Veuillez remplir tous les champs obligatoires marqués d'un astérisque (*).
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const form = document.getElementById('agencyForm');
        form.insertBefore(alertDiv, form.firstChild);
        
        // Réactiver le bouton
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Créer l\'Agence';
        submitBtn.disabled = false;
    }
});
</script>


</body>
</html>