<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class RunScheduler
{
    public function handle($request, Closure $next)
    {
        // Exécute la tâche seulement une fois toutes les 60 secondes
        if (!Cache::has('schedule_last_run')) {
            Artisan::call('schedule:run');
            Cache::put('schedule_last_run', now(), 60); // en secondes
        }

        return $next($request);
    }
}
