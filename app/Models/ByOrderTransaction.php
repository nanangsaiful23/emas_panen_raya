<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ByOrderTransaction extends Model
{    
    use SoftDeletes;
    
    protected $fillable = [
        'role', 'role_id', 'name', 'address', 'phone_number', 'category_id', 'model', 'percentage_id', 'weight', 'good_unit_id', 'fee', 'order_date', 'finish_date'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $dates =[
        'deleted_at',
    ];
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    
    public function good_unit()
    {
        return $this->belongsTo('App\Models\GoodUnit');
    }
    
    public function percentage()
    {
        return $this->belongsTo('App\Models\Percentage');
    }
}
