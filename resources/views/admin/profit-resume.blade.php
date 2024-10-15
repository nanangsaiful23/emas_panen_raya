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
            <div class="box-body col-sm-6" style="color: black !important">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Nama</th>
                  <th>Nominal</th>
                </tr>
                </thead>
                <tbody id="table-good">
                  <tr>
                    <td>Penjualan</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('income')) }}</td>
                  </tr>
                  <tr>
                    <td>Harga Penjualan Pokok</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('hpp')) }}</td>
                  </tr>
                  <tr style="font-weight: bold;">
                    <td>Laba (rugi) kotor</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('result')) }}</td>
                  </tr>
                  <tr>
                    <td colspan="5"></td>
                  </tr>
                  <!-- <tr style="font-weight: bold;">
                    <td>Pendapatan lain-lain</td>
                  </tr> -->
                  <tr style="font-weight: bold;">
                    <td>Biaya lain-lain</td>
                    <td style="text-align: right;">{{ printRupiah($transactions->sum('nominal')) }}</td>
                  </tr>
                  <tr style="font-weight: bold; background-color: yellow;">
                    <td>Total Akhir</td>
                    <td style="text-align: right;">{{ printRupiah($sub_total->sum('result') - $transactions->sum('nominal')) }}</td>
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