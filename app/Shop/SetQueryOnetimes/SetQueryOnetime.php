<?php
namespace App\Shop\SetQueryOnetimes;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;

class SetQueryOnetime extends Model
{
    
    //use Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'set_query_onetimes';

    protected $fillable = [
        'id',
        'value',
        'createdate',
        'created_at',
        'updated_at'
    ];

	
	
}