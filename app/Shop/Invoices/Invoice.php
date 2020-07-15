<?php

namespace App\Shop\Invoices;

use Illuminate\Database\Eloquent\Model;
use App\Shop\InvoiceNotes\InvoiceNote;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'invoiceid',
        'status',
        'payment_method',
        'po_number',
        'po_number_confirm',
        'is_confirm',
        'start_date',
        'end_date',
        'paid_date',
        'week_no',
        'month',
        'year',
        'start_date',
        'end_date',
        'type',
        'remittance',
        'trans_id',
        ];
	
	/**
     * Get the notes for the invoices.
     */
    public function notes()
    {
        return $this->hasMany('InvoiceNote', 'invoiceid', 'invoiceid');
    }
}
