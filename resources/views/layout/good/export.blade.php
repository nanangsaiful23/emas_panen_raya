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

<link rel="stylesheet" href="{{asset('assets/plugins/iCheck/all.css')}}">

<div class="content-wrapper">

  @include('layout' . '.alert-message', ['type' => $default['type'], 'data' => $default['data'], 'color' => $default['color']])

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> {{ $default['page_name'] }}</h3>
          </div>
          <div class="box-body">
            {!! Form::model(old(),array('url' => route($role . '.good.export'), 'enctype'=>'multipart/form-data', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'export-form')) !!}
              <div class="form-group col-sm-12" style="margin-top: 10px;">
                {!! Form::label('category', 'Kategori', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-3">
                  {!! Form::select('category', getCategoriesWoNull(), null, ['class' => 'form-control select2', 'style'=>'width: 100%']) !!}
                </div>
                {!! Form::label('start_date', 'Tanggal Awal Barang Masuk', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-2">
                  <div class="input-group date">
                    <input type="text" class="form-control pull-right" id="datepicker" name="start_date" value="{{ date('Y-m-d',strtotime('-10 day')) }}">
                  </div>
                </div>
              </div>
              <div class="form-group col-sm-12" style="margin-top: 10px;">
                {!! Form::label('sort', 'Urutan Data', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-3">
                  {!! Form::select('sort', ['goods.created_at' => 'Tanggal Masuk Barang', 'goods.name' => 'Nama Barang'], null, ['class' => 'form-control select2', 'style'=>'width: 100%']) !!}
                </div>
                {!! Form::label('end_date', 'Tanggal Akhir Barang Masuk', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-2">
                  <div class="input-group date">
                    <input type="text" class="form-control pull-right" id="datepicker2" name="end_date" value="{{ date('Y-m-d') }}">
                  </div>
                </div>
              </div>
              <div class="form-group col-sm-12" style="margin-top: 10px;">
                {!! Form::label('status', 'Status Barang', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-3">
                  {!! Form::select('status', getStatusOther(), null, ['class' => 'form-control select2', 'style'=>'width: 100%']) !!}
                </div>
                <!-- <label for="all_date" class="col-sm-3 control-label"> Semua tanggal</label>
                <div class="col-sm-2">
                  <input type="checkbox" id="all_date" name="all_date" value="1" class="flat-red" checked>
                </div> -->
              </div>
              {{ csrf_field() }}
              <div class="form-group col-sm-12" style="margin-top: 10px;">
                <div onclick="event.preventDefault(); exportData()" class='btn btn-success btn-flat btn-block form-control' data-dismiss="modal">Export Data Barang</div>
              </div>
            {!! Form::close() !!}
          </div>
        </div>
        <div id="loader"></div>

        <div style="display:none;" id="myDiv" class="animate-bottom">
          <h2>Data Berhasil di Export</h2>
          <p>Silahkan buka file pada folder download</p>
        </div>
      </div>
    </div>
  </section>
</div>

@section('js-addon')

  <script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
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

        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass   : 'iradio_flat-green'
        })

        document.getElementById("loader").style.display = "none";
    }) ;

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
