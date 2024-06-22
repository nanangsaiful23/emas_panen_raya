<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

use App\Models\TransactionDetail;

class Member extends Model
{    
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'address', 'phone_number', 'birth_place', 'birth_date', 'no_ktp', 'start_point'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $dates =[
        'deleted_at',
    ];

    public function transaction()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function getTotalGramTransaction()
    {
        $transactions = TransactionDetail::join('transactions', 'transactions.id', 'transaction_details.transaction_id')
                                          ->where('transactions.member_id', $this->id)
                                          ->get();

        return intdiv($transactions->sum('gold_weight'), 1);
    }

    public function redeemPoints()
    {
        return $this->hasMany('App\Models\RedeemPoint');
    }

    public function getTotalPoint()
    {
        $total = $this->getTotalGramTransaction() + $this->start_point;

        return $total;
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
