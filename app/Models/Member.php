<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Member extends Model
{    
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'address', 'phone_number', 'birth_place', 'birth_date'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $dates =[
        'deleted_at',
    ];

    public function totalTransaction()
    {
        $transactions = Transaction::where('member_id', $this->id)
                                    ->get();

        return $transactions;
    }

    public function redeemPoints()
    {
        return $this->hasMany('App\Models\RedeemPoint');
    }

    public function getTotalPoint()
    {
        return intdiv($this->totalTransaction()->sum('total_sum_price'), 1000000);
    }

    public function getRedeemPoint()
    {
        return $this->redeemPoints()->sum('point');
    }

    public function getCurrentPoint()
    {
        return $this->getTotalPoint() - $this->getRedeemPoint();
    }
}
