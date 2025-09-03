<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Imports\RapportsImport;
use App\Exports\RapportsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TousRapportsExport;
use App\Models\Clients;
use App\Models\Equipements;
use App\Models\Agences;
use App\Models\intervention;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    //la page qui regroupe tout
    public function page()
    {
         $totalClients = Clients::count();
        $totalAgences = Agences::count();
        $totalEquipements = Equipements::count();

        // Statistiques par client
        $clients = Clients::withCount('agences')->withCount('equipment')->get();

        return view('Maintenance.page1', compact(
            'totalClients', 
            'totalAgences', 
            'totalEquipements', 
            'clients'
        ));
      
    }

    //tout ce qui concerne intervention CRUD
    public function Intervention()
    {
        $clients = Clients::with('agences')->get(); // chaque client contient ses agences
        return view('Maintenance.intervention', compact('clients'));
    }



    public function Intervention_traitement(request $request)
    {
        $request->validate([
            'nom',
            'date_intervention',
            'client_id' => 'required|exists:clients,id',
            'agence_id' => 'required|exists:agences,id',
            'type_intervention',
            'description',
        ]);

        $client = Clients::find($request->client_id);
        $agence = Agences::find($request->agence_id);

        if (!$client || !$agence) {
            return back()->withErrors(['client_id' => 'Client ou agence introuvable.']);
        }


        $intervention = new intervention();
        $intervention->Nom = $request->input('nom_technicien');
        $intervention->Date = $request->input('date_intervention');
        $intervention->Nom_Agence = $client->name;
        $intervention->Nom_site = $agence->name;

        $intervention->Type_intervention = $request->input('type_intervention');
        $intervention->Description = $request->input('description');
        $intervention->save();
        return redirect('/liste')->with('status', 'Intervention creer avec success');
    }

   public function Liste_intervention(Request $request)
{
    $query = Intervention::query();

    // Filtrage agence
   
    if ($request->filled('agence')) {
    $query->where('Nom_Agence', $request->agence);
    }

    // Filtrage type
    if ($request->filled('type')) {
        $query->where('Type_intervention', $request->type);
    }

    // Filtrage statut (à venir, terminée, non effectuée)
    if ($request->filled('statut')) {
        $query->where(function ($q) use ($request) {
            $now = \Carbon\Carbon::now();

            if ($request->statut === 'a_venir') {
                $q->where('Date', '>', $now);
            } elseif ($request->statut === 'terminee') {
                $q->where('est_effectuee', true);
            } elseif ($request->statut === 'non_effectuee') {
                $q->where('est_effectuee', false);
            }
        });
    }

    // Filtrage réalisation
    if ($request->filled('realisation')) {
        if ($request->realisation === 'effectuee') {
            $query->where('est_effectuee', true);
        } elseif ($request->realisation === 'non_effectuee') {
            $query->where('est_effectuee', false);
        }
    }

    // Résultat final
    $interventions = $query->orderByDesc('Date')->get();

    return view('Maintenance.Liste_intervention', compact('interventions'));
}

    

    public function Update($id)
    {
        $interventions = Intervention::find($id);
        $clients = Clients::with('agences')->get(); // chargement des agences liées

        if (!$interventions) {
            return redirect()->back()->with('error', 'Intervention non trouvée.');
        }

        return view('Maintenance.update', compact('interventions', 'clients'));
    }


    public function Update_traitement(Request $request)
    {
        // Validation correcte avec clés
        $request->validate([
            // 'id' => 'required|exists:interventions,id',
            'nom_technicien' => 'required|string',
            'date_intervention' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'agence_id' => 'required|exists:agences,id',

            'type_intervention' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $client = Clients::find($request->client_id);
        $agence = Agences::find($request->agence_id);

        if (!$client || !$agence) {
            return back()->withErrors(['client_id' => 'Client ou agence introuvable.']);
        }


        // Récupère l'intervention ou échoue automatiquement
        $intervention = Intervention::find($request->id);

        if (!$intervention) {
            return redirect()->back()->with('error', 'Intervention introuvable.');
        }


        // Mise à jour des champs
        $intervention->Nom = $request->nom_technicien;
        $intervention->Date = $request->date_intervention;
        $intervention->Nom_Agence = $client->name;
        $intervention->Nom_site = $agence->name;

        $intervention->Type_intervention = $request->type_intervention;
        $intervention->Description = $request->description;
        $intervention->update();

        return redirect('/liste')->with('status', 'Intervention modifiée avec succès');
    }

    public function destroy($id)
    {
        $intervention = intervention::find($id);

        if (!$intervention) {
            return redirect('/liste')->with('error', 'Intervention introuvable.');
        }

        $intervention->delete();

        return redirect('/liste')->with('status', 'Intervention supprimée avec succès.');
    }



    public function marquerEffectuee(Request $request, $id)
    {
        try {
            $intervention = Intervention::findOrFail($id);
            $intervention->est_effectuee = $request->input('est_effectuee');
            $intervention->save();

            $intervention->updateStatutAutomatiquement();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }


    public function show($intervention_id)
    {
        $rapports = Rapport::where('intervention_id', $intervention_id)->get();
        return view('Maintenance.liste', compact('rapports'));
    }



    //tout ce qui concerne RAPPORT CRUD

public function Rapport(Request $request)
{
  

$interventionId = $request->query('intervention_id');

// Récupérer l'intervention avec son client
$intervention = Intervention::with('client')->findOrFail($interventionId);

// Trouver l'agence via le nom stocké dans Nom_site
$agence = Agences::where('name', $intervention->Nom_site)->first();
$agenceId = $agence->id ?? null;

// Client lié à l'intervention
$client = Clients::where('name', $intervention->Nom_Agence)->first();


// Charger uniquement cette agence
$agences = collect();
if ($agence) {
    $agences->push($agence);
}

// Charger uniquement les équipements liés à cette agence
$equipements = $agenceId 
    ? Equipements::where('agency_id', $agenceId)->get()
    : collect();

return view('Maintenance.rapport', compact(
    'interventionId',
    'client',
      'agence',
    'agenceId',
    'agences',
    'equipements',
    'intervention'
));
}

    public function Rapport_traitement(Request $request)
    {
        $request->validate([
            'intervention_id' => 'required|exists:interventions,id',
            'agence_id' => 'required|exists:agences,id',
            'materiel_id' => 'required|exists:equipements,id', // on valide l'ID
            'observation' => 'required|string',
            'recommandation' => 'nullable|string',
        ]);

        // Récupère l'équipement sélectionné
        $equipement = Equipements::findOrFail($request->materiel_id);

        // Construit une description texte de l'équipement
        $descriptionMateriel = $equipement->type . ' - ' . $equipement->brand . ' - ' . $equipement->model;

        // Création du rapport
        $rapport = new Rapport();
        $rapport->intervention_id = $request->input('intervention_id');
   $rapport->site = Agences::find($request->agence_id)->name ?? 'Inconnu';
$rapport->client = optional(Agences::find($request->agence_id)->client)->name ?? 'Inconnu';
        $rapport->materiel = $descriptionMateriel; // enregistre comme texte libre
        $rapport->observations = $request->input('observation');
        $rapport->recommandations = $request->input('recommandation');
        $rapport->save();

        return redirect('/liste2')->with('status', 'Rapport créé avec succès');
    }

public function Voir($id)
{
    $intervention = Intervention::with(['agence', 'equipement'])->findOrFail($id);
    return view('Maintenance.voir', compact('intervention'));
}

    public function Liste_Rapport()
    {
        $rapports = Rapport::with('agency.client')->get();


        return view('Maintenance.liste', compact('rapports'));
    }



    //POUR telecharger un rapport en fichier excel
    public function exportRapport($intervention_id)
    {
        return Excel::download(new RapportsExport($intervention_id), 'rapport_intervention_' . $intervention_id . '.xlsx');
    }

    public function exportTousRapports()
    {
        return Excel::download(new TousRapportsExport, 'tous_rapports.xlsx');
    }

    public function deleteRapport($id)
    {
        $rapport = Rapport::find($id);

        if (!$rapport) {
            return redirect()->back()->with('error', 'Rapport introuvable.');
        }

        $rapport->delete();

        return redirect()->back()->with('success', 'Rapport supprimé avec succès.');
    }

    public function editRapport($id, Request $request)
    {
        $interventionId = $request->query('intervention_id');
        $agences = Agences::all();
        $equipements = Equipements::all();
        $rapport = Rapport::findOrFail($id);
        return view('Maintenance.EditRapport', compact('interventionId', 'agences', 'equipements', 'rapport'));
    }

    public function updateRapport(Request $request, $id)
    {
        $request->validate([
            'site' => 'required|exists:agences,id', // <- ici on attend un ID d'agence
            'materiel_id' => 'required|exists:equipements,id',
            'observation' => 'required|string',
            'recommandation' => 'nullable|string',
        ]);

        $rapport = Rapport::findOrFail($id);

        // ✅ Récupération du nom de l'agence depuis son ID
        $agency = Agences::findOrFail($request->site);
        $rapport->site = $agency->name; // ← Enregistrer le NOM, pas l'ID

        $equipement = Equipements::findOrFail($request->materiel_id);
        $descriptionMateriel = $equipement->type . ' - ' . $equipement->brand . ' - ' . $equipement->model;

        $rapport->materiel = $descriptionMateriel;
        $rapport->observations = $request->input('observation');
        $rapport->recommandations = $request->input('recommandation');
        $rapport->save();

        return redirect('/liste2')->with('success', 'Rapport modifié avec succès.');
    }



    // pour l'authentification 
    public function Inscription()
    {
        return view('Maintenance.inscription');
    }

    public function Inscription_traitement(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:utilisateurs,email',
            'phone' => 'required|digits_between:8,15|numeric',
            'password' => 'required|confirmed|min:8',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'terms' => 'accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Création de l'utilisateur
        $utilisateur = new Utilisateur();
        $utilisateur->first_name = $request->first_name;
        $utilisateur->last_name = $request->last_name;
        $utilisateur->email = $request->email;
        $utilisateur->phone = $request->phone;
        $utilisateur->password = Hash::make($request->password);
        $utilisateur->birth_date = $request->birth_date;
        $utilisateur->gender = $request->gender;
        $utilisateur->terms = $request->has('terms');


        $utilisateur->save();

        // Redirection avec succès
        return redirect('/page')->with('success', 'Inscription réussie !');
    }

    public function Connexion()
    {
        return view('Maintenance.connexion');
    }

    public function traitement_connexion(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required'], //  Assure-toi que le champ rôle est présent dans le formulaire
        ]);

        $selectedRole = $credentials['role']; // 'admin' ou 'technicien'

        // Vérification de l'admin statique
        $adminEmail = 'admin@admin.com';
        $adminPassword = 'admin123';

        if ($credentials['email'] === $adminEmail && $credentials['password'] === $adminPassword) {
            if ($selectedRole !== 'admin') {
                return back()->withErrors([
                    'email' => 'Rôle incorrect pour cet utilisateur.',
                ])->withInput();
            }

            // Créer un "faux" utilisateur admin dans la session
            $admin = new \stdClass();
            $admin->id = 0;
            $admin->name = 'Administrateur';
            $admin->email = $adminEmail;
            $admin->role = 'admin';

            session(['utilisateur' => $admin]);

            return redirect('/page')->with('success', 'Bienvenue administrateur');
        }

        // Vérification pour les utilisateurs dans la base
        $utilisateur = Utilisateur::where('email', $credentials['email'])->first();

        if ($utilisateur && Hash::check($credentials['password'], $utilisateur->password)) {
            // Vérifier que le rôle sélectionné correspond à l'utilisateur
            if ($utilisateur->role !== $selectedRole) {
                return back()->withErrors([
                    'email' => 'Rôle incorrect pour cet utilisateur.',
                ])->withInput();
            }

            Auth::login($utilisateur);

            return redirect('/page')->with('success', 'Connexion réussie !');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->withInput();
    }



    //tout ce qui concerne client CRUD 
    public function Liste_client()
    {
        $clients = Clients::with(['agences', 'equipment'])
            ->latest()
            ->paginate(12);

        return view('Maintenance.Liste_client', compact('clients'));
    }

    public function index()
    {
        return view('Maintenance.client');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:banque,assurance,telecom,gouvernement,prive,Maintenance,Installation,Reparation,Formation,Consultation',
            'description' => 'nullable|string|max:500'
        ]);

        Clients::create($request->only(['name', 'category', 'description']));

        return redirect('/liste_client')
            ->with('success', 'Service créé avec succès !');
    }

    public function edite_client($id)
    {
        $clients = Clients::findOrFail($id);
        return view('Maintenance.Update_client', compact('clients'));
    }

    public function updatee(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500'
        ]);
        $clients = Clients::findOrFail($id);
        $clients->update($request->only(['name', 'category', 'description']));

        return redirect('/liste_client')->with('success', 'Client modifié avec succès !');
    }

    public function supprimerService($id)
    {
        try {
            $client = Clients::findOrFail($id);
            $client->delete();

            return redirect()->route('liste_client')->with('success', 'Client supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
    //tout ce qui concerne agence CRUD 
    public function Agence()
    {
        $clients = Clients::all();
        return view('Maintenance.agence', compact('clients'));
    }


    public function Agence_traitement(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        Agences::create($validated);

        return redirect('/listeag')->with('success', 'Agence ajoutée avec succès.');
    }

    public function Liste_agence()
    {
        $agences = Agences::with(['client', 'equipment'])
            ->latest()
            ->paginate(12);

        $clients = Clients::all(); // Pour le formulaire d'ajout

        return view('Maintenance.agence_liste', compact('agences', 'clients'));
    }

    public function supprimerAgence($id)
    {
        $agence = Agences::findOrFail($id);
        $agence->delete();

        return redirect()->back()->with('success', "L'agence a été supprimée avec succès !");
    }


    public function editAgence($id)
    {
        $agence = Agences::findOrFail($id);
        $clients = Clients::all(); // Pour afficher une liste déroulante si nécessaire

        return view('Maintenance.edit_agence', compact('agence', 'clients'));
    }



    public function updateAgence(Request $request, $id)
    {
        // Validation des champs
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        // Trouver l'agence à modifier
        $agence = Agences::findOrFail($id);

        // Mise à jour des données
        $agence->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            // client_id non modifiable ici
        ]);

        return redirect('/listeag')->with('success', 'Agence mise à jour avec succès.');
    }

    // tout ce qui concerne Equipement CRUD
    public function Equipement(Request $request)
    {

        $agences = Agences::with('client')->get();
        return view('Maintenance.Equipement', compact('agences'));
    }

    public function Equipement_traitement(Request $request)
    {

        $validated = $request->validate([
            'agency_id' => 'required|exists:agences,id',
            'type' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'serial_number' => 'required|string|unique:equipements',
            'part_number' => 'nullable|string',
            'installation_date' => 'required|date',
            'warranty_end_date' => 'nullable|date',
            'status' => 'required|in:actif,maintenance,hors_service'
        ]);

        Equipements::create($validated);
        return redirect('/listeequip')->with('success', 'Équipement ajouté avec succès');
    }


   public function Liste_Equipement(Request $request)
{
    $query = Equipements::with(['interventions', 'agency.client']);

    if ($request->filled('client')) {
        $query->whereHas('agency.client', function ($q) use ($request) {
            $q->where('id', $request->client);
        });
    }

    if ($request->filled('agence')) {
        $query->whereHas('agency', function ($q) use ($request) {
            $q->where('id', $request->agence);
        });
    }

    if ($request->filled('type')) {
        $query->where('type', 'like', '%' . $request->type . '%');
    }
 if ($request->filled('brand')) {
        $query->where('brand', 'like', '%' . $request->brand . '%');}
        
    $equipements = $query->get();
    $clients = Clients::all();
    $agences = Agences::all();

    return view('Maintenance.Liste_equipement', compact('equipements', 'clients', 'agences'));
}

    

    public function Supprimer_equipement($id)
    {
        $equipements = Equipements::findOrFail($id);
        $equipements->delete();

        return redirect()->back()->with('success', "L'agence a été supprimée avec succès !");
    }

    public function Modifier_Equipement($id)
    {

        $equipements = Equipements::findOrFail($id);
        $agences = Agences::all(); // Pour afficher une liste déroulante si nécessaire

        return view('Maintenance.edite_equipement', compact('agences', 'equipements'));
    }



    public function Equipement_traitement_update(Request $request, $id)
    {
        // Validation des champs
        $request->validate([

            'type' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'serial_number' => 'required|unique:equipements,serial_number,' . $id,
            'part_number' => 'nullable|string',
            'installation_date' => 'required|date',
            'warranty_end_date' => 'nullable|date',
            'status' => 'required|in:actif,maintenance,hors_service'
        ]);
        $equipements = Equipements::findOrFail($id);
        $equipements->update($request->only([
            'type',
            'brand',
            'model',
            'serial_number',
            'part_number',
            'installation_date',
            'warranty_end_date',
            'status'
        ]));



        return redirect('/listeequip')->with('success', 'Agence mise à jour avec succès.');
    }

    //pour le changement de mot de passe
    public function edit()
    {
        return view('Maintenance.changementMotDePasse');
    }

    public function update_motdepasse(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:utilisateurs,email',
            'ancien_mot_de_passe' => 'required',
            'nouveau_mot_de_passe' => 'required|confirmed|min:6',
        ]);

        $user = Utilisateur::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->ancien_mot_de_passe, $user->password)) {
            return back()->with('error', 'Email ou ancien mot de passe incorrect.');
        }

        $user->password = Hash::make($request->nouveau_mot_de_passe);
        $user->save();

        return redirect('/page')->with('success', 'Mot de passe mis à jour avec succès.');
    }
}















