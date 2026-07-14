@extends('layout.user', ['role' => 'admin', 'title' => 'Admin'])

@section('content')
  <style type="text/css">
    .table-bordered tbody tr td, .table-bordered thead tr th
    {
      border: 1px solid #B17457;
    }
  </style>

  <div class="content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">{{ $default['page_name'] . ' ' . $category->name . ' ' . displayDate($start_date) . ' sampai ' . displayDate($end_date) }}</h3>
            </div>
            <div class="box-body">
              {!! Form::label('show', 'Show', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-1">
                {!! Form::select('show', getPaginations(), $pagination, ['class' => 'form-control', 'style'=>'width: 100%', 'id' => 'show', 'onchange' => 'changeDate()']) !!}
              </div>
              {!! Form::label('category', 'Kategori', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-2">
                {!! Form::select('category', getCategories(), $category->id, ['class' => 'form-control select2', 'style'=>'width: 100%', 'id' => 'category', 'onchange' => 'changeDate()']) !!}
              </div>
              {!! Form::label('start_date', 'Tanggal Awal', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-2">
                <div class="input-group date">
                  <input type="text" class="form-control pull-right" id="datepicker" name="start_date" value="{{ $start_date }}" onchange="changeDate()">
                </div>
              </div>
              {!! Form::label('end_date', 'Tanggal Akhir', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-2">
                <div class="input-group date">
                  <input type="text" class="form-control pull-right" id="datepicker2" name="end_date" value="{{ $end_date }}" onchange="changeDate()">
                </div>
              </div>
            </div>
            <div class="box-body" style="color: black !important">
              <div class="col-sm-3">
                <h4>Rangkuman transaksi</h4>
                <table class="no-border" style="font-size: 16px">
                  <tbody>
                    <tr>
                      <td width="50%">Total transaksi</td>
                      <td width="1%">:</td>
                      <td width="1%"></td>
                      <td style="text-align: right;">{{ $sub_total->sum('count_trans') }} transaksi</td>
                    </tr>
                    <tr>
                      <td width="50%">Total pemasukan</td>
                      <td width="1%">:</td>
                      <td width="1%">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($sub_total->sum('income')) }}</td>
                    </tr>
                    <tr>
                      <td width="50%">Total HPP</td>
                      <td width="1%">:</td>
                      <td width="1%">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($sub_total->sum('hpp')) }}</td>
                    </tr>
                    <tr>
                      <td width="50%">Total laba/rugi</td>
                      <td width="1%">:</td>
                      <td width="1%">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($sub_total->sum('result')) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <h4 style="margin-top: 20px">Detail transaksi</h4>
            <div class="box-body">
            Klik judul tabel untuk mengurutkan data
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Waktu</th>
                  <th>Jenis</th>
                  <th colspan="2">@if($order == 'desc')<a href="{{ url('admin/profit/category/'. $category->id . '/' . $start_date . '/' . $end_date . '/total_sum_price/asc/20') }}">@else<a href="{{ url('admin/profit/category/' . $category->id . '/' . $start_date . '/' . $end_date . '/total_sum_price/desc/20') }}">@endif <i class="fa fa-sort" aria-hidden="true"></i> Pemasukan</a></th>
                  <th colspan="2">@if($order == 'desc')<a href="{{ url('admin/profit/category/'. $category->id . '/' . $start_date . '/' . $end_date . '/hpp/asc/20') }}">@else<a href="{{ url('admin/profit/category/' . $category->id . '/' . $start_date . '/' . $end_date . '/hpp/desc/20') }}">@endif <i class="fa fa-sort" aria-hidden="true"></i> HPP</a></th>
                  <th colspan="2">@if($order == 'desc')<a href="{{ url('admin/profit/category/'. $category->id . '/' . $start_date . '/' . $end_date . '/status_fee/asc/20') }}">@else<a href="{{ url('admin/profit/category/' . $category->id . '/' . $start_date . '/' . $end_date . '/status_fee/desc/20') }}">@endif <i class="fa fa-sort" aria-hidden="true"></i> Potongan</a></th>
                  <th colspan="2">@if($order == 'desc')<a href="{{ url('admin/profit/category/'. $category->id . '/' . $start_date . '/' . $end_date . '/profit/asc/20') }}">@else<a href="{{ url('admin/profit/category/' . $category->id . '/' . $start_date . '/' . $end_date . '/profit/desc/20') }}">@endif <i class="fa fa-sort" aria-hidden="true"></i> Laba/Rugi</a></th>
                  <th>Detail Barang</th>
                  <th>Ubah Transaksi</th>
                </tr>
                </thead>
                <tbody id="table-transaction">
                  <?php $i = 1 ?>
                  @foreach($transactions as $transaction)
                    <tr style="@if($i%2 == 0) background-color: #DEE5D4; @endif">
                      <td>{{ $i++ }}</td>
                      <td>{{ $transaction->created_at }}</td>
                      <td>{{ $transaction->transaction->trx_type }}</td>
                      <td style="border-right-color: transparent;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->selling_price) }}</td>
                      <td style="border-right-color: transparent;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->buy_price) }}</td>
                      <td style="border-right-color: transparent;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->discount_price) }}</td>
                      <td style="border-right-color: transparent;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->selling_price - $transaction->buy_price - $transaction->discount_price) }}</td>
                      <td>{{ $transaction->good_unit->good->name . ' ' . $transaction->good_unit->good->weight . ' gram' }}</td>
                      <td class="center"><a href="{{ url('admin/transaction/' . $transaction->transaction->id . '/edit') }}"><i class="fa fa-pencil-square-o tosca" aria-hidden="true"></i></a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @section('js-addon')
    <script type="text/javascript">
      $(document).ready(function(){
        $('.select2').select2();
        $('#datepicker').datepicker({
          autoclose: true,
          format: 'yyyy-mm-dd'
        })

        $('#datepicker2').datepicker({
          autoclose: true,
          format: 'yyyy-mm-dd'
        })
      });

      function changeDate()
      {
        window.location = window.location.origin + '/admin/profit/category/' + $("#category").val() + '/' + $("#datepicker").val() + '/' + $("#datepicker2").val() + '/{{ $sort }}/{{ $order }}/' + $("#show").val();
      }
    </script>
  @endsection
@endsection