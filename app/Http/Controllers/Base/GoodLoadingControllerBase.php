<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;
use App\Imports\LoadingImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Account;
use App\Models\Category;
use App\Models\Distributor;
use App\Models\Good;
use App\Models\GoodLoading;
use App\Models\GoodLoadingDetail;
use App\Models\GoodPrice;
use App\Models\GoodUnit;
use App\Models\Journal;

trait GoodLoadingControllerBase 
{
    public function indexGoodLoadingBase($type, $start_date, $end_date, $distributor_id, $pagination)
    {
        if($distributor_id == "all")
        {
            if($pagination == 'all')
            {
                if($type == 'all')
                {
                    $good_loadings = GoodLoading::whereDate('good_loadings.created_at', '>=', $start_date)
                                                ->whereDate('good_loadings.created_at', '<=', $end_date) 
                                                ->where('type', '!=', 'loading')
                                                ->orderBy('good_loadings.loading_date','asc')
                                                ->get();
                }
                else
                {
                    $good_loadings = GoodLoading::whereDate('good_loadings.created_at', '>=', $start_date)
                                                ->whereDate('good_loadings.created_at', '<=', $end_date) 
                                                ->where('type', $type)
                                                ->orderBy('good_loadings.loading_date','asc')
                                                ->get();
                }
            }
            else
            {
                if($type == 'all')
                {
                    $good_loadings = GoodLoading::whereDate('good_loadings.created_at', '>=', $start_date)
                                                ->whereDate('good_loadings.created_at', '<=', $end_date)
                                                ->where('type', '!=', 'loading')
                                                ->orderBy('good_loadings.loading_date','asc')
                                                ->paginate($pagination);
                }
                else
                {
                    $good_loadings = GoodLoading::whereDate('good_loadings.created_at', '>=', $start_date)
                                                ->whereDate('good_loadings.created_at', '<=', $end_date)
                                                ->where('type', $type)
                                                ->orderBy('good_loadings.loading_date','asc')
                                                ->paginate($pagination);
                }
            }
        }
        else
        {
            if($pagination == 'all')
            {
                if($type == 'all')
                {
                    $good_loadings = GoodLoading::whereDate('good_loadings.created_at', '>=', $start_date)
                                                ->whereDate('good_loadings.created_at', '<=', $end_date) 
                                                ->where('good_loadings.distributor_id', $distributor_id)
                                                ->where('type', '!=', 'loading')
                                                ->orderBy('good_loadings.loading_date','asc')
                                                ->get();
                }
                else
                {
                    $good_loadings = GoodLoading::whereDate('good_loadings.created_at', '>=', $start_date)
                                                ->whereDate('good_loadings.created_at', '<=', $end_date) 
                                                ->where('good_loadings.distributor_id', $distributor_id)
                                                ->where('type', $type)
                                                ->orderBy('good_loadings.loading_date','asc')
                                                ->get();
                }
            }
            else
            {
                if($type == 'all')
                {
                    $good_loadings = GoodLoading::whereDate('good_loadings.created_at', '>=', $start_date)
                                                ->whereDate('good_loadings.created_at', '<=', $end_date)
                                                ->where('good_loadings.distributor_id', $distributor_id)
                                                ->where('type', '!=', 'loading')
                                                ->orderBy('good_loadings.loading_date','asc')
                                                ->paginate($pagination);
                }
                else
                {
                    $good_loadings = GoodLoading::whereDate('good_loadings.created_at', '>=', $start_date)
                                                ->whereDate('good_loadings.created_at', '<=', $end_date)
                                                ->where('good_loadings.distributor_id', $distributor_id)
                                                ->where('type', $type)
                                                ->orderBy('good_loadings.loading_date','asc')
                                                ->paginate($pagination);
                }
            }
        }

        return $good_loadings;
    }

