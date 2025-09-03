<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'category',
        'description',
    ];

    public function agences()
    {
        return $this->hasMany(Agences::class, 'client_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($client) {
            // Supprime toutes les agences liées à ce client
            $client->agences()->each(function ($agence) {
                $agence->delete();
            });
        });
    }

   public function equipment()
{
    return $this->hasManyThrough(
        Equipements::class,    // modèle final
        Agences::class,        // modèle intermédiaire
        'client_id',           // clé étrangère dans la table agences
        'agency_id',           // clé étrangère dans la table equipements
        'id',                  // clé locale (clients.id)
        'id'                   // clé locale (agences.id)
    );
}


}

    




