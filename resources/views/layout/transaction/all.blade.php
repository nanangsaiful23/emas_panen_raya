<style type="text/css">
  .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
   background-color: #E1EEDD;
}

  #loader {
    position: absolute;
    left: 50%;
    top: 50%;
    z-index: 1;
    width: 120px;
    height: 120px;
    margin: -76px 0 0 -76px;
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
  }

  @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  /* Add animation to "page content" */
  .animate-bottom {
    position: relative;
    -webkit-animation-name: animatebottom;
    -webkit-animation-duration: 1s;
    animation-name: animatebottom;
    animation-duration: 1s
  }

  @-webkit-keyframes animatebottom {
    from { bottom:-100px; opacity:0 } 
    to { bottom:0px; opacity:1 }
  }

  @keyframes animatebottom { 
    from{ bottom:-100px; opacity:0 } 
    to{ bottom:0; opacity:1 }
  }

  #myDiv {
    display: none;
    text-align: center;
  }
</style>

<div class="content-wrapper">

  @include('layout' . '.alert-message', ['type' => $default['type'], 'data' => $default['data'], 'color' => $default['color']])

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Daftar transaksi</h3>
            <!-- @include('layout.search-form') -->
          </div>
          <div class="box-body">
            {!! Form::model(old(),array('url' => route($role . '.transaction.export'), 'enctype'=>'multipart/form-data', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'export-form')) !!}
              <div class="col-sm-12">
                {!! Form::label('show', 'Show', array('class' => 'col-sm-1 control-label')) !!}
                <div class="col-sm-1">
                  {!! Form::select('show', getPaginations(), $pagination, ['class' => 'form-control', 'style'=>'width: 100%', 'id' => 'show', 'onchange' => 'advanceSearch()']) !!}
                </div>
                @if($role == 'admin')
                  {!! Form::label('user_id', 'PIC', array('class' => 'col-sm-1 control-label')) !!}
                  <div class="col-sm-2">
                    {!! Form::select('user_id', getUsers(), $role_user . '/' . $role_id, ['class' => 'form-control select2', 'style'=>'width: 100%', 'id' => 'user_id', 'onchange' => 'advanceSearch()']) !!}
                  </div>
                @endif
                {!! Form::label('trx_type', 'Tipe Transaksi', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2">
                  {!! Form::select('trx_type', getTrxTypes(), $trx_type, ['class' => 'form-control select2', 'style'=>'width: 100%', 'id' => 'trx_type', 'onchange' => 'advanceSearch()']) !!}
                </div>
              </div>
              <div class="col-sm-12" style="margin-top: 10px">
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
            </div>
            {{ csrf_field() }}
            <div class="form-group col-sm-12" style="margin-top: 10px;">
              <div onclick="event.preventDefault(); exportData()" class='btn btn-success btn-flat btn-block form-control' data-dismiss="modal">Export Data Transaksi</div>
            </div>
          {!! Form::close() !!}
          <div id="loader"></div>

          <div style="display:none;" id="myDiv" class="animate-bottom">
            <h2>Data Berhasil di Export</h2>
            <p>Silahkan buka file pada folder download</p>
          </div>

          <div class="box-body">
            <h3>Total transaksi: {{ showRupiah($sub_total->sum('total_sum_price')) }}</h3>
          </div>

          @include('layout.transaction.all-form', ['name' => 'Lunas', 'transactions' => $transactions['cash'], 'color' => '#E5F9DB', 'total_sum_price' => $sub_total->sum('total_sum_price'), 'total_discount_price' => $sub_total->sum('total_discount_price'), 'discount_item' => $sub_total->sum('discount_item') ])
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

      $("#search-input").keyup( function(e){
        if(e.keyCode == 13)
        {
          ajaxFunction();
        }
      });

      $("#search-btn").click(function(){
          ajaxFunction();
      });

      document.getElementById("loader").style.display = "none";
    });

    function changeDate()
    {
      window.location = window.location.origin + '/{{ $role }}/transaction/{{ $role_user }}/{{ $role_id }}/{{ $trx_type }}/' + $("#datepicker").val() + '/' + $("#datepicker2").val() + '/{{ $pagination }}';
    }

    function advanceSearch()
    {
      var show        = $('#show').val();
      var user_id     = $('#user_id').val();
      var trx_type    = $('#trx_type').val();

      @if($role == 'cashier')
        user_id = 'all/all';
      @endif
      window.location = window.location.origin + '/{{ $role }}/transaction/' + user_id + '/' + trx_type + '/{{ $start_date }}/{{ $end_date }}/' + show;
    }

    var myVar;

    function showPage() {
      document.getElementById("loader").style.display = "none";
      document.getElementById("myDiv").style.display = "block";
    }

    function exportData()
    {
      document.getElementById('export-form').submit();
      document.getElementById("loader").style.display = "block";
      myVar = setTimeout(showPage, 3000);
    }
  </script>
@endsection