/*class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with(['agency.client', 'maintenances']);
        
        // Filtres de recherche
        if ($request->filled('client_id')) {
            $query->whereHas('agency', function($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('serial_number')) {
            $query->where('serial_number', 'like', '%' . $request->serial_number . '%');
        }
        
        $equipment = $query->paginate(20);
        
        return view('equipment.index', compact('equipment'));
    }
    
    public function show(Equipment $equipment)
    {
        $equipment->load(['agency.client', 'maintenances', 'reminders']);
        return view('equipment.show', compact('equipment'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'type' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'serial_number' => 'required|string|unique:equipment',
            'part_number' => 'nullable|string',
            'installation_date' => 'required|date',
            'warranty_end_date' => 'nullable|date',
            'status' => 'required|in:actif,maintenance,hors_service'
        ]);
        
        Equipment::create($validated);
        return redirect()->route('equipment.index')->with('success', 'Équipement ajouté avec succès');
    }
}

// app/Http/Controllers/MaintenanceController.php
class MaintenanceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:preventive,corrective,urgence',
            'scheduled_date' => 'required|date',
            'observations' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'technician' => 'nullable|string'
        ]);
        
        $maintenance = Maintenance::create($validated);
        
        // Créer un rappel automatique
        $this->createReminder($maintenance);
        
        return redirect()->back()->with('success', 'Maintenance programmée');
    }
    
    private function createReminder(Maintenance $maintenance)
    {
        MaintenanceReminder::create([
            'equipment_id' => $maintenance->equipment_id,
            'next_maintenance_date' => $maintenance->scheduled_date,
            'reminder_days_before' => 7
        ]);
    }
}



//4. Système de Rappels Automatiques
// app/Console/Commands/SendMaintenanceReminders.php
class SendMaintenanceReminders extends Command
{
    protected $signature = 'maintenance:send-reminders';
    protected $description = 'Envoie les rappels de maintenance';
    
    public function handle()
    {
        $reminders = MaintenanceReminder::where('is_sent', false)
            ->whereDate('next_maintenance_date', '<=', now()->addDays(7))
            ->with(['equipment.agency.client'])
            ->get();
            
        foreach ($reminders as $reminder) {
            // Envoyer notification (email, SMS, etc.)
            Mail::to('maintenance@src.com')->send(new MaintenanceReminderMail($reminder));
            
            $reminder->update(['is_sent' => true]);
        }
        
        $this->info("Envoyé {$reminders->count()} rappels de maintenance");
    }
}

// Dans app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('maintenance:send-reminders')->daily();
}

//5. systeme de recherche et filtrage
// app/Http/Controllers/SearchController.php
class SearchController extends Controller
{
    public function advanced(Request $request)
    {
        $query = Equipment::with(['agency.client', 'maintenances']);
        
        // Recherche multi-critères
        if ($request->filled('search_term')) {
            $term = $request->search_term;
            $query->where(function($q) use ($term) {
                $q->where('serial_number', 'like', "%{$term}%")
                  ->orWhere('part_number', 'like', "%{$term}%")
                  ->orWhere('model', 'like', "%{$term}%")
                  ->orWhereHas('agency', function($subQ) use ($term) {
                      $subQ->where('name', 'like', "%{$term}%");
                  })
                  ->orWhereHas('agency.client', function($subQ) use ($term) {
                      $subQ->where('name', 'like', "%{$term}%");
                  });
            });
        }
        
        // Filtres par date
        if ($request->filled('date_from')) {
            $query->where('installation_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('installation_date', '<=', $request->date_to);
        }
        
        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $results = $query->paginate(20);
        
        return view('search.results', compact('results'));
    }
}

//tableau de bord et statistique
// app/Http/Controllers/DashboardController.php
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_equipment' => Equipment::count(),
            'active_equipment' => Equipment::where('status', 'actif')->count(),
            'pending_maintenance' => Maintenance::where('status', 'planifie')->count(),
            'equipment_by_client' => Client::withCount('equipment')->get(),
            'upcoming_maintenance' => Maintenance::where('scheduled_date', '>=', now())
                ->where('scheduled_date', '<=', now()->addDays(30))
                ->with(['equipment.agency.client'])
                ->get()
        ];
        
        return view('dashboard', compact('stats'));
    }
}

}

*/