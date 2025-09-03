<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AlerteMaintenanceTechnicien extends Notification
{
    public $intervention;

    public function __construct($intervention)
    {
        $this->intervention = $intervention;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Rappel de maintenance programmée')
            ->line("Bonjour {$notifiable->nom},")
            ->line("Une maintenance que vous avez planifiée ou qui a été planifiée en votre nom est prévue le {$this->intervention->Date}.")
            ->line("Type d'intervention : {$this->intervention->Type_intervention}")
            ->line("Client : {$this->intervention->Nom_Agence}")
            ->line("Agence : {$this->intervention->Nom_site}")
            ->line("Description : {$this->intervention->Description}")
            ->action('Voir les détails', url('/liste/' . $this->intervention->id))
            ->line('Merci de vérifier et préparer cette intervention.');
    }
}

?>