<?php

namespace App\Exports;

use App\Models\Rapport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TousRapportsExport implements FromView
{
    public function view(): View
    {
        return view('exports.rapport_excel', [
            'rapports' => Rapport::all()
        ]);
    }
}