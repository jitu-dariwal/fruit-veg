<?php
namespace App\Shop\OrdersTotals;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;

class OrdersTotal extends Model
{
    
    //use Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders_totals';

    protected $fillable = [
        'id',
        'orders_id',
        'title',
        'text',
        'value',
        'class',
        'sort_order',
        'created_at',
        'updated_at'
    ];


	
}