    public function storeGoodLoadingBase($role, $role_id, Request $request)
    {
        $data = $request->input();
        // dd($data);die;

        if($data['distributor_name'] != null)
        {
            $distributor = Distributor::where('name', $data['distributor_name'])->first();

            if($distributor == null)
            {
                $data_distributor['name'] = $data['distributor_name'];

                $distributor = Distributor::create($data_distributor);
            }
            $data['distributor_id'] = $distributor->id;
        }

        $data_loading['role']         = $role;
        $data_loading['role_id']      = $role_id;
        $data_loading['checker']      = $data['checker'];
        $data_loading['loading_date'] = $data['loading_date'];
        $data_loading['distributor_id']   = $data['distributor_id'];
        $data_loading['total_item_price'] = unformatNumber($request->total_item_price);
        $data_loading['note']             = $data['note'];
        $data_loading['payment']          = '1111';
        $data_loading['type']             = $data['type'];

        $good_loading = GoodLoading::create($data_loading);

        for($i = 0; $i < sizeof($data['names']); $i++) 
        { 
            if($data['names'][$i] != null)
            {
                $data['prices'][$i] = unformatNumber($data['prices'][$i]);
                $data['stone_prices'][$i] = unformatNumber($data['stone_prices'][$i]);

                $data_good['category_id'] = $data['category_ids'][$i];
                $data_good['is_old_gold'] = $data['is_old_golds'][$i];
                $data_good['name'] = $data['names'][$i];
                $data_good['percentage_id'] = $data['percentage_ids'][$i];
                $data_good['weight'] = displayGramComa($data['weights'][$i]);
                $data_good['status'] = $data['statuses'][$i];
                $data_good['gold_history_number'] = $data['gold_history_numbers'][$i];
                $data_good['stone_weight'] = $data['stone_weights'][$i];
                $data_good['stone_price'] = $data['stone_prices'][$i];

                if($request->type == 'buy')
                {
                    $good = Good::find($data['ids'][$i]);

                    $code = explode(' ', $good->code);

                    $hist_no = $data['gold_history_numbers'][$i];
                    if($hist_no != 'N')
                        $hist_no = intval($hist_no + 1);
                    $data_good['code'] = $code[0] . ' ' . $code[1] . ' ' . $code [2] . ' ' . $code[3] . ' ' . $hist_no;

                    $good->update($data_good);
                }
                else
                {
                    $good = Good::create($data_good);

                    $category = Category::find($data_good['category_id']);

                    $is_code_exist = true;
                    $last_id = $good->getLastCategoryId();

                    while($is_code_exist)
                    {
                        $barcode = '';
                        $id = $last_id;
                        while($id < 1000)
                        {
                            $barcode .= '0';
                            $id = $id * 10; 
                        }
                        $barcode .= $last_id;

                        $data_code['code'] = $category->code . ' ' . date('y') . ' ' . $barcode . ' ' . date('m');

                        $is_exist = Good::where('code', 'like', $data_code['code'] . '%')->withTrashed()->first();

                        if($is_exist == null)
                        {
                            $hist_no = $good->gold_history_number;
                            if($hist_no != 'N')
                                $hist_no = intval($hist_no + 1);
                            $data_code['code'] = $category->code . ' ' . date('y') . ' ' . $barcode . ' ' . date('m') . ' ' . $hist_no;

                            $good->update($data_code);
                            $is_code_exist = false;
                        }
                        else
                        {
                            $last_id += 1;
                        } 
                    }
                }
                    
                $data_unit['good_id']       = $good->id;
                $data_unit['unit_id']       = 1;
                $data_unit['buy_price']     = $data['prices'][$i];
                $data_unit['selling_price'] = 1;

                $good_unit = GoodUnit::create($data_unit);

                $data_price['role']         = $role;
                $data_price['role_id']      = $role_id;
                $data_price['good_unit_id'] = $good_unit->id;
                $data_price['old_price']    = $good_unit->selling_price;
                $data_price['recent_price'] = 1;
                $data_price['reason']       = 'Harga pertama';

                GoodPrice::create($data_price);

                $data['selling_prices'][$i] = 1;
                
                $good_unit = GoodUnit::where('good_id', $good->id)
                                     ->where('unit_id', 1)
                                     ->first();

                if($good_unit)
                {
                    if($good_unit->selling_price != $data['selling_prices'][$i])
                    {
                        $data_price['role']         = $role;
                        $data_price['role_id']      = $role_id;
                        $data_price['good_unit_id'] = $good_unit->id;
                        $data_price['old_price']    = $good_unit->selling_price;
                        $data_price['recent_price'] = $data['selling_prices'][$i];
                        $data_price['reason']       = 'Diubah saat loading';

                        GoodPrice::create($data_price);
                    }

                    $data_unit['good_id']       = $good->id;
                    $data_unit['unit_id']       = 1;
                    $data_unit['buy_price']     = $data['prices'][$i];
                    $data_unit['selling_price'] = $data['selling_prices'][$i];

                    $good_unit->update($data_unit);
                }
                else
                {
                    $data_unit['good_id']       = $good->id;
                    $data_unit['unit_id']       = 1;
                    $data_unit['buy_price']     = $data['prices'][$i];
                    $data_unit['selling_price'] = $data['selling_prices'][$i];

                    $good_unit = GoodUnit::create($data_unit);

                    $data_price['role']         = $role;
                    $data_price['role_id']      = $role_id;
                    $data_price['good_unit_id'] = $good_unit->id;
                    $data_price['old_price']    = $good_unit->selling_price;
                    $data_price['recent_price'] = $data['selling_prices'][$i];
                    $data_price['reason']       = 'Harga pertama';

                    GoodPrice::create($data_price);
                }

                $data_detail['good_loading_id'] = $good_loading->id;
                $data_detail['good_unit_id']    = $good_unit->id;
                $data_detail['last_stock']      = 1;
                $data_detail['quantity']        = 1;
                $data_detail['real_quantity']   = $good_unit->unit->quantity;
                $data_detail['price']           = $data['prices'][$i];
                $data_detail['selling_price']   = $data['selling_prices'][$i];

                GoodLoadingDetail::create($data_detail);
            }
        }

        #tabel journal 
        $account = Account::where('code', '1111')->first();

        $data_journal['type']               = 'good_loading';
        $data_journal['type_id']            = $good_loading->id;
        $data_journal['journal_date']       = $data['loading_date'];
        $data_journal['name']               = 'Loading barang ' . $good_loading->distributor->name . ' tanggal ' . displayDate($good_loading->loading_date);
        $data_journal['debit_account_id']   = Account::where('code', '3001')->first()->id;
        $data_journal['debit']              = unformatNumber($request->total_item_price);
        $data_journal['credit_account_id']  = $account->id;
        $data_journal['credit']             = unformatNumber($request->total_item_price);

        Journal::create($data_journal);

        return $good_loading;
    }

    public function updateGoodLoadingBase($role, $role_id, $good_loading_id, Request $request)
    {
        $data = $request->input();
        // dd($data);die;

        if($data['distributor_name'] != null)
        {
            $distributor = Distributor::where('name', $data['distributor_name'])->first();

            if($distributor == null)
            {
                $data_distributor['name'] = $data['distributor_name'];

                $distributor = Distributor::create($data_distributor);
            }
            $data['distributor_id'] = $distributor->id;
        }

        $data_loading['role']         = $role;
        $data_loading['role_id']      = $role_id;
        $data_loading['checker']      = $data['checker'];
        $data_loading['loading_date'] = $data['loading_date'];
        $data_loading['distributor_id']   = $data['distributor_id'];
        $data_loading['total_item_price'] = unformatNumber($request->total_item_price);
        $data_loading['note']             = $data['note'];
        $data_loading['payment']          = '1111';
        $data_loading['type']             = $data['type'];

        $good_loading = GoodLoading::find($good_loading_id);
        $good_loading->update($data_loading);

        $journal = Journal::where('type', 'good_loading')->where('type_id', $good_loading->id)->first();

        #tabel journal 
        $data_journal['journal_date']       = $good_loading->loading_date;
        $data_journal['name']               = 'Edit loading barang ' . $good_loading->distributor->name . ' tanggal ' . displayDate($good_loading->loading_date);
        $data_journal['debit']              = unformatNumber($good_loading->total_item_price);
        $data_journal['credit']             = unformatNumber($good_loading->total_item_price);

        $journal->update($data_journal);

        for($i = 0; $i < sizeof($data['ids']); $i++) 
        { 
            if($data['ids'][$i] != null)
            {
                $data['prices'][$i] = unformatNumber($data['prices'][$i]);
                $data['stone_prices'][$i] = unformatNumber($data['stone_prices'][$i]);

                $data_good['category_id'] = $data['category_ids'][$i];
                $data_good['is_old_gold'] = $data['is_old_golds'][$i];
                $data_good['name'] = $data['names'][$i];
                $data_good['percentage_id'] = $data['percentage_ids'][$i];

                $data_good['weight'] = displayGramComa($data['weights'][$i]);
                $data_good['status'] = $data['statuses'][$i];
                $data_good['gold_history_number'] = $data['gold_history_numbers'][$i];
                $data_good['stone_weight'] = $data['stone_weights'][$i];
                $data_good['stone_price'] = $data['stone_prices'][$i];

                $good = Good::find($data['good_ids'][$i]);
                $good->update($data_good);

                $data_detail['price'] = $data['prices'][$i];

                $good_loading_detail = GoodLoadingDetail::find($data['ids'][$i]);
                $good_loading_detail->update($data_detail);
            }
        }

        return $good_loading;
    }

