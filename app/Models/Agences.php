<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agences extends Model
{
    use HasFactory;

    protected $table = 'agences';

    protected $fillable = [
        'client_id',
        'name',
        'address',
        'contact_person',
        'phone',
    ];

    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }

    public function equipment()
    {
        return $this->hasMany(Equipements::class, 'agency_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($agence) {
            // Supprime tous les équipements liés à cette agence
            $agence->equipment()->each(function ($equipement) {
                $equipement->delete();
            });
        });
    }

  public function interventions()
    {
        return $this->hasMany(Intervention::class, 'agence_id');
    }

}


