<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomMail extends Mailable
{
    use Queueable, SerializesModels;

	private $add_to;
	private $add_from;
	private $view_template;
	private $datas_template;
	private $subject_title = 'Tasso Store mail.';

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
 	 * Enregistrement du mail du template
	 * @param $recup_view_template
	 */
	public function setViewTemplate($recup_view_template)
	{
		$this->view_template = $recup_view_template;
	}

	/**
	 * Enregistrement des données passées au template
	 */
	public function setTemplateDatas($recup_datas_template)
	{
		$this->datas_template = $recup_datas_template;
	}

	/**
	 * Enregistrement du sujet du mail
	 * @param $subject
	 */
	public function setSubject($subject)
	{
		$this->subject_title = $subject;
	}

	/**
	 * Envoie du mail.
	 * @return $this
	 */
    public function build()
    {
        return $this->from($this->add_from)
	        ->subject($this->subject_title)
	        ->to($this->add_to)
	        ->view($this->view_template)
	        ->with($this->datas_template);
    }
}
