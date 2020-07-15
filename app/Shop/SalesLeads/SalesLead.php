<?php

namespace App\Shop\SalesLeads;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;

class SalesLead extends Model
{
	
	 protected $table = 'sales_leads';
	 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
		'customers_id',
        'SalesClerk_ID',
        'SalesClerk',
        'ClientName',
        'Company',
        'Enquiry',
        'Tel_1',
        'Tel_2',
        'eMail',
        'Address1',
        'Address2',
        'Town',
        'County',
        'Postcode',
        'status',
        'ArrangeCallBackAlertDate',
        'ArrangeCallBackAlertTime',
        'created_at',
        'updated_at',
        'Hear_About_Us'
    ];

  
    public function searchEmployee($term)
    {
        return self::search($term);
    }
}
