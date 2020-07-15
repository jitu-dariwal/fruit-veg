<?php

namespace App\Shop\Customers;

use App\Shop\Addresses\Address;
use App\Shop\Orders\Order;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Customer extends Authenticatable
{
    use Notifiable, SoftDeletes, SearchableTrait, Billable;

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
        'company_name',
        'street_address',
        'address_line_2',
        'post_code',
        'city',
        'county_state',
        'country',
        'tel_num',
        'fax_num',
        'newsletter',
        'current_spend_month',
        'credit_checked',
        'credit_by',
        'credit_ref',
        'minimum_order',
        'Access_Time',
        'Access_Time_latest',
        'company_tax_id',
        'authentication_alert',
        'token',
        'customers_email_address_check_for',
        'customers_company_contact_email_extra',
        'customers_company_contact_email_extra_check_for',
        'customers_account_contact_email_extra',
        'customers_account_contact_email_extra_check_for',
        'invoice_street_address',
        'invoice_suburb',
        'invoice_postcode',
        'invoice_city',
        'invoice_state',
        'fleetmatics_street_address',
        'fleetmatics_suburb',
        'fleetmatics_post_code',
        'fleetmatics_city',
        'fleetmatics_county_state',
        'credit_terms_agreed',
        'credit_terms_agreed_from',
        'customers_require_invoice_type',
        'sage_ref',
        'Hear_About_Us',
        'Box_Type',
        'customers_accountemail',
        'customers_accountemail_check_for',
        'customers_notify',
        'Box_Info',
        'Delivery_Procedure_Customer',
        'fob_card',
        'purchase_order_number',
        'customers_acc_trade_contract',
        'CustomersInvoiceMethod',
        'customers_invoice_notes',
        'monthly_marker',
        'customers_group_id',
        'customers_emailvalidated',
        'activation_mail_send',
        'chase',
        'customers_payment_settings',
        'customers_payment_allowed',
        'delivery_status',
        'global_product_notifications',
        'credit_application_form',
        'status',
		'shipping_disabled_dates',
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
            'customers.first_name' => 10,
            'customers.last_name'  => 10,
            //'addresses.company_name' => 10,
            'customers.email'      => 5,
        ],
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class)->where('status', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function defaultaddress()
    {
        return $this->belongsTo(Address::class, 'default_address_id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ordersWithDiscount()
    {
        return $this->orders()->whereNotNull('customer_discount')->where('customer_discount', '!=', '0.00')->where('order_status_id', '=', 3);
    }

    /**
     * @param $term
     *
     * @return mixed
     */
    public function searchCustomer($term)
    {
        return self::search($term);
    }
}
