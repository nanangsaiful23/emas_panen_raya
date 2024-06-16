<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Member;
use App\Models\RedeemPoint;
use App\Models\Transaction;

trait MemberControllerBase 
{
    public function indexMemberBase($start_date, $end_date, $sort, $order, $pagination)
    {
        if($pagination == 'all')
           $members = Member::leftjoin('transactions', 'transactions.member_id', 'members.id')
                            ->select('members.id', 'members.name', 'members.address', 'members.phone_number', 'members.birth_place', 'members.birth_date', DB::raw('SUM(transactions.total_sum_price) as total_sum_price'), DB::raw('COUNT(transactions.id) as total_transaction'))
                            ->where('transactions.type', 'normal')
                            ->whereDate('transactions.created_at', '>=', $start_date)
                            ->whereDate('transactions.created_at', '<=', $end_date) 
                            ->groupBy('members.id', 'members.name', 'members.address', 'members.phone_number', 'members.birth_place', 'members.birth_date')
                            ->orderBy($sort, $order)->get();
        else
           $members = Member::leftjoin('transactions', 'transactions.member_id', 'members.id')
                            ->select('members.id', 'members.name', 'members.address', 'members.phone_number', 'members.birth_place', 'members.birth_date', DB::raw('SUM(transactions.total_sum_price) as total_sum_price'), DB::raw('COUNT(transactions.id) as total_transaction'))
                            ->where('transactions.type', 'normal')
                            ->whereDate('transactions.created_at', '>=', $start_date)
                            ->whereDate('transactions.created_at', '<=', $end_date) 
                            ->groupBy('members.id', 'members.name', 'members.address', 'members.phone_number', 'members.birth_place', 'members.birth_date')
                            ->orderBy($sort, $order)->paginate($pagination);

        return $members;
    }

    public function searchByNameMemberBase($query)
    {
        $members = Member::where('name', 'like', '%'. $query . '%')
                         ->orderBy('name', 'asc')
                         ->get();

        foreach($members as $member)
        {
            $member->birth_date    = $member->birth_date == null ? '-' : displayDate($member->birth_date);
            $member->total_transaction = $member->totalTransaction()->count();
            $member->transaction   = showRupiah($member->totalTransaction()->sum('total_sum_price'));
            $member->total_point   = $member->getTotalPoint();
            $member->redeem_point  = $member->getRedeemPoint();
            $member->current_point = $member->total_point - $member->redeem_point;
        }

        return $members;
    }

    public function storeMemberBase(Request $request)
    {
        $data = $request->input();

        $member = Member::create($data);

        return $member;
    }

    public function updateMemberBase($member_id, Request $request)
    {
        $data = $request->input();

        $member = Member::find($member_id);
        $member->update($data);

        return $member;
    }

    public function deleteMemberBase($member_id)
    {
        $member = Member::find($member_id);
        $member->delete();
    }

    public function transactionMemberBase($member_id, $start_date, $end_date, $pagination)
    {
        $transactions = [];

        if($pagination == 'all')
        {
            $transactions['cash'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                ->whereDate('transactions.created_at', '<=', $end_date) 
                                                ->where('payment', 'cash')
                                                ->whereRaw('money_paid >= total_sum_price')
                                                ->where('member_id', $member_id)
                                                ->orderBy('transactions.created_at','desc')
                                                ->get();

            $transactions['credit'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                ->whereDate('transactions.created_at', '<=', $end_date) 
                                                ->where('payment', 'cash')
                                                ->whereRaw('money_paid < total_sum_price')
                                                ->where('member_id', $member_id)
                                                ->orderBy('transactions.created_at','desc')
                                                ->get();

            $transactions['transfer'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                ->whereDate('transactions.created_at', '<=', $end_date) 
                                                ->where('payment', 'transfer')
                                                ->where('member_id', $member_id)
                                                ->orderBy('transactions.created_at','desc')
                                                ->get();
        }
        else
        {
            $transactions['cash'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                ->whereDate('transactions.created_at', '<=', $end_date)
                                                ->where('payment', 'cash')
                                                ->whereRaw('money_paid >= total_sum_price')
                                                ->where('member_id', $member_id)
                                                ->orderBy('transactions.created_at','desc')
                                                ->paginate($pagination);

            $transactions['credit'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                ->whereDate('transactions.created_at', '<=', $end_date)
                                                ->where('payment', 'cash')
                                                ->whereRaw('money_paid < total_sum_price')
                                                ->where('member_id', $member_id)
                                                ->orderBy('transactions.created_at','desc')
                                                ->paginate($pagination);
                                                
            $transactions['transfer'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                ->whereDate('transactions.created_at', '<=', $end_date)
                                                ->where('payment', 'transfer')
                                                ->where('member_id', $member_id)
                                                ->orderBy('transactions.created_at','desc')
                                                ->paginate($pagination);
        }

        return $transactions;
    }

    public function pointMemberBase($member_id, $start_date, $end_date, $pagination)
    {
        if($pagination == 'all')
        {
            $points = RedeemPoint::whereDate('created_at', '>=', $start_date)
                                    ->whereDate('created_at', '<=', $end_date) 
                                    ->where('member_id', $member_id)
                                    ->get();
        }
        else
        {
            $points = RedeemPoint::whereDate('created_at', '>=', $start_date)
                                    ->whereDate('created_at', '<=', $end_date) 
                                    ->where('member_id', $member_id)
                                    ->paginate($pagination);
        }

        return $points;
    }

    public function storeRedeemMemberBase($member_id, Request $request)
    {
        $data = $request->input();
        $data['member_id'] = $member_id;

        $point = RedeemPoint::create($data);

        return $point;
    }
}
