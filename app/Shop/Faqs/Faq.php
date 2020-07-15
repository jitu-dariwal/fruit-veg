<?php

namespace App\Shop\Faqs;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
	public $timestamps = true;
	protected $fillable = ['question','answer','status'];
}
