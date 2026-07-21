<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;

use App\Models\ByOrderTransaction;

use App\Http\Controllers\Base\GoodLoadingControllerBase;
use App\Http\Controllers\Base\TransactionControllerBase;

trait ByOrderTransactionControllerBase 
{
    use GoodLoadingControllerBase, TransactionControllerBase;
    
    public function indexByOrderTransactionBase($start_date, $end_date, $pagination, $status)
    {
        if($status == 'ongoing')
        {
            if($pagination == 'all')
               $orders = ByOrderTransaction::whereDate('by_order_transactions.created_at', '>=', $start_date)
                                            ->whereDate('by_order_transactions.created_at', '<=', $end_date) 
                                            ->where('good_unit_id', null)
                                            ->orderBy('good_unit_id', 'asc')->get();
            else
               $orders = ByOrderTransaction::whereDate('by_order_transactions.created_at', '>=', $start_date)
                                            ->whereDate('by_order_transactions.created_at', '<=', $end_date) 
                                            ->where('good_unit_id', null)
                                            ->orderBy('good_unit_id', 'asc')->paginate($pagination);
        }
        else
        {
            if($pagination == 'all')
               $orders = ByOrderTransaction::whereDate('by_order_transactions.created_at', '>=', $start_date)
                                            ->whereDate('by_order_transactions.created_at', '<=', $end_date) 
                                            ->where('good_unit_id', '!=', null)
                                            ->orderBy('good_unit_id', 'asc')->get();
            else
               $orders = ByOrderTransaction::whereDate('by_order_transactions.created_at', '>=', $start_date)
                                            ->whereDate('by_order_transactions.created_at', '<=', $end_date) 
                                            ->where('good_unit_id', '!=', null)
                                            ->orderBy('good_unit_id', 'asc')->paginate($pagination);
        }

        return $orders;
    }

    public function storeByOrderTransactionBase(Request $request)
    {
        $data = $request->input();
        // $data['fee'] = unformatNumber($data['fee']);
        $data['good_unit_id'] = null;

        $order = ByOrderTransaction::create($data);

        return $order;
    }

    public function updateByOrderTransactionBase($order_id, Request $request)
    {
        $data = $request->input();
        // $data['fee'] = unformatNumber($data['fee']);

        $order = ByOrderTransaction::find($order_id);
        $order->update($data);

        return $order;
    }

    public function deleteByOrderTransactionBase($order_id)
    {
        $order = ByOrderTransaction::find($order_id);
        $order->delete();
    }

    public function storeTransactionByOrderTransactionBase($order_id, $role, $role_id, Request $request)
    {
        $order = ByOrderTransaction::find($order_id);

        #loading section
        $data['distributor_name'] = 'Panen Raya';
        $data['checker']          = 'Pesanan by sistem';
        $data['loading_date']     = date('Y-m-d');
        $data['total_item_price'] = unformatNumber($request->total_price);
        $data['note']             = 'Loading barang pesanan by sistem (harga jual ' . showRupiah(unformatNumber($request->selling_price)) . ' & ongkos ' . showRupiah(unformatNumber($request->fee));
        $data['type']             = 'by-order';

        $data['prices'][0]        = unformatNumber($request->price_gram);
        $data['stone_prices'][0]  = 0;
        $data['category_ids'][0]  = $order->category_id;
        $data['is_old_golds'][0]  = 0;
        $data['names'][0]         = $order->model;
        $data['percentage_ids'][0] = $order->percentage_id;
        $data['weights'][0]       = displayGramComa($order->weight);
        $data['statuses'][0]      = 'Siap dijual';
        $data['gold_history_numbers'][0] = 'N';
        $data['stone_weights'][0] = 0;
        $data['stone_prices'][0]  = 0;

        $loading = $this->storeMainLoadingFunction($role, $role_id, $data);
        $good_unit = $loading->details[0]->good_unit;

        #transaction section
        $data_transaction['member_name'] = null;
        $data_transaction['member_id']   = 1;
        $data_transaction['type']        = 'normal';
        $data_transaction['trx_type']    = 'order';
        $data_transaction['total_item_price']     = unformatNumber($request->selling_price);
        $data_transaction['total_discount_price'] = 0;
        $data_transaction['total_sum_price']      = $data_transaction['total_item_price'] + unformatNumber($request->fee);
        $data_transaction['money_paid']           = $data_transaction['total_sum_price'];
        $data_transaction['money_returned']       = 0;
        $data_transaction['note']                 = 'Harga ongkos ' . showRupiah($request->fee);

        $data_transaction['barcodes'][0]          = $good_unit->id;
        $data_transaction['quantities'][0]        = 1;
        $data_transaction['gold_prices'][0]       = $data['prices'][0];
        $data_transaction['prices'][0]            = $data_transaction['total_item_price'];
        $data_transaction['discounts'][0]         = 0;
        $data_transaction['stone_prices'][0]      = 0;
        $data_transaction['sums'][0]              = $data_transaction['total_sum_price'];

        $transaction = $this->storeMainTransactionFunction($role, $role_id, $data_transaction);

        $data_order['good_unit_id'] = $good_unit->id;
        $data_order['fee']          = unformatNumber($request->fee);

        $order->update($data_order);

        return $transaction;
    }
}
