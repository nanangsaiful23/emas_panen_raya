@extends('layout.user', ['role' => 'admin', 'title' => 'Admin'])

@section('content')
  <style type="text/css">
    table, th, td
    {
      border: solid 2px black !important;
    }

    .no-border td
    {
      border: none !important;
    }

    th 
    {
      text-align: center;
    }
  </style>

  <div class="content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Laporan Penjualan {{ displayDate($start_date) . ' sampai ' . displayDate($end_date) }}</h3>
            </div>
            <div class="box-body">
              {!! Form::label('show', 'Show', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-1">
                {!! Form::select('show', getPaginations(), $pagination, ['class' => 'form-control', 'style'=>'width: 100%', 'id' => 'show', 'onchange' => 'changeDate()']) !!}
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
            <div class="box-body" style="overflow-x:scroll; color: black !important">
              <h4>Rangkuman transaksi</h4>
              <table class="no-border" style="font-size: 16px">
                <tbody>
                  <tr>
                    <td width="13%">Total transaksi</td>
                    <td width="1%">:</td>
                    <td width="1%"></td>
                    <td style="text-align: right;">{{ $sub_total->sum('count_trans') }} transaksi</td>
                    <td width="76%"></td>
                  </tr>
                  <tr>
                    <td width="13%">Total pemasukan</td>
                    <td width="1%">:</td>
                    <td width="1%">Rp</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('income')) }}</td>
                    <td width="76%"></td>
                  </tr>
                  <tr>
                    <td width="13%">Total HPP</td>
                    <td width="1%">:</td>
                    <td width="1%">Rp</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('hpp')) }}</td>
                    <td width="76%"></td>
                  </tr>
                  <tr>
                    <td width="13%">Total laba/rugi</td>
                    <td width="1%">:</td>
                    <td width="1%">Rp</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('result')) }}</td>
                    <td width="76%"></td>
                  </tr>
                </tbody>
              </table>
              <h4 style="margin-top: 20px">Detail transaksi</h4>
              <table id="example1" class="table table-bordered table-striped">
                <div id="renderField" class="col-sm-12">
                  @if($pagination != 'all')
                    {{ $transactions->render() }}
                  @endif
                </div>
                <thead>
                  <tr style="font-size: 15px">
                    <th width="10%">Tanggal</th>
                    <th width="10%">Jumlah Transaksi</th>
                    <th width="10%">Jumlah Gram Emas</th>
                    <th width="10%" colspan="2">Pemasukan</th>
                    <th width="10%" colspan="2">HPP</th>
                    <th width="10%" colspan="2">Laba/Rugi</th>
                    <th width="3%">Detail</th>
                  </tr>
                </thead>
                <tbody id="table-good">
                  <?php $i = 0; ?>
                  @foreach($transactions as $transaction)
                    <tr style="text-align: right; @if($i%2 == 0) background-color: #DEE5D4; @endif">
                      <td>{{ displayDate($transaction->date) }}</td>
                      <td>{{ $transaction->count_trans }} transaksi</td>
                      <td>{{ $transaction->count_gram }} gram</td>
                      <td style="border-right-color: transparent !important;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->income) }}</td>
                      <td style="border-right-color: transparent !important;">Rp</td>
                      <td style="text-align: right;">{{ printRupiah($transaction->hpp) }}</td>
                      <td style="border-right-color: transparent !important;">Rp</td>
                      <td style="text-align: right;" @if($transaction->result <=0 ) style="background-color: red" @endif>{{ printRupiah($transaction->result) }}</td>
                      <td style="text-align: center !important"><a href="{{ url('admin/profit/detail/' . $transaction->date . '/' . $transaction->date . '/id/asc/20') }}"><i class="fa fa-hand-o-right tosca" aria-hidden="true"></i></a></td>
                    </tr>
                    <?php $i++ ?>
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
        window.location = window.location.origin + '/admin/profit/{{ $type}}/' + $("#datepicker").val() + '/' + $("#datepicker2").val() + '/{{ $sort }}/{{ $order }}/' + $("#show").val();
      }
    </script>
  @endsection
@endsection