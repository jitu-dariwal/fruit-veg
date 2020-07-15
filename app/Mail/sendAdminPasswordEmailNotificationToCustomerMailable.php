<?php

namespace App\Mail;

use App\Shop\Addresses\Transformations\AddressTransformable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendAdminPasswordEmailNotificationToCustomerMailable extends Mailable
{
    use Queueable, SerializesModels, AddressTransformable;

    public $data;

    /**
     * Create a new message instance.
     *
     */
    public function __construct($data)
    {
        $this->data = $data;
		//echo "<pre>"; print_r($this->data); echo "ok"; exit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
       
        
		$customeruser_details = $this->data;
        
		
		//$this->from("admin@fruitandveg.co.uk","Login Details");
		$this->subject("Your login details");

        return $this->view('emails.admin.CustomerUserCreationEmail', compact('customeruser_details'));
    }
}
