<?php
namespace App\Shop\Logs;

use Illuminate\Database\Eloquent\Model;
use App\Shop\Addresses\Address;
//use Kyslik\ColumnSortable\Sortable;

class AmendmentLogAdmin extends Model
{
    
    //use Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'amendment_log_admins';

    protected $fillable = [
        'id',
        'CompanyName',
        'CompanyContact',
        'AdminClerk',
        'DayofWeek',
        'OriginalOrderDate',
        'NewOrderDate',
        'NewOrderDate2',
        'NewOrderDate3',
		'NewOrderDate4',
        'NewOrderDate5',
        'Cancellation',
        'AmendedOrderDetails',
        'created_at',
        'updated_at'
    ];
/* public function companyNameShow()
{
	
	return $this->hasManyThrough('App\Shop\Addresses\Address', 'App\Shop\Customers\Customer', 'id', 'id','CompanyName','default_address_id')->withPivot('value');
	
} */

public function companyNameShow()
{
	
	
	 return $this->getCustomerDefaultAddressId->belongsTo('App\Shop\Addresses\Address','default_address_id')->withDefault();
	
	
}

public function getCustomerDefaultAddressId()
{
	 return $this->belongsTo('App\Shop\Customers\Customer','CompanyName')->withDefault();
	
	
}

/**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function defaultaddress()
    {
        return $this->belongsTo('App\Shop\Addresses\Address','default_address_id')->withDefault();
    }
   
}