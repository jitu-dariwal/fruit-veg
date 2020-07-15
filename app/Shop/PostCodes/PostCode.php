<?php

namespace App\Shop\PostCodes;

use Illuminate\Database\Eloquent\Model;

class PostCode extends Model
{
	public $timestamps = true;
	protected $fillable = ['title','week_days','post_codes','status'];
}
