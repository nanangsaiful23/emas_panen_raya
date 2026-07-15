<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Base\ByOrderTransactionControllerBase;

use App\Models\ByOrderTransaction;

class ByOrderTransactionController extends Controller
{
    use ByOrderTransactionControllerBase;

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index($start_date, $end_date, $pagination)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Daftar Pesanan';
        $default['page'] = 'by-order-transaction';
        $default['section'] = 'all';

        $orders = $this->indexByOrderTransactionBase($start_date, $end_date, $pagination);

        return view('admin.layout.page', compact('default', 'orders', 'start_date', 'end_date', 'pagination'));
    }

    public function create()
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Tambah Pesanan';
        $default['page'] = 'by-order-transaction';
        $default['section'] = 'create';

        return view('admin.layout.page', compact('default'));
    }

    public function store(Request $request)
    {
        $order = $this->storeByOrderTransactionBase($request);

        session(['alert' => 'add', 'data' => 'Pesanan barang']);

        return redirect('/admin/by-order-transaction/' . $order->id . '/print');
    }

    public function detail($order_id)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Detail Pesanan';
        $default['page'] = 'by-order-transaction';
        $default['section'] = 'detail';

        $order = ByOrderTransaction::find($order_id);

        return view('admin.layout.page', compact('default', 'order'));
    }

    public function edit($order_id)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Ubah Pesanan';
        $default['page'] = 'by-order-transaction';
        $default['section'] = 'edit';

        $order = ByOrderTransaction::find($order_id);

        return view('admin.layout.page', compact('default', 'order'));
    }

    public function update($order_id, Request $request)
    {
        $order = $this->updateByOrderTransactionBase($order_id, $request);

        session(['alert' => 'edit', 'data' => 'Pesanan barang']);

        return redirect('/admin/by-order-transaction/' . $order->id . '/detail');
    }

    public function delete($order_id)
    {
        $this->deleteByOrderTransactionBase($order_id);

        session(['alert' => 'delete', 'data' => 'Pesanan barang']);

        return redirect('/admin/by-order-transaction/all/10');
    }

    public function print($order_id)
    {
        $role = 'admin';

        $order = ByOrderTransaction::find($order_id);

        return view('layout.by-order-transaction.print', compact('role', 'order'));
    }

    public function createTransaction($order_id)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Jual Emas Pesanan';
        $default['page'] = 'by-order-transaction';
        $default['section'] = 'create-transaction';

        $order = ByOrderTransaction::find($order_id);

        return view('admin.layout.page', compact('default', 'order'));
    }

    public function storeTransaction($order_id, Request $request)
    {
        $transaction = $this->storeTransactionByOrderTransactionBase($order_id, 'admin', \Auth::user()->id, $request);

        session(['alert' => 'add', 'data' => 'Penjualan pesanan']);

        return redirect('/admin/transaction/' . $transaction->id . '/detail');
    }
}
