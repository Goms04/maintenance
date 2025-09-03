<?php

namespace App\Imports;

use App\Models\Rapport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class RapportsImport implements ToModel , WithHeadingRow

{
    /**
     * @param array $row
     * @return Rapport|null
     */
   public function model(array $row)
{
    return new Rapport([
        'intervention_id' => $row['N°'], // ici on lit la colonne "numero"
        'site'            => $row['SITES'],
        'materiel'        => $row['DESIGNATION DU MATERIEL'],
        'observations'    => $row['OBSERVATIONS'],
        'recommandations' => $row['RECOMMANDATIONS'],
    ]);
}
}

