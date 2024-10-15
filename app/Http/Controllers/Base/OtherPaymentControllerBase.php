<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;

use App\Models\OtherPayment;

trait OtherPaymentControllerBase 
{
    public function indexOtherPaymentBase($start_date, $end_date, $pagination)
    {
        if($pagination == 'all')
           $payments = OtherPayment::whereDate('date', '>=', $start_date)
                                    ->whereDate('date', '<=', $end_date) 
                                    ->orderBy('id', 'desc')->get();
        else
           $payments = OtherPayment::whereDate('date', '>=', $start_date)
                                    ->whereDate('date', '<=', $end_date) 
                                    ->orderBy('id', 'desc')->paginate($pagination);

        return $payments;
    }

    public function storeOtherPaymentBase(Request $request)
    {
        $data = $request->input();
        $data['nominal'] = unformatNumber($request->nominal);

        $other_payment = OtherPayment::create($data);

        return $other_payment;
    }

    public function updateOtherPaymentBase($other_payment_id, Request $request)
    {
        $data = $request->input();
        $data['nominal'] = unformatNumber($request->nominal);

        $other_payment = OtherPayment::find($other_payment_id);
        $other_payment->update($data);

        return $other_payment;
    }

    public function deleteOtherPaymentBase($other_payment_id)
    {
        $other_payment = OtherPayment::find($other_payment_id);
        $other_payment->delete();
    }
}
