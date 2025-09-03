<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipements extends Model
{
    use HasFactory;

    protected $table = 'equipements';

    protected $fillable = [
       'agency_id',
        'type',
        'brand',
        'model',
        'serial_number',
        'part_number',
        'installation_date',
        'warranty_end_date',
        'status',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'warranty_end_date' => 'date',
    ];

    public function agency()
    {
        return $this->belongsTo(Agences::class, 'agency_id');
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'equipement_id');
    }

    
    
}
