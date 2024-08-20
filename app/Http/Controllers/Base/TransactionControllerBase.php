<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Account;
use App\Models\GoldPrice;
use App\Models\Good;
use App\Models\GoodLoading;
use App\Models\GoodLoadingDetail;
use App\Models\GoodUnit;
use App\Models\Journal;
use App\Models\Member;
use App\Models\PiutangPayment;
use App\Models\ReturItem;
use App\Models\Transaction;
use App\Models\TransactionDetail;

trait TransactionControllerBase 
{
    public function indexTransactionBase($role, $role_id, $start_date, $end_date, $pagination)
    {
        $transactions = [];

        $sub_total = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                ->whereDate('transactions.created_at', '<=', $end_date) 
                                ->where('transactions.payment', 'cash')
                                ->whereRaw('transactions.money_paid >= total_sum_price')
                                ->where('transactions.type', 'normal')
                                ->orderBy('transactions.created_at','desc')
                                ->get();

        if($pagination == 'all')
        {
            if($role_id == 'all')
            {
                $transactions['cash'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                    ->whereDate('transactions.created_at', '<=', $end_date) 
                                                    ->where('payment', 'cash')
                                                    ->whereRaw('money_paid >= total_sum_price')
                                                    ->where('type', 'normal')
                                                    ->orderBy('transactions.created_at','desc')
                                                    ->get();

                $transactions['retur'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                    ->whereDate('transactions.created_at', '<=', $end_date) 
                                                    ->where('type', 'retur')
                                                    ->orderBy('transactions.created_at','desc')
                                                    ->get();
            }
            else
            {
                $transactions['cash'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                    ->whereDate('transactions.created_at', '<=', $end_date) 
                                                    ->where('payment', 'cash')
                                                    ->whereRaw('money_paid >= total_sum_price')
                                                    ->where('role', $role)
                                                    ->where('role_id', $role_id)
                                                    ->where('type', 'normal')
                                                    ->orderBy('transactions.created_at','desc')
                                                    ->get();

                $transactions['retur'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                    ->whereDate('transactions.created_at', '<=', $end_date) 
                                                    ->where('type', 'retur')
                                                    ->where('role', $role)
                                                    ->where('role_id', $role_id)
                                                    ->orderBy('transactions.created_at','desc')
                                                    ->get();
            }
        }
        else
        {
            if($role_id == 'all')
            {
                $transactions['cash'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                    ->whereDate('transactions.created_at', '<=', $end_date)
                                                    ->where('payment', 'cash')
                                                    ->whereRaw('money_paid >= total_sum_price')
                                                    ->where('type', 'normal')
                                                    ->orderBy('transactions.created_at','desc')
                                                    ->paginate($pagination);

                $transactions['retur'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                    ->whereDate('transactions.created_at', '<=', $end_date) 
                                                    ->where('type', 'retur')
                                                    ->orderBy('transactions.created_at','desc')
                                                    ->paginate($pagination);
            }
            else
            {
                $transactions['cash'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                    ->whereDate('transactions.created_at', '<=', $end_date)
                                                    ->where('payment', 'cash')
                                                    ->whereRaw('money_paid >= total_sum_price')
                                                    ->where('role', $role)
                                                    ->where('role_id', $role_id)
                                                    ->where('type', 'normal')
                                                    ->orderBy('transactions.created_at','desc')
                                                    ->paginate($pagination);

                $transactions['retur'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                                    ->whereDate('transactions.created_at', '<=', $end_date) 
                                                    ->where('type', 'retur')
                                                    ->where('role', $role)
                                                    ->where('role_id', $role_id)
                                                    ->orderBy('transactions.created_at','desc')
                                                    ->paginate($pagination);
            }
        }

        return [$sub_total, $transactions];
    }

    public function storeTransactionBase($role, $role_id, Request $request)
    {
        $hpp = 0;
        $sum = 0;

        if($request->member_name != null)
        {
            $data_member_new['name'] = $request->member_name;

            $member = Member::where('name', $request->member_name)->first();

            if($member == null)
                $member = Member::create($data_member_new);

            $data_transaction['member_id'] = $member->id;
        }
        else
        {
            $data_transaction['member_id'] = $request->member_id;
        }

        #tabel transaction
        $data_transaction['type'] = $request->type;
        $data_transaction['role'] = $role;
        $data_transaction['role_id'] = $role_id;
        $data_transaction['total_item_price'] = unformatNumber($request->total_item_price);
        $data_transaction['total_discount_price'] = unformatNumber($request->total_discount_price);
        $data_transaction['total_sum_price'] = unformatNumber($request->total_sum_price);
        $data_transaction['money_paid'] = unformatNumber($request->money_paid);
        $data_transaction['money_returned'] = unformatNumber($request->money_returned);
        $data_transaction['store']   = 'emas pak tani demak';
        $data_transaction['payment'] = 'cash';
        $data_transaction['note']    = $request->note;

        $transaction = Transaction::create($data_transaction);

        #tabel transaction detail
        $data_detail['transaction_id'] = $transaction->id;

        for ($i = 0; $i < sizeof($request->barcodes); $i++) 
        { 
            if($request->barcodes[$i] != null)
            {
                $good_unit = GoodUnit::find($request->barcodes[$i]);
                $data_detail['good_unit_id']   = $good_unit->id;
                $data_detail['type']           = $request->type;
                $data_detail['quantity']       = $request->quantities[$i];
                $data_detail['real_quantity']  = $request->quantities[$i] * $good_unit->unit->quantity;
                $data_detail['last_stock']     = $good_unit->good->getStock();
                $data_detail['gold_weight']    = $good_unit->good->weight;
                $data_detail['gold_price']     = unformatNumber($request->gold_prices[$i]);
                if($good_unit->good->gold_history_number == 'N')
                {
                    $data_detail['buy_price']  = ($data_detail['gold_price'] * $good_unit->good->weight * $good_unit->good->percentage->nominal) + checkNull($good_unit->good->change_status_fee);
                }
                else
                {
                    $data_detail['buy_price']  = $good_unit->good->getLastBuy() != null ? $good_unit->good->getLastBuy()->price : 0;
                }
                $data_detail['selling_price']  = unformatNumber($request->prices[$i]);
                $data_detail['discount_price'] = unformatNumber($request->discounts[$i]);
                $data_detail['stone_price']    = unformatNumber($request->stone_prices[$i]);
                $data_detail['sum_price']      = unformatNumber($request->sums[$i]);

                TransactionDetail::create($data_detail);

                $sum += $data_detail['sum_price'];
                $hpp += ($data_detail['buy_price'] * $data_detail['quantity']) + checkNull($good_unit->good->stone_price) + checkNull($good_unit->good->change_status_fee);
            }
        }

        $sum = $sum - checkNull($data_transaction['total_discount_price']);

        #tabel journal transaksi
        $data_journal['debit_account_id']   = Account::where('code', '1111')->first()->id;
        $data_journal['type']               = 'transaction';
        $data_journal['type_id']            = $transaction->id;
        $data_journal['journal_date']       = date('Y-m-d');
        $data_journal['name']               = 'Penjualan tanggal ' . displayDate(date('Y-m-d')) . ' (ID ' . $transaction->id . ')';
        $data_journal['debit']              = $sum;
        $data_journal['credit_account_id']  = Account::where('code', '4101')->first()->id;
        $data_journal['credit']             = $sum;

        Journal::create($data_journal);

        #tabel journal hpp
        $data_hpp['type']               = 'hpp';
        $data_hpp['type_id']            = $transaction->id;
        $data_hpp['journal_date']       = date('Y-m-d');
        $data_hpp['name']               = 'HPP penjualan tanggal ' . displayDate(date('Y-m-d')) . ' (ID ' . $transaction->id . ')';
        $data_hpp['debit_account_id']   = Account::where('code', '5101')->first()->id;
        $data_hpp['debit']              = $hpp;
        $data_hpp['credit_account_id']  = Account::where('code', '3001')->first()->id;
        $data_hpp['credit']             = $hpp;

        Journal::create($data_hpp);

        return $transaction;
    }

    public function updateTransactionBase($role, $role_id, $transaction_id, Request $request)
    {
        $hpp = 0;
        $sum = 0;

        if($request->member_name != null)
        {
            $data_member_new['name'] = $request->member_name;

            $member = Member::where('name', $request->member_name)->first();

            if($member == null)
                $member = Member::create($data_member_new);

            $data_transaction['member_id'] = $member->id;
        }
        else
        {
            $data_transaction['member_id'] = $request->member_id;
        }

        #tabel transaction
        $data_transaction['type'] = $request->type;
        $data_transaction['role'] = $role;
        $data_transaction['role_id'] = $role_id;
        $data_transaction['total_item_price'] = unformatNumber($request->total_item_price);
        $data_transaction['total_discount_price'] = unformatNumber($request->total_discount_price);
        $data_transaction['total_sum_price'] = unformatNumber($request->total_sum_price);
        $data_transaction['money_paid'] = unformatNumber($request->money_paid);
        $data_transaction['money_returned'] = unformatNumber($request->money_returned);
        $data_transaction['store']   = config('app.store_name');
        $data_transaction['payment'] = 'cash';
        $data_transaction['note']    = $request->note;

        $transaction = Transaction::find($transaction_id);
        $transaction->update($data_transaction);

        #tabel transaction detail
        $data_detail['transaction_id'] = $transaction->id;

        for ($i = 0; $i < sizeof($request->detail_ids); $i++) 
        { 
            if($request->detail_ids[$i] != null)
            {
                $data_detail['buy_price']      = unformatNumber($request->buy_prices[$i]);
                $data_detail['selling_price']  = unformatNumber($request->prices[$i]);
                $data_detail['discount_price'] = unformatNumber($request->discounts[$i]);
                $data_detail['stone_price']    = unformatNumber($request->stone_prices[$i]);
                $data_detail['sum_price']      = unformatNumber($request->sums[$i]);

                $detail = TransactionDetail::find($request->detail_ids[$i]);
                $detail->update($data_detail);

                $sum += $data_detail['sum_price'];
                $hpp += ($data_detail['buy_price'] * $data_detail['quantity']) + checkNull($good_unit->good->stone_price) + checkNull($good_unit->good->change_status_fee);
            }
        }

        $sum = $sum - checkNull($data_transaction['total_discount_price']);

        #tabel journal transaksi
        $journal = Journal::where('type', 'transaction')->where('type_id', $transaction->id)->first();

        #tabel journal 
        $data_journal['journal_date']       = date('Y-m-d');
        $data_journal['name']               = 'Edit penjualan tanggal ' . displayDate(date('Y-m-d')) . ' (ID ' . $transaction->id . ')';
        $data_journal['debit']              = $sum;
        $data_journal['credit']             = $sum;

        $journal->update($data_journal);

        #tabel journal hpp
        $journal_hpp = Journal::where('type', 'hpp')->where('type_id', $transaction->id)->first();

        #tabel journal 
        $data_journal_hpp['journal_date']       = date('Y-m-d');
        $data_journal_hpp['name']               = 'Edit hpp penjualan tanggal ' . displayDate(date('Y-m-d')) . ' (ID ' . $transaction->id . ')';
        $data_journal_hpp['debit']              = $hpp;
        $data_journal_hpp['credit']             = $hpp;

        $journal_hpp->update($data_journal_hpp);

        return $transaction;
    }

    public function reverseTransactionBase($role, $role_id, $journal_status, $transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        $total = 0;

        foreach($transaction->details as $detail)
        {
            $good = $detail->good_unit->good;

            $data_loading['role']         = $role;
            $data_loading['role_id']      = $role_id;
            $data_loading['checker']      = "Created by system";
            $data_loading['loading_date'] = date('Y-m-d');
            $data_loading['distributor_id']   = $good->last_distributor_id;
            $data_loading['total_item_price'] = $detail->quantity * $detail->buy_price;
            $data_loading['note']             = "Reverse transaction id " . $transaction->id;
            $data_loading['payment']          = "cash";

            $good_loading = GoodLoading::create($data_loading);

            $data_detail['good_loading_id'] = $good_loading->id;
            $data_detail['good_unit_id']    = $detail->good_unit->id;
            $data_detail['last_stock']      = $good->getStock();
            $data_detail['quantity']        = $detail->quantity;
            $data_detail['real_quantity']   = $detail->real_quantity;
            $data_detail['price']           = $detail->buy_price;
            $data_detail['selling_price']   = $detail->selling_price;

            GoodLoadingDetail::create($data_detail);

            $total += $data_loading['total_item_price'];
        }

        $data_journal['type']               = 'good_loading';
        $data_journal['type_id']            = $good_loading->id;
        $data_journal['journal_date']       = date('Y-m-d');
        $data_journal['name']               = 'Loading ' . $journal_status . ' transaction ID ' . $transaction->id . ' (loading ID ' . $good_loading->id . ')';
        $data_journal['debit_account_id']   = Account::where('code', '4101')->first()->id;
        $data_journal['debit']              = unformatNumber($transaction->total_sum_price);
        if($transaction->payment == 'cash')
            $data_journal['credit_account_id']  = Account::where('code', '1111')->first()->id;
        elseif($transaction->payment == 'transfer')
            $data_journal['credit_account_id']  = Account::where('code', '1112')->first()->id;
        $data_journal['credit']             = unformatNumber($transaction->total_sum_price);

        Journal::create($data_journal);

        $data_hpp['type']               = 'hpp';
        $data_hpp['journal_date']       = date('Y-m-d');
        $data_hpp['name']               = 'Penjualan ' . $journal_status . ' transaction ID ' . $transaction->id . ' (loading ID ' . $good_loading->id . ')';
        $data_hpp['debit_account_id']   = Account::where('code', '1141')->first()->id;
        $data_hpp['debit']              = unformatNumber($total);
        $data_hpp['credit_account_id']  = Account::where('code', '5101')->first()->id;
        $data_hpp['credit']             = unformatNumber($total);

        Journal::create($data_hpp);

        $data_transaction['type'] = $journal_status;
        $transaction->update($data_transaction);

        return true;
    }

    public function resumeTransactionBase($category_id, $distributor_id, $start_date, $end_date)
    {
        if($category_id == 'all' && $distributor_id == 'all')
        {
            $total = TransactionDetail::whereDate('transaction_details.created_at', '>=', $start_date)
                                      ->whereDate('transaction_details.created_at', '<=', $end_date) 
                                      ->get();

            $transaction_details = TransactionDetail::join('good_units', 'good_units.id', 'transaction_details.good_unit_id')
                                                    ->join('goods', 'goods.id', 'good_units.good_id')
                                                    ->join('units', 'units.id', 'good_units.unit_id')
                                                    ->select(DB::raw("goods.code, goods.name, units.name as unit_name, SUM(transaction_details.quantity) as quantity, transaction_details.buy_price, transaction_details.selling_price"))
                                                    ->whereDate('transaction_details.created_at', '>=', $start_date)
                                                    ->whereDate('transaction_details.created_at', '<=', $end_date) 
                                                    ->groupBy('goods.code')
                                                    ->groupBy('goods.name')
                                                    ->groupBy('units.name')
                                                    ->groupBy('transaction_details.buy_price')
                                                    ->groupBy('transaction_details.selling_price')
                                                    ->orderBy('quantity', 'desc')
                                                    ->get();
        }
        else if($category_id == 'all')
        {
            $total = TransactionDetail::join('good_units', 'good_units.id', 'transaction_details.good_unit_id')
                                      ->join('goods', 'goods.id', 'good_units.good_id')
                                      ->whereDate('transaction_details.created_at', '>=', $start_date)
                                      ->whereDate('transaction_details.created_at', '<=', $end_date) 
                                      ->where('goods.last_distributor_id', $distributor_id)
                                      ->get();

            $transaction_details = TransactionDetail::join('good_units', 'good_units.id', 'transaction_details.good_unit_id')
                                                    ->join('goods', 'goods.id', 'good_units.good_id')
                                                    ->join('units', 'units.id', 'good_units.unit_id')
                                                    ->select(DB::raw("goods.code, goods.name, units.name as unit_name, SUM(transaction_details.quantity) as quantity, transaction_details.buy_price, transaction_details.selling_price"))
                                                    ->whereDate('transaction_details.created_at', '>=', $start_date)
                                                    ->whereDate('transaction_details.created_at', '<=', $end_date) 
                                                    ->where('goods.last_distributor_id', $distributor_id)
                                                    ->groupBy('goods.code')
                                                    ->groupBy('goods.name')
                                                    ->groupBy('units.name')
                                                    ->groupBy('transaction_details.buy_price')
                                                    ->groupBy('transaction_details.selling_price')
                                                    ->orderBy('quantity', 'desc')
                                                    ->get();
        }
        else
        {   
            $total = TransactionDetail::join('good_units', 'good_units.id', 'transaction_details.good_unit_id')
                                      ->join('goods', 'goods.id', 'good_units.good_id')
                                      ->whereDate('transaction_details.created_at', '>=', $start_date)
                                      ->whereDate('transaction_details.created_at', '<=', $end_date) 
                                      ->where('goods.last_distributor_id', $distributor_id)
                                      ->where('goods.category_id', $category_id)
                                      ->get();

            $transaction_details = TransactionDetail::join('good_units', 'good_units.id', 'transaction_details.good_unit_id')
                                                    ->join('goods', 'goods.id', 'good_units.good_id')
                                                    ->join('units', 'units.id', 'good_units.unit_id')
                                                    ->select(DB::raw("goods.code, goods.name, units.name as unit_name, SUM(transaction_details.quantity) as quantity, transaction_details.buy_price, transaction_details.selling_price"))
                                                    ->where('goods.category_id', $category_id)
                                                    ->where('goods.last_distributor_id', $distributor_id)
                                                    ->whereDate('transaction_details.created_at', '>=', $start_date)
                                                    ->whereDate('transaction_details.created_at', '<=', $end_date) 
                                                    ->groupBy('goods.code')
                                                    ->groupBy('goods.name')
                                                    ->groupBy('units.name')
                                                    ->groupBy('transaction_details.buy_price')
                                                    ->groupBy('transaction_details.selling_price')
                                                    ->orderBy('quantity', 'desc')
                                                    ->get();
        }

        return [$transaction_details, $total];
    }

    public function resumeTotalTransactionBase($start_date, $end_date)
    {
        $transactions['normal'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                            ->whereDate('transactions.created_at', '<=', $end_date) 
                                            ->where('type', 'normal')
                                            ->get();

        $transactions['retur'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                            ->whereDate('transactions.created_at', '<=', $end_date) 
                                            ->where('type', 'retur')
                                            ->get();

        $transactions['not_valid'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                            ->whereDate('transactions.created_at', '<=', $end_date) 
                                            ->where('type', 'not valid')
                                            ->get();

        $transactions['internal'] = Transaction::whereDate('transactions.created_at', '>=', $start_date)
                                            ->whereDate('transactions.created_at', '<=', $end_date) 
                                            ->where('type', '!=', 'normal')
                                            ->where('type', '!=', 'retur')
                                            ->where('type', '!=', 'not valid')
                                            ->get();

        $transactions['other_payment'] = Journal::where('type', 'like', '%_payment%')
                                        ->whereDate('journals.journal_date', '>=', $start_date)
                                        ->whereDate('journals.journal_date', '<=', $end_date) 
                                        ->get();

        $transactions['other_transaction'] = Journal::where('type', 'like', '%_transaction')
                                        ->whereDate('journals.journal_date', '>=', $start_date)
                                        ->whereDate('journals.journal_date', '<=', $end_date) 
                                        ->get();   

        return $transactions;                             
    }

    public function storeMoneyTransactionBase(Request $request)
    {
        $data_journal['type']               = 'cash_draw';
        $data_journal['journal_date']       = date('Y-m-d');
        $data_journal['name']               = 'Pengambilan uang tanggal ' . date('Y-m-d');
        $data_journal['debit_account_id']   = Account::where('code', '1113')->first()->id;
        $data_journal['debit']              = unformatNumber($request->money);
        $data_journal['credit_account_id']  = Account::where('code', '1111')->first()->id;
        $data_journal['credit']             = unformatNumber($request->money);

        Journal::create($data_journal);

        return true;
    }
}