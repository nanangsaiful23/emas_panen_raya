<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MemberExport;

use App\Http\Controllers\Base\MemberControllerBase;

use App\Models\Member;

class MemberController extends Controller
{
    use MemberControllerBase;

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index($start_date, $end_date, $sort, $order, $pagination)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Daftar Member';
        $default['page'] = 'member';
        $default['section'] = 'all';

        $members = $this->indexMemberBase($start_date, $end_date, $sort, $order, $pagination);

        return view('admin.layout.page', compact('default', 'members', 'start_date', 'end_date', 'sort', 'order', 'pagination'));
    }

    public function search($member_id)
    {
        $member = Member::find($member_id);

        return response()->json([
            "member"  => $member
        ], 200);
    }

    public function searchByName($name)
    {
        $members = $this->searchByNameMemberBase($name);

        return response()->json([
            "members"  => $members
        ], 200);
    }

    public function create()
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Tambah Member';
        $default['page'] = 'member';
        $default['section'] = 'create';

        return view('admin.layout.page', compact('default'));
    }

    public function store(Request $request)
    {
        $member = $this->storeMemberBase($request);

        session(['alert' => 'add', 'data' => 'member']);

        return redirect('/admin/member/' . $member->id . '/detail');
    }

    public function detail($member_id)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Detail Member';
        $default['page'] = 'member';
        $default['section'] = 'detail';

        $member = Member::find($member_id);

        return view('admin.layout.page', compact('default', 'member'));
    }

    public function transaction($member_id, $start_date, $end_date, $pagination)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Riwayat Transaksi Member';
        $default['page'] = 'member';
        $default['section'] = 'transaction';

        $member = Member::find($member_id);
        $transactions = $this->transactionMemberBase($member_id, $start_date, $end_date, $pagination);

        return view('admin.layout.page', compact('default', 'member', 'transactions', 'start_date', 'end_date', 'pagination'));
    }

    public function point($member_id, $start_date, $end_date, $pagination)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $member = Member::find($member_id);

        $default['page_name'] = 'Riwayat Penukaran Point Member ' . $member->name . ' (' . $member->phone_number . ')';
        $default['page'] = 'member';
        $default['section'] = 'point';

        $points = $this->pointMemberBase($member_id, $start_date, $end_date, $pagination);

        return view('admin.layout.page', compact('default', 'member', 'points', 'start_date', 'end_date', 'pagination'));
    }

    public function edit($member_id)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $default['page_name'] = 'Ubah Member';
        $default['page'] = 'member';
        $default['section'] = 'edit';

        $member = Member::find($member_id);

        return view('admin.layout.page', compact('default', 'member'));
    }

    public function update($member_id, Request $request)
    {
        $member = $this->updateMemberBase($member_id, $request);

        session(['alert' => 'edit', 'data' => 'member barang']);

        return redirect('/admin/member/' . $member->id . '/detail');
    }

    public function delete($member_id)
    {
        $this->deleteMemberBase($member_id);

        session(['alert' => 'delete', 'data' => 'member']);

        return redirect('/admin/member/2024-01-01/' . date('Y-m-d') . '/name/asc/15');
    }

    public function createRedeem($member_id)
    {
        [$default['type'], $default['color'], $default['data']] = alert();

        $member = Member::find($member_id);

        $default['page_name'] = 'Redeem Point Member ' . $member->name . ' (' . $member->phone_number . ')';
        $default['page'] = 'member';
        $default['section'] = 'create-redeem';

        return view('admin.layout.page', compact('default', 'member'));
    }

    public function storeRedeem($member_id, Request $request)
    {
        $member = $this->storeRedeemMemberBase($member_id, $request);

        session(['alert' => 'add', 'data' => 'redeem point']);

        return redirect('/admin/member/' . $member->id . '/point/2024-01-01/' . date('Y-m-d') . '/20');
    }

    public function export()
    {
        $result = [['Nama', 'Alamat', 'Desa', 'Kecamatan', 'No Telephone', 'Tanggal Lahir', 'No KTP/SIM', 'Sisa Point']];

        $members = Member::orderBy('name', 'asc')->get();
        foreach($members as $member)
        {
            array_push($result, [$member->name, $member->address, $member->village, $member->subdistrict, $member->phone_number, $member->birth_date, $member->no_ktp, $member->getCurrentPoint()]);
        }

        return Excel::download(new MemberExport($result), 'Data Member ' . date('Y-m-d') . '.xlsx');
    }
}
