<?php

namespace App\Shop\Employees;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Nicolaslopezj\Searchable\SearchableTrait;

class Employee extends Authenticatable
{
    use Notifiable, SoftDeletes, LaratrustUserTrait, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
		'last_name',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['deleted_at'];
	
	/**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'employees.first_name' => 10,
			'employees.last_name' => 10,
			'roles.display_name' => 10,
			'employees.email' => 5
        ],
		'joins' => [
            'role_user' => ['employees.id','role_user.user_id'],
			'roles' => ['role_user.role_id','roles.id'],
        ],
    ];
	
	 /**
     * @param $term
     *
     * @return mixed
     */
    public function searchEmployee($term)
    {
        return self::search($term);
    }
}
