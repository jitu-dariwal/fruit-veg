<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
//use Illuminate\Support\Facades\Config;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendEmailtoAdminOnNewuser extends Mailable
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
        $this->from($mail_content['email'], $mail_content['first_name']);
        $this->subject("New registration on FNV from front end");
        return $this->view('emails.customer.AdminOnNewuserEmailTpl', compact('mail_content'));
    }
}
