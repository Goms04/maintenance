<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intervention</title>
</head>
<body>
    <div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-tools me-2"></i> Détails de l'intervention
            </h4>
        </div>
        <div class="card-body">
           <div class="card-body">
    <div class="mb-3">
        <strong><i class="fas fa-building me-2 text-secondary"></i>Client:</strong>
        <span class="badge bg-info text-dark">
            {{ $intervention->Nom_Agence ?? 'Inconnue' }}
        </span>
    </div>

    <div class="mb-3">
        <strong><i class="fas fa-map-marker-alt me-2 text-secondary"></i>Agence:</strong>
        <span class="badge bg-secondary text-light">
            {{ $intervention->Nom_site ?? 'Inconnu' }}
        </span>
    </div>

    <div class="mb-3">
        <strong><i class="fas fa-user me-2 text-secondary"></i>Nom du technicien:</strong>
        <span class="badge bg-dark text-white">
            {{ $intervention->Nom ?? 'Inconnu' }}
        </span>
    </div>

    <div class="mb-3">
        <strong><i class="fas fa-calendar-alt me-2 text-secondary"></i>Date :</strong>
        <span>
            {{ \Carbon\Carbon::parse($intervention->Date)->format('d/m/Y H:i') }}
        </span>
    </div>

   <!-- <div class="mb-3">
        <strong><i class="fas fa-info-circle me-2 text-secondary"></i>Statut :</strong>
        @php
            $statutClass = [
                'planifiee' => 'primary',
                'en_cours' => 'warning',
                'terminee' => 'success',
                'annulee' => 'danger'
            ][$intervention->statut] ?? 'secondary';
        @endphp
        <span class="badge bg-{{ $statutClass }}">
            {{ ucfirst($intervention->statut) }}
        </span>
    </div>  -->
</div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ url('/liste2') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
</body>
</html>