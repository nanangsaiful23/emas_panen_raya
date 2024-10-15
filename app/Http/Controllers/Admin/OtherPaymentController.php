<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Base\OtherPaymentControllerBase;

use App\Models\Account;
use App\Models\OtherPayment;

class OtherPaymentController extends Controller
{
    use OtherPaymentControllerBase;

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index($start_date, $end_date, $pagination)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Daftar Biaya Lain';
        $default['page'] = 'other-payment';
        $default['section'] = 'all';

        $other_payments = $this->indexOtherPaymentBase($start_date, $end_date, $pagination);

        return view('admin.layout.page', compact('default', 'other_payments', 'start_date', 'end_date', 'pagination'));
    }

    public function create()
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Tambah Biaya Lain';
        $default['page'] = 'other-payment';
        $default['section'] = 'create';

        return view('admin.layout.page', compact('default'));
    }

    public function store(Request $request)
    {
        $this->storeOtherPaymentBase($request);

        session(['alert' => 'add', 'data' => 'Biaya Lain']);

        return redirect('/admin/other-payment/' . date('Y-m-d') . '/' . date('Y-m-d') . '/15');
    }

    public function detail($other_payment_id)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Detail Biaya Lain';
        $default['page'] = 'other-payment';
        $default['section'] = 'detail';

        $other_payment = OtherPayment::find($other_payment_id);

        return view('admin.layout.page', compact('default', 'other_payment'));
    }

    public function edit($other_payment_id)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Ubah Biaya Lain';
        $default['page'] = 'other-payment';
        $default['section'] = 'edit';

        $other_payment = OtherPayment::find($other_payment_id);

        return view('admin.layout.page', compact('default', 'other_payment'));
    }

    public function update($other_payment_id, Request $request)
    {
        $other_payment = $this->updateOtherPaymentBase($other_payment_id, $request);

        session(['alert' => 'edit', 'data' => 'Biaya Lain']);

        return redirect('/admin/other-payment/' . $other_payment->id . '/detail');
    }

    public function delete($other_payment_id)
    {
        $this->deleteOtherPaymentBase($other_payment_id);

        session(['alert' => 'delete', 'data' => 'Biaya Lain']);

        return redirect('/admin/other-payment/all/10');
    }
}
