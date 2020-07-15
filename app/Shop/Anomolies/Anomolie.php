<?php
namespace App\Shop\Anomolies;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;

class Anomolie extends Model
{
    
    //use Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anomolies';

    protected $fillable = [
        'id',
        'anomolies_points',
        'anomolies_points_reply',
        'anomolies_date',
        'status',
        'created_at',
        'updated_at'
    ];

	
	
}