<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Maintenance;
use App\Imports\RapportsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RapportsExport;
use Illuminate\Support\Facades\Session;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//pour l'authentification 
//affichage de la page d'inscription
Route::get('/insc',[Maintenance::class,'Inscription']);
//affichage de la page de connexion
Route::get('/conn', [Maintenance::class, 'Connexion']);
//traitement de la page d'inscription
 Route::post('/traitement inscription',[Maintenance::class,'Inscription_traitement']);
 //traitement connexion
 Route::post('/traitement connexion', [Maintenance::class, 'traitement_connexion']);
 
 //pour la deconnexion
 Route::get('/logout', function () {
    // Déconnexion de l'utilisateur Auth (technicien)
    Auth::logout();

    // Suppression manuelle de la session admin si elle existe
    if (Session::has('utilisateur')) {
        Session::forget('utilisateur');
    }

    return redirect('/page')->with('success', 'Déconnecté');
});

// la page principale qui ressemble tout
Route::get('/page',[Maintenance::class,'Page']);
Route::get('/', function () {
    return redirect('/page');
});







 Route::middleware(['customauth'])->group(function () {
   
//pour le changement de mot de passe
Route::get('/changer-mot-de-passe', [Maintenance::class, 'edit'])->name('password.edit');
Route::post('/changer-mot-de-passe', [Maintenance::class, 'update_motdepasse'])->name('password.update');




//Tout ce qui concerne intervention
Route::get('/inter',[Maintenance::class,'Intervention']);
Route::post('/intervention_traitement',[Maintenance::class,'Intervention_traitement']);
Route::get('/liste',[Maintenance::class,'Liste_intervention']);
Route::get('/update/{id}',[Maintenance::class,'Update']);
Route::post('/update_traitement',[Maintenance::class,'Update_traitement']);
Route::delete('/interventions/{id}', [Maintenance::class, 'destroy'])->name('interventions.destroy');
Route::patch('/interventions/{id}/marquer-effectuee', [Maintenance::class, 'marquerEffectuee']);


// Formulaire de création de rapport pour une intervention
Route::get('/rap',[Maintenance::class,'Rapport']);
// Sauvegarde du rapport en base
Route::post('/Rapport_traitement',[Maintenance::class,'Rapport_traitement']);
Route::get('/liste2',[Maintenance::class,'Liste_Rapport']);
// Affichage de tous les rapports d'une intervention
Route::get('/rapports/{intervention}', [Maintenance::class, 'show'])
     ->name('rapports.show');
     //Affichage des intervention d'un rapport
     Route::get('/liste/{id}', [Maintenance::class, 'Voir'])
     ->name('intervention.show');

//importer de la base de donnee en excel d'un rapport correspondant en un id de l'intervention
Route::get('/rapports/export/{intervention_id}', [Maintenance::class, 'exportRapport'])->name('rapports.export');
//importer tout les rapport en excel
Route::get('/rapport', [Maintenance::class, 'exportTousRapports'])->name('rapports.tous_export');
//suppression d'un rapport
Route::delete('/rapport/delete/{id}', [Maintenance::class, 'deleteRapport'])->name('rapport.delete');
// pour modification d'un rapport
Route::get('/rapport/edit/{id}', [Maintenance::class, 'editRapport'])->name('rapport.edit');
Route::put('/rapport/update/{id}', [Maintenance::class, 'updateRapport'])->name('rapport.update');



 // formulaires des equipements CRUD
 Route::get('/equip', [Maintenance::class, 'Equipement']);
  Route::get('/listeequip', [Maintenance::class, 'Liste_Equipement']);
    Route::get('/supprimer/{id}', [Maintenance::class, 'Supprimer_Equipement']);
Route::post('/equipement_traitement', [Maintenance::class, 'Equipement_traitement']);
    Route::get('/modifier/{id}', [Maintenance::class, 'Modifier_Equipement']);
Route::post('/equipement/update/{id}', [Maintenance::class, 'Equipement_traitement_update'])->name('equipement.update');

 // formulaires des agences CRUD
 Route::get('/agence', [Maintenance::class, 'Agence']);
Route::post('/agence_traitement', [Maintenance::class, 'Agence_traitement']);
 Route::get('/listeag', [Maintenance::class, 'Liste_agence']);
 Route::delete('/agence/{id}/supprimer', [Maintenance::class, 'supprimerAgence'])->name('agences.destroy');
 Route::get('/agence/{id}/edit', [Maintenance::class, 'editAgence'])->name('agences.edit');
 Route::put('/agences/{id}/update', [Maintenance::class, 'updateAgence'])->name('agence.update');

 //tout ce qui concerne clientn CRUD
 Route::resource('services',Maintenance::class,);
Route::get('/liste_client', [Maintenance::class, 'Liste_client']);
Route::delete('/services/{id}/supprimer', [Maintenance::class, 'supprimerService'])->name('suppression');
Route::get('/editclient/{id}', [Maintenance::class, 'edite_client'])->name('editclient');
Route::post('/editclient/{id}', [Maintenance::class, 'updatee'])->name('updateclient');

});






