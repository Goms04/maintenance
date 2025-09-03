<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <title>Modifier une intervention</title>
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Modifier une Intervention
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

                    <form action="/update_traitement" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $interventions->id }}">

                        <!-- Nom du Technicien -->
                        <div class="mb-3">
                            <label for="nom_technicien" class="form-label">
                                <i class="fas fa-user me-1"></i>
                                Nom du Technicien <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('nom_technicien') is-invalid @enderror"
                                   id="nom_technicien"
                                   name="nom_technicien"
                                   value="{{ $interventions->Nom }}"
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
                                   value="{{ \Carbon\Carbon::parse($interventions->Date)->format('Y-m-d\TH:i') }}"
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
                                               {{ $interventions->Type_intervention == 'PREVENTIVE' ? 'checked' : '' }}
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
                                               {{ $interventions->Type_intervention == 'CURATIVE' ? 'checked' : '' }}>
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

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-comment me-1"></i>
                                Description (Optionnel)
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Décrivez brièvement l'intervention...">{{ $interventions->Description }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ url('/liste') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

const clientSelect = document.getElementById('clientSelect');
    const agenceSelect = document.getElementById('agenceSelect');

    clientSelect.addEventListener('change', function () {
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
    </script>
</body>
</html>
 