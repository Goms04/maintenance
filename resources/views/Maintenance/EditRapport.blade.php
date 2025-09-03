<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports d'Intervention</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <script src="{{ asset('CSS/bootstrap.bundle.min.js') }}"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light">
    <div class="container-fluid py-4">

        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title mb-2">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>
                            Rapports d'Intervention
                        </h1>
                        <p class="text-muted mb-0">Gestion des rapports d'intervention technique</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire d'ajout -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle me-2"></i>
                            Modification d'un rapport
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif



         <form action="{{ route('rapport.update', $rapport->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <select class="form-select" id="nom_site" name="site" required>
                                    <option value="">Sélectionnez une agence</option>
                                    @foreach($agences as $agency)
                                    <option value="{{ $agency->id }}">
                                        {{ $agency->name }} ({{ optional($agency->client)->name ?? 'Client inconnu' }})
                                    </option>
                                    @endforeach
                                </select>
                                <div class="col-md-6 mb-3">
                                    <label for="materiel_id" class="form-label">
                                        Sélectionner un équipement existant <span class="text-danger">*</span>
                                    </label>
                                    <select name="materiel_id" id="materiel_id" class="form-select" value="materiel" required>
                                        <option value="">-- Choisir un équipement --</option>
                                       @foreach($equipements as $equipement)
                                            <option value="{{ $equipement->id }}">
                                                {{ $equipement->type }} - {{ $equipement->brand }} - {{ $equipement->model }}
                                            </option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <label>Observation</label>
                            <textarea name="observation" class="form-control" required>{{ $rapport->observations }}</textarea>

                            <label>Recommandation</label>
                            <textarea name="recommandation" class="form-control">{{ $rapport->recommandations }}</textarea>

                            <button type="submit" class="btn btn-primary mt-3">Modifier</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Script JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.btn-nouveau-rapport').forEach(btn => {
                btn.addEventListener('click', function() {
                    const interventionId = this.getAttribute('data-id');
                    document.getElementById('intervention_id').value = interventionId;
                    // Scroll vers le formulaire automatiquement
                    document.getElementById('rapport-form').scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>

</html>