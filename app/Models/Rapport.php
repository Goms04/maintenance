<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;
 protected $fillable = [
      'intervention_id' ,
          'agence_id',
            'materiel_id',
        'client', 
        'site',
        'materiel',
        'observations',
        'recommandations',
    ];

 public function intervention()
    {
        return $this->belongsTo(Intervention::class);
}

public function agency()
{
    return $this->belongsTo(Agences::class, 'site', 'name');
}


}