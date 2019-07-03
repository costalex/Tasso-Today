<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    private $add_to;
	private $add_from;
    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

	/**
	 * Enregistrement du nouveau mot de passe
	 * @param $new_password
	 */
    public function setNewPassword($new_password)
    {
		$this->password = $new_password;
    }

	/**
	 * Enregistrement de l'addresse de destinataire
	 * @param $recup_add_to
	 */
	public function setAddressTo($recup_add_to)
	{
		$this->add_to = $recup_add_to;
	}

	/**
	 * Enregistrement de l'addresse de l'envoyeur
	 * @param $recup_from
	 */
	public function setAddressFrom($recup_from)
	{
		$this->add_from = $recup_from;
	}

    /**
     * Envoie du mail.
     * @return $this
     */
    public function build()
    {
        return $this->to($this->add_to)
	        ->from($this->add_from)
	        ->subject('Modification mot de passe Tasso.')
	        ->view('emails.mailNewPassword')
	        ->with(['mot_de_passe' => $this->password]);
    }
}
