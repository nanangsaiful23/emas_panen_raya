<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherPayment extends Model
{    
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'date', 'nominal'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $dates =[
        'deleted_at',
    ];
}
