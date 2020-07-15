<?php

namespace App\Shop\Addresses;

use App\Shop\Customers\Customer;
use App\Shop\Orders\Order;
use App\Shop\Provinces\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Shop\Cities\City;
use App\Shop\Countries\Country;
use Nicolaslopezj\Searchable\SearchableTrait;

class Address extends Model
{
    use SoftDeletes, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'street_address',
        'address_line_2',
        'post_code',
        'city',
        'county_state',
        'country_id',
        'customer_id',
        'Access_Time',
        'Access_Time_latest',
        'access_24_hours',
        'delivery_notes',
   ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates = ['deleted_at'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'first_name' => 5,
            'last_name' => 10,
            'company_name' => 5,
            'street_address' => 5,
            'address_line_2' => 10,
            'post_code' => 10
        ]
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    public function province()
    {
        return $this->belongsTo(Province::class)->withDefault();
    }

    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @param $term
     *
     * @return mixed
     */
    public function searchAddress($term)
    {
        return self::search($term);
    }
}
