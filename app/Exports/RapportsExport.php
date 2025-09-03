<?php

namespace App\Exports;

use App\Models\Rapport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RapportsExport implements FromView
{
    protected $interventionId;

    public function __construct($interventionId)
    {
        $this->interventionId = $interventionId;
    }

    public function view(): View
    {
        return view('exports.rapport_excel', [
            'rapports' => Rapport::where('intervention_id', $this->interventionId)->get()
        ]);
    }
}

?>

