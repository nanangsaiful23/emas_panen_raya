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
            <!-- <div class="box-body">
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
            </div> -->
            <div class="box-body">
              <div class="chart" id="bar-chart" style="height: 300px;"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @section('js-addon')
    <script src="{{asset('assets/bower_components/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('assets/bower_components/morris.js/morris.min.js')}}"></script>
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

        var bar = new Morris.Bar({
          element: 'bar-chart',
          resize: true,
          data: [
            @foreach($transactions as $transaction)
              {y: '{{ $transaction->month . "-" . $transaction->year }}', a: "{{ $transaction->income }}", b: "{{ $transaction->hpp }}", c: "{{ $transaction->result }}"},
            @endforeach
          ],
          barColors: ['#00a65a', '#f56954', '#00CCDD'],
          xkey: 'y',
          ykeys: ['a', 'b', 'c'],
          labels: ['Pemasukan', 'HPP', 'Laba/Rugi'],
          hideHover: 'auto'
        });
      });


      function changeDate()
      {
        window.location = window.location.origin + '/admin/profit/{{ $type}}/' + $("#datepicker").val() + '/' + $("#datepicker2").val() + '/' + $("#show").val();
      }
    </script>
  @endsection
@endsection