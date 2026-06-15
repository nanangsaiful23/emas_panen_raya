@extends('layout.user', ['role' => 'admin', 'title' => 'Admin'])

@section('content')
  <style>
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
              <h3 class="box-title">Laporan Keuangan Detail</h3>
            </div>
            <div class="box-body">
              {!! Form::label('start_date', 'Tanggal', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-2">
                <div class="input-group date">
                  <input type="text" class="form-control pull-right" id="datepicker" name="start_date" value="{{ $start_date }}" onchange="changeDate()">
                </div>
              </div>
            </div>
            <div class="box-body">
              @foreach($sub_trx as $rsm)
                @if(sizeof($rsm) != null)
                  <div class="col-sm-3">
                    <h4>Rangkuman transaksi {{ $rsm[0]->trx_type }}</h4>
                    <table class="no-border" style="font-size: 16px">
                      <tbody>
                        <tr>
                          <td width="60%">Total transaksi</td>
                          <td width="1%">:</td>
                          <td width="1%"></td>
                          <td style="text-align: right;">{{ $rsm->sum('count_trans') }} transaksi</td>
                          <!-- <td width="50%"></td> -->
                        </tr>
                        <tr>
                          <td width="60%">Total pemasukan</td>
                          <td width="1%">:</td>
                          <td width="1%">Rp</td>
                          <td style="text-align: right;">{{ printRupiah($rsm->sum('income')) }}</td>
                          <!-- <td width="50%"></td> -->
                        </tr>
                        <tr>
                          <td width="60%">Total HPP</td>
                          <td width="1%">:</td>
                          <td width="1%">Rp</td>
                          <td style="text-align: right;">{{ printRupiah($rsm->sum('hpp')) }}</td>
                          <!-- <td width="50%"></td> -->
                        </tr>
                        <tr>
                          <td width="60%">Total laba/rugi</td>
                          <td width="1%">:</td>
                          <td width="1%">Rp</td>
                          <td style="text-align: right;">{{ printRupiah($rsm->sum('result')) }}</td>
                          <!-- <td width="50%"></td> -->
                        </tr>
                      </tbody>
                    </table>
                  </div>
                @endif
              @endforeach
            </div>
            <hr>
            <div class="box-body" style="overflow-x:scroll;">
            Klik judul tabel untuk mengurutkan data
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>@if($order == 'desc')<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/id/asc/20') }}">@else<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/id/desc/20') }}">@endif <i class="fa fa-sort" aria-hidden="true"></i> ID</a></th>
                  <th>Waktu</th>
                  <th colspan="2">@if($order == 'desc')<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/total_sum_price/asc/20') }}">@else<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/total_sum_price/desc/20') }}">@endif <i class="fa fa-sort" aria-hidden="true"></i> Pemasukan</a></th>
                  <th colspan="2">@if($order == 'desc')<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/hpp/asc/20') }}">@else<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/hpp/desc/20') }}">@endif <i class="fa fa-sort" aria-hidden="true"></i> HPP</a></th>
                  <th colspan="2">@if($order == 'desc')<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/status_fee/asc/20') }}">@else<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/status_fee/desc/20') }}">@endif <i class="fa fa-sort" aria-hidden="true"></i> Biaya Lain</a></th>
                  <th colspan="2">@if($order == 'desc')<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/profit/asc/20') }}">@else<a href="{{ url('admin/profit/detail/' . $start_date . '/' . $end_date . '/profit/desc/20') }}">@endif <i class="fa fa-sort" aria-hidden="true"></i> Laba/Rugi</a></th>
                  <th>Detail Barang</th>
                  <th>Ubah Transaksi</th>
                </tr>
                </thead>
                <tbody id="table-transaction">
                  <?php $i = 0 ?>
                  @foreach($transactions as $transaction)
                    <tr style="@if($i%2 == 0) background-color: #DEE5D4; @endif">
                      <td>{{ $transaction->id }}</td>
                      <td>{{ $transaction->created_at }}</td>
                      <td style="border-right-color: transparent;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->total_sum_price) }}</td>
                      <td style="border-right-color: transparent;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->hpp) }}</td>
                      <td style="border-right-color: transparent;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->status_fee) }}</td>
                      <td style="border-right-color: transparent;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->profit) }}</td>
                      <td>
                        <ol>
                          @foreach($transaction->details as $detail)
                            <li>{{ $detail->good_unit->good->fullName() }}</li>
                          @endforeach
                        </ol>
                      </td>
                      <td class="center"><a href="{{ url('admin/transaction/' . $transaction->id . '/edit') }}"><i class="fa fa-pencil-square-o tosca" aria-hidden="true"></i></a></td>
                      <?php $i++ ?>
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
@endsection

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

      $("#search-input").keyup( function(e){
        if(e.keyCode == 13)
        {
          ajaxFunction();
        }
      });

      $("#search-btn").click(function(){
          ajaxFunction();
      });
    });

    function changeDate()
    {
      window.location = window.location.origin + '/admin/profit/detail/' + $("#datepicker").val() + '/' + $("#datepicker").val() + '/{{ $sort }}/{{ $order }}/{{ $pagination }}';
    }

    function advanceSearch()
    {
      var show        = $('#show').val();

      window.location = window.location.origin + '/admin/profit/detail/{{ $start_date }}/{{ $start_date }}/{{ $sort }}/{{ $order }}/' + show;
    }
  </script>
@endsection
