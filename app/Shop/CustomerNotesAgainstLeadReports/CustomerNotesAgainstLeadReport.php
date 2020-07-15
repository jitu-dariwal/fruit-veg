<?php
namespace App\Shop\CustomerNotesAgainstLeadReports;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;

class CustomerNotesAgainstLeadReport extends Model
{
    
    //use Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer_notes_against_lead_reports';

    protected $fillable = [
        'id',
        'lead_id',
        'customers_id',
        'notes',
        'create_date',
        'modify_date',
        'ArrangeCallBackAlertDate',
        'ArrangeCallBackAlertTime',
        'agent',
        'created_at',
        'updated_at'
    ];

	
	
}