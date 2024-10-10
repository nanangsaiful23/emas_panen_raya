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
              <h3 class="box-title">{{ $default['page_name'] }}</h3>
            </div>
            <div class="box-body" style="color: black !important">
              <table class="no-border" style="font-size: 16px">
                <tbody>
                  <tr>
                    <td width="13%">Total transaksi</td>
                    <td width="1%">:</td>
                    <td width="1%"></td>
                    <td style="text-align: right;">{{ $sub_total->sum('count_trans') }} transaksi</td>
                    <td width="73%"></td>
                  </tr>
                  <tr>
                    <td width="13%">Total pemasukan</td>
                    <td width="1%">:</td>
                    <td width="1%">Rp</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('income')) }}</td>
                    <td width="73%"></td>
                  </tr>
                  <tr>
                    <td width="13%">Total HPP</td>
                    <td width="1%">:</td>
                    <td width="1%">Rp</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('hpp')) }}</td>
                    <td width="73%"></td>
                  </tr>
                  <tr>
                    <td width="13%">Total laba/rugi</td>
                    <td width="1%">:</td>
                    <td width="1%">Rp</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('result')) }}</td>
                    <td width="73%"></td>
                  </tr>
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
        window.location = window.location.origin + '/admin/profit/{{ $type}}/' + $("#datepicker").val() + '/' + $("#datepicker2").val() + '/' + $("#show").val();
      }
    </script>
  @endsection
@endsection