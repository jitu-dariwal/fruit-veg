<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
//use Illuminate\Support\Facades\Config;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendEmailtoAdminOnNewEnquiry extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
		$mail_content = (array)$this->data;
		$this->from($mail_content['email'], $mail_content['name']);
		$this->subject($mail_content['subject']);
		$this->markdown('emails.customer.AdminOnNewEnquiryEmailTpl', compact('mail_content'));
		//$this->view('emails.customer.AdminOnNewEnquiryEmailTpl', compact('mail_content'));
    }
}
