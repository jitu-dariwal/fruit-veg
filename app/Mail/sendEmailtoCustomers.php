<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
//use Illuminate\Support\Facades\Config;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendEmailtoCustomers extends Mailable
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
		
		$this->from($mail_content['from_email'], env('MAIL_FROM_NAME'));
		$this->subject($mail_content['subject']);

        return $this->view('emails.admin.CustomerEmailTpl', compact('mail_content'));
    }
}
