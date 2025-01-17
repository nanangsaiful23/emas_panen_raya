<style type="text/css">
  .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
   background-color: #E1EEDD;
}
</style>

<div class="content-wrapper">

  @include('layout' . '.alert-message', ['type' => $default['type'], 'data' => $default['data'], 'color' => $default['color']])

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> {{ $default['page_name'] }}</h3>
          </div>
          <div class="box-body" style="overflow-x:scroll">
            {!! Form::model(old(),array('url' => route($role . '.good.export'), 'enctype'=>'multipart/form-data', 'method' => 'POST', 'class' => 'form-horizontal')) !!}
              <div class="form-group col-sm-12" style="margin-top: 10px;">
                {!! Form::label('category', 'Kategori', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-3">
                  {!! Form::select('category', getCategories(), null, ['class' => 'form-control select2', 'style'=>'width: 100%']) !!}
                </div>
                {!! Form::label('start_date', 'Tanggal Awal Barang Masuk', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-2">
                  <div class="input-group date">
                    <input type="text" class="form-control pull-right" id="datepicker" name="start_date">
                  </div>
                </div>
              </div>
              <div class="form-group col-sm-12" style="margin-top: 10px;">
                {!! Form::label('sort', 'Urutan Data', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-3">
                  {!! Form::select('sort', ['goods.name' => 'Nama Barang', 'goods.created_at' => 'Tanggal Masuk Barang'], null, ['class' => 'form-control select2', 'style'=>'width: 100%']) !!}
                </div>
                {!! Form::label('end_date', 'Tanggal Akhir Barang Masuk', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-2">
                  <div class="input-group date">
                    <input type="text" class="form-control pull-right" id="datepicker2" name="end_date">
                  </div>
                </div>
              </div>
              <div class="form-group col-sm-12" style="margin-top: 10px;">
                {!! Form::label('status', 'Status Barang', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-3">
                  {!! Form::select('status', getStatusOther(), null, ['class' => 'form-control select2', 'style'=>'width: 100%']) !!}
                </div>
              </div>
              {{ csrf_field() }}
              {!! Form::submit('EXPORT DATA BARANG', ['class' => 'btn form-control'])  !!}
            {!! Form::close() !!}
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
  </script>
@endsection
