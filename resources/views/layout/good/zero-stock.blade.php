<style type="text/css">
  th
  {
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
            <h3 class="box-title">{{ $default['page_name'] }}</h3>
          </div>
          <div class="box-body" style="overflow-x:scroll">
            <div class="form-group col-sm-12" style="margin-top: 10px;">
              {!! Form::label('category_id', 'Kategori', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-5">
                {!! Form::select('category_id', getCategories(), $category_id, ['class' => 'form-control select2', 'style'=>'width: 100%', 'id' => 'category_id', 'onchange' => 'advanceSearch()']) !!}
              </div>
              {!! Form::label('distributor', 'Distributor', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-5">
                {!! Form::select('distributor_id', getDistributorLists(), $distributor_id, ['class' => 'form-control select2', 'style'=>'width: 100%', 'id' => 'distributor_id', 'onchange' => 'advanceSearch()']) !!}
              </div>
            </div>
            <div class="form-group col-sm-12" style="margin-top: 10px;">
              <!-- {!! Form::label('stock', 'Stock', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-3">
                {!! Form::select('stock', ['0' => '0', '3' => '3', '5' => '5', '10' => '10'], $stock, ['class' => 'form-control select2', 'style'=>'width: 100%', 'id' => 'stock', 'onchange' => 'advanceSearch()']) !!}
              </div> -->
              <!-- {!! Form::label('location', 'Lokasi', array('class' => 'col-sm-1 control-label')) !!}
              <div class="col-sm-3">
                {!! Form::select('location', getDistributorLocations(), $location, ['class' => 'form-control select2', 'style'=>'width: 100%', 'id' => 'location', 'onchange' => 'advanceSearch()']) !!}
              </div> -->
            </div>
          </div>
          <div class="box-body" style="overflow-x:scroll; color: black !important">
            @if(\Auth::user()->email == 'admin')
              {!! Form::model(old(),array('url' => route($role . '.zeroStock.export'), 'enctype'=>'multipart/form-data', 'method' => 'POST', 'class' => 'form-horizontal')) !!}
                {!! Form::submit('EXPORT', ['class' => 'btn form-control'])  !!}
            @endif
            <h3>Total barang: {{ sizeof($goods) }}</h3>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Kategori</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Berat</th>
                <th>Kadar</th>
                @if(\Auth::user()->email == 'admin')
                  <th>Loading Terakhir</th>
                  <th>Harga Beli Terakhir</th>
                @endif
                <th>Stock</th>
                @if(\Auth::user()->email == 'admin')
                  <th>Export</th>
                  <th>Hapus Barang</th>
                @endif
              </tr>
              </thead>
              <tbody id="table-good">
                @foreach($goods as $good)
                  <tr>
                    <td>{{ $good->category_name }}</td>
                    <td>{{ $good->code }}</td>
                    <td>{{ $good->name }}</td>
                    <td>{{ $good->weight }} gram</td>
                    <td>{{ $good->percentage_name }}</td>
                    @if(\Auth::user()->email == 'admin')
                      <td style="text-align: center;">{{ $good->getLastBuy() == null ? "" : displayDate($good->getLastBuy()->good_loading->loading_date) }}</td>
                      <td style="text-align: right;">{{ $good->getLastBuy() == null ? "" : showRupiah($good->getLastBuy()->price) }}</td>
                    @endif
                    <td style="text-align: center;">{{ $good->getStock() }}</td>
                    @if(\Auth::user()->email == 'admin')
                      <td style="text-align: center;">
                        <input type="checkbox" name="exports[]" value="{{ $good->id }}" checked="checked">
                      </td>
                      </form>
                      <td style="text-align: center;">
                        @if($good->getStock() == 0)
                          <button type="button" class="no-btn" data-toggle="modal" data-target="#modal-danger-{{$good->id}}"><i class="fa fa-times red" aria-hidden="true"></i></button>

                          @include('layout' . '.delete-modal', ['id' => $good->id, 'data' => $good->name, 'formName' => 'delete-form-' . $good->id])

                          <form id="delete-form-{{$good->id}}" action="{{ url($role . '/good/' . $good->id . '/delete') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                          </form>
                        @endif
                      </td>
                    @endif
                  </tr>
                @endforeach
              </tbody>
            </table>
            @if(\Auth::user()->email == 'admin')  
              {!! Form::close() !!}
            @endif
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

    function advanceSearch()
    {
      window.location = window.location.origin + '/{{ $role }}/good/zeroStock/' + $('#category_id').val() + '/{{ $location }}/' + $('#distributor_id').val() + '/0/all';
    }
  </script>
@endsection