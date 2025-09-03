<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <title>Nos clients</title>
</head>

<body>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-{{ isset($service) ? 'edit' : 'plus' }} me-2"></i>
                                {{ isset($service) ? 'Modifier le service' : 'Modifier le client' }}
                            </h4>
                            <a href="{{ route('services.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation :</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form action="{{ route('updateclient', $clients->id) }}"
                            method="POST" id="serviceForm">
                            @csrf
                            @if(isset($service))
                            @method('PUT')
                            @endif
           <input type="hidden" name="id" value="{{ $clients->id }}">
                            <!-- Nom du service -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fas fa-tag text-primary me-2"></i>Nom du client
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ $clients->name }}"
                                    placeholder="Ex: Support technique, Maintenance..."
                                    required>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Saisissez un nom descriptif pour votre client
                                </div>
                            </div>

                            <!-- Catégorie -->
                            <div class="mb-4">
                                <label for="category" class="form-label fw-bold">
                                    <i class="fas fa-folder text-warning me-2"></i>Catégorie
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('category') is-invalid @enderror"
                                    id="category"
                                    name="category"
                                    value="{{ $clients->category }}"
                                    required>
                                    <option value="">-- Sélectionnez une catégorie pour votre client --</option>
                                    <option value="Support" {{ old('category', $service->category ?? '') == 'Support' ? 'selected' : '' }}>
                                        Support
                                    </option>
                                    <option value="Maintenance" {{ old('category', $service->category ?? '') == 'Maintenance' ? 'selected' : '' }}>
                                        Maintenance
                                    </option>
                                    <option value="Installation" {{ old('category', $service->category ?? '') == 'Installation' ? 'selected' : '' }}>
                                        Installation
                                    </option>
                                    <option value="Formation" {{ old('category', $service->category ?? '') == 'Formation' ? 'selected' : '' }}>
                                        Formation
                                    </option>
                                    <option value="Consultation" {{ old('category', $service->category ?? '') == 'Consultation' ? 'selected' : '' }}>
                                        Consultation
                                    </option>
                                    <option value="Réparation" {{ old('category', $service->category ?? '') == 'Réparation' ? 'selected' : '' }}>
                                        Réparation
                                    </option>
                                    <option value="Autre" {{ old('category', $service->category ?? '') == 'Autre' ? 'selected' : '' }}>
                                        Autre
                                    </option>
                                </select>
                                @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">
                                    <i class="fas fa-align-left text-info me-2"></i>Description
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description"
                                    name="description"
                                    value="{{ $clients->description }}"
                                    rows="4"
                                    placeholder="Décrivez en détail ce service, ses objectifs et son périmètre d'intervention...">{{ old('description', $service->description ?? '') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <div class="form-text">
                                    <span id="charCount">0</span>/500 caractères
                                </div>
                            </div>

                            <!-- Informations supplémentaires si modification -->
                            @if(isset($service))
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="text-success">{{ $service->agences->count() }}</h5>
                                            <small class="text-muted">
                                                <i class="fas fa-building me-1"></i>Agences liées
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="text-warning">{{ $service->equipment->count() }}</h5>
                                            <small class="text-muted">
                                                <i class="fas fa-tools me-1"></i>Équipements associés
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!-- Boutons d'action -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('services.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>

                                <div>
                                    @if(isset($service))
                                    <button type="button" class="btn btn-outline-danger me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <i class="fas fa-trash me-2"></i>Supprimer
                                    </button>
                                    @endif

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        {{ isset($service) ? 'Mettre à jour' : 'Mettre à jour le client' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Conseils et aide -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-lightbulb text-warning me-2"></i>Conseils pour bien remplir ce formulaire
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-primary">Nom du service</h6>
                                <small class="text-muted">
                                    Choisissez un nom clair et précis qui décrit l'activité principale du service.
                                </small>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-warning">Catégorie</h6>
                                <small class="text-muted">
                                    Sélectionnez la catégorie qui correspond le mieux au type d'intervention.
                                </small>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-info">Description</h6>
                                <small class="text-muted">
                                    Détaillez les prestations, les objectifs et le périmètre d'action.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression (si modification) -->
    @if(isset($service))
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Êtes-vous sûr de vouloir supprimer définitivement le service <strong>{{ $service->name }}</strong> ?</p>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Attention :</h6>
                        <ul class="mb-0">
                            <li>Cette action est irréversible</li>
                            <li>{{ $service->agences->count() }} agence(s) seront dissociées</li>
                            <li>{{ $service->equipment->count() }} équipement(s) ne seront plus liés</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Compteur de caractères pour la description
            const descriptionField = document.getElementById('description');
            const charCountElement = document.getElementById('charCount');

            function updateCharCount() {
                const currentLength = descriptionField.value.length;
                charCountElement.textContent = currentLength;

                if (currentLength > 400) {
                    charCountElement.classList.add('text-warning');
                } else if (currentLength > 450) {
                    charCountElement.classList.remove('text-warning');
                    charCountElement.classList.add('text-danger');
                } else {
                    charCountElement.classList.remove('text-warning', 'text-danger');
                }
            }

            descriptionField.addEventListener('input', updateCharCount);
            updateCharCount(); // Initial count

            // Validation du formulaire
            document.getElementById('serviceForm').addEventListener('submit', function(e) {
                const name = document.getElementById('name').value.trim();
                const category = document.getElementById('category').value;

                if (!name || !category) {
                    e.preventDefault();

                    let errorMessage = 'Veuillez remplir les champs obligatoires :\n';
                    if (!name) errorMessage += '- Nom du service\n';
                    if (!category) errorMessage += '- Catégorie\n';

                    alert(errorMessage);
                    return false;
                }

                // Confirmation pour les modifications importantes
                (isset($service))
                if (!confirm('Êtes-vous sûr de vouloir modifier ce service ?')) {
                    e.preventDefault();
                    return false;
                }
          
            });

            // Auto-focus sur le premier champ
            document.getElementById('name').focus();
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .form-label {
            font-size: 0.95rem;
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .invalid-feedback {
            font-size: 0.85rem;
        }

        .form-text {
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }

            .card-header h4 {
                font-size: 1.1rem;
            }
        }
    </style>
    @endpush
  
</body>

</html>