<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <title>Modification d'un equipement</title>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
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
                        <form action="{{ route('equipement.update', $equipements->id) }}" method="POST" id="equipmentForm">
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
                                        <select name="agency_id" id="agency_id" class="form-select @error('agency_id') is-invalid @enderror" required>
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
                                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" value=" {{ $equipements->type}} " required>
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
                                            value=" {{ $equipements->brand}} "
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
                                            value=" {{ $equipements->model}} "
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
                                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" value=" {{ $equipements->status}} " required>
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
                                            value=" {{ $equipements->serial_number}} "
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
                                            value=" {{ $equipements->part_number}} "
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
                                            value=" {{ $equipements->installation_date}} "
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
                                               value=" {{ $equipements->warranty_end_date}} "
                                            class="form-control @error('warranty_end_date') is-invalid @enderror"
                                            value="{{ old('warranty_end_date') }}">
                                        @error('warranty_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Date limite de la garantie constructeur</div>
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
                                                <i class="fas fa-save me-1"></i>Modifier l'Équipement
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
</body>

</html>