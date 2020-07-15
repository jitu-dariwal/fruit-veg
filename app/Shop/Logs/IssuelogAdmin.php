<?php
namespace App\Shop\Logs;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;

class IssuelogAdmin extends Model
{
    
    //use Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'issuelog_admins';

    protected $fillable = [
        'id',
        'NatureOfIssue',
        'ClientEffected',
        'Responsibility',
        'Details',
        'Resolution',
        'FinancialImplication',
        'LossType',
        'issuelog_status',
		'CompanyName',
        'CompanyName_search',
        'CompanyContact',
        'AdminClerk',
        'date1',
        'OrderNumber',
        'created_at',
        'updated_at'
    ];

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