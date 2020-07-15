<?php

namespace App\Shop\InvoiceNotes;

use Illuminate\Database\Eloquent\Model;

class InvoiceNote extends Model
{
    protected $fillable = [
        'customer_id',
        'invoiceid',
        'notes'
        ];
}
