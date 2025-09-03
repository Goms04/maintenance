<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;
        protected $fillable = [
        'nom_technicien',
        'date_intervention',
        'nom_agence',
         'nom_site',
         'type_intervention',
         'description',
         'est_effectuee'=> 0,
         'statut',
         'equipements_id',
         'alerte_envoyee'

           
    ]; 
 public function equipment()
    {
        return $this->belongsTo(Equipements::class);
    }
public function agence()
    {
        return $this->belongsTo(Agences::class, 'agence_id');
    }

protected $casts = [
    'est_effectuee' => 'boolean',
];

public function client()
{
    return $this->belongsTo(Clients::class, 'Nom_Agence', 'id'); 
    // 'Nom_Agence' est le champ de l'intervention qui contient l'ID du client
    // 'id' est la clé primaire dans la table clients
}



public function updateStatutAutomatiquement()
{
    $now = Carbon::now(); 
    $dateIntervention = Carbon::parse($this->date); // ou $this->date_intervention

    if ($now->lessThan($dateIntervention)) {
        $this->setAttribute('statut', 'a_venir');
    } else {
        if (is_null($this->est_effectuee)) {
            $this->setAttribute('statut', 'en_cours');
        } elseif ($this->est_effectuee === true) {
            $this->setAttribute('statut', 'terminee');
        } else {
            $this->setAttribute('statut', 'non_effectuee');
        }
    }
}



protected static function booted()
{
    static::saving(function ($intervention) {
        $intervention->updateStatutAutomatiquement();
    });
}

public function equipement()
{
    return $this->belongsTo(Equipements::class, 'equipement_id');
}


}
