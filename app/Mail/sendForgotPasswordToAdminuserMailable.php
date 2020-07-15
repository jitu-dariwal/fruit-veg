<?php

namespace App\Mail;

use App\Shop\Addresses\Transformations\AddressTransformable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendForgotPasswordToAdminuserMailable extends Mailable
{
    use Queueable, SerializesModels, AddressTransformable;

    public $data;

    /**
     * Create a new message instance.
     *
     */
    public function __construct($data, $new_password)
    {
        $this->data = $data;
		$this->new_password = $new_password;
		//echo "<pre>"; print_r($this->data); echo "ok"; exit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
       
        
		$adminuser_details = (array)$this->data;
        $adminuser_details['new_password'] = $this->new_password;
		
		//$this->from("admin@fruitandveg.co.uk","Login Details");
		$this->subject("New password");

        return $this->view('emails.admin.AdminForgotPasswordEmail', compact('adminuser_details'));
    }
}
