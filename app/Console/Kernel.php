<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Intervention;
use App\Models\Utilisateur;
use App\Notifications\AlerteMaintenanceTechnicien;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Support\Facades\Log;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
   protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        try {
            //5 jours avant la date d'intervention 

            $cibleDate = now()->addDays(5)->toDateString();
            Log::info("Recherche d'intervention prévue le $cibleDate");

            $interventions = Intervention::whereDate('Date', $cibleDate)
                ->where('alerte_envoyee', false)
                ->get(); 

                // intervention entre demain et les 7 jours a venir
              /*  $start = now()->addDays(1)->startOfDay(); // demain
$end = now()->addDays(15)->endOfDay();     // dans 7 jours

$interventions = Intervention::whereBetween('Date', [$start, $end])
    ->where('alerte_envoyee', false)
    ->get();*/


            Log::info("Nombre d'interventions trouvées : " . $interventions->count());

            foreach ($interventions as $intervention) {
                $technicien = Utilisateur::where('last_name', $intervention->Nom)->first();

                if ($technicien && $technicien->email) {
                    Log::info("Envoi du mail à : " . $technicien->email);
                    $technicien->notify(new AlerteMaintenanceTechnicien($intervention));
                    $intervention->update(['alerte_envoyee' => true]);
                } else {
                    Log::warning("Technicien non trouvé ou email manquant pour : " . $intervention->Nom);
                }
            }

        } catch (\Exception $e) {
            Log::error('Erreur tâche planifiée : ' . $e->getMessage());
        }
    })->dailyAt('07:00');
}

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
