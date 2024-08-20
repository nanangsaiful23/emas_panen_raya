<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;

use App\Models\Account;

trait AccountControllerBase 
{
    public function indexAccountBase($pagination)
    {
        if($pagination == 'all')
           $accounts = Account::where('code', '1111')
                              ->orWhere('code', '1141')
                              ->orWhere('code', '3001')
                              ->orderBy('code', 'asc')->get();
        else
           $accounts = Account::where('code', '1111')
                              ->orWhere('code', '1141')
                              ->orWhere('code', '3001')
                              ->orderBy('code', 'asc')->paginate($pagination);

        return $accounts;
    }

    public function storeAccountBase(Request $request)
    {
        $data = $request->input();
        $data['balance'] = unformatNumber($data['balance']);

        $account = Account::create($data);

        return $account;
    }

    public function updateAccountBase($account_id, Request $request)
    {
        $data = $request->input();
        $data['balance'] = unformatNumber($data['balance']);

        $account = Account::find($account_id);
        $account->update($data);

        return $account;
    }

    public function deleteAccountBase($account_id)
    {
        $account = Account::find($account_id);
        $account->delete();
    }
}
