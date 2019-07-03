<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

	private $add_to;
	private $add_from;
	private $mail_code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
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
	 * Enregistrement du code de retour inclue dans le mail
	 * @param $recup_mail_code
	 */
	public function setMailCode($recup_mail_code)
	{
		$this->mail_code = $recup_mail_code;
	}

	/**
	 * Envoie du mail.
	 * @return $this
	 */
    public function build()
    {
        return $this->to($this->add_to)
	        ->from($this->add_from)
	        ->subject('Validez votre compte Tasso.')
	        ->view('emails.mailConfirm')->with('mail_code', $this->mail_code);
    }
}
