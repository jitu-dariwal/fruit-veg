<?php

namespace App\Shop\InvoicePartPayments;

use Illuminate\Database\Eloquent\Model;

class InvoicePartPayment extends Model
{
    protected $fillable = [
        'customer_id',
        'invoiceid',
        'partpayment',
        ];
}