    public function storeExcelGoodLoadingBase($role, $role_id, Request $request)
    {
        // $data = $request
        if($request->hasFile('file')) 
        {
            $distributor = Distributor::where('name', $request->distributor_name)->first();

            if($distributor == null)
            {
                if($request->distributor_id == null || $request->distributor_id == 'null')
                {
                    $data_distributor['name'] = $request->distributor_name;

                    $distributor = Distributor::create($data_distributor);
                }
                else
                {
                    $distributor = Distributor::find($request->distributor_id);
                }
            }

            $data_loading['role']             = $role;
            $data_loading['role_id']          = $role_id;
            $data_loading['checker']          = "Upload by sistem";
            $data_loading['loading_date']     = date('Y-m-d');
            $data_loading['distributor_id']   = $distributor->id;
            $data_loading['total_item_price'] = $request->total_item_price;
            $data_loading['note']             = "Upload by sistem";
            $data_loading['payment']          = "cash";

            $good_loading = GoodLoading::create($data_loading);
            Excel::import(new LoadingImport($role, $role_id, $good_loading->id), $request->file('file'));

            #tabel journal 
            $data_journal['type']               = 'good_loading';
            $data_journal['journal_date']       = date('Y-m-d');
            $data_journal['name']               = 'Loading barang ' . $good_loading->distributor->name . ' tanggal ' . displayDate($good_loading->loading_date);
            $data_journal['debit_account_id']   = Account::where('code', '1141')->first()->id;
            $data_journal['debit']              = unformatNumber($request->total_item_price);
            $data_journal['credit_account_id']  = Account::where('code', '3001')->first()->id; #modal awal pas pertama upload barang
            $data_journal['credit']             = unformatNumber($request->total_item_price);

            Journal::create($data_journal);

            return $good_loading;
        }
    }

    public function printBarcodeFromLoadingGoodLoadingBase($good_loading_id)
    {
        $goods = [];
        $good_loading = GoodLoading::find($good_loading_id);
        foreach($good_loading->details as $detail)
        {
            if($detail->good_unit_id != null)
            {
                $good_unit = GoodUnit::find($detail->good_unit_id);

                if($good_unit->good != null)
                {
                    if($good_unit->good->status == 'Siap dijual')
                    {
                        $data['barcode'] = $good_unit->good->getBarcode();
                        $data['name'] = $good_unit->good->name;
                        $data['code'] = $good_unit->good->code;
                        $data['weight'] = $good_unit->good->weight;
                        $data['old_gold'] = '';
                        if($good_unit->good->is_old_gold == 1)
                            $data['old_gold'] = 'MT';
                        $data['stone_weight'] = $good_unit->good->stone_weight;
                        $data['stone_price'] = showRupiah($good_unit->good->stone_price);

                        array_push($goods, $data);
                    }  
                }
            }
        }
        
        return $goods;
    }

    public function deleteGoodLoadingBase($good_loading_id)
    {
        $good_loading = GoodLoading::find($good_loading_id);

        $account = Account::where('code', '1111')->first();

        $data_journal['type']               = 'good_loading';
        $data_journal['journal_date']       = $good_loading->loading_date;
        $data_journal['name']               = 'Hapus loading barang ' . $good_loading->distributor->name . ' tanggal ' . displayDate($good_loading->loading_date);
        $data_journal['debit']              = unformatNumber($good_loading->total_item_price);
        $data_journal['debit_account_id']   = $account->id;
        $data_journal['credit_account_id']  = Account::where('code', '3001')->first()->id;
        $data_journal['credit']             = unformatNumber($good_loading->total_item_price);

        Journal::create($data_journal);

        $good_loading->delete();

        return true;
    }
}
