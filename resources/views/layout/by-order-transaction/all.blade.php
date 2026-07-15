<div class="content-wrapper">

  @include('layout' . '.alert-message', ['type' => $default['type'], 'data' => $default['data'], 'color' => $default['color']])

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> {{ $default['page_name'] }}</h3>
            @include('layout.search-form')
          </div>
          <div class="box-body">
            
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
          <div class="box-body" style="overflow-x:scroll; color: black !important">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Model/Nama Barang</th>
                <th>Tanggal Order</th>
                <th>Tanggal Jadi</th>
                <th>Status</th>
                <th class="center">Print</th>
                <th class="center">Transaksi Emas</th>
                <th class="center">Detail</th>
                <th class="center">Ubah</th>
                @if($role == 'admin')
                  <th class="center">Hapus</th>
                @endif
              </tr>
              </thead>
              <tbody id="table-good">
                @foreach($orders as $order)
                  <tr>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->address }}</td>
                    <td>{{ $order->phone_number }}</td>
                    <td>{{ $order->model }}</td>
                    <td>{{ displayDate($order->order_date) }}</td>
                    <td>{{ displayDate($order->finish_date) }}</td>
                    <td>@if($order->good_unit_id != null) Sudah terjual @else On progress @endif</td>
                    <td class="center"><a href="{{ url($role . '/by-order-transaction/' . $order->id . '/print') }}" target="_blank()"><i class="fa fa-print tosca" aria-hidden="true"></i></a></td>
                    <td class="center"><a href="{{ url($role . '/by-order-transaction/' . $order->id . '/transaction') }}"><i class="fa fa-handshake-o tosca" aria-hidden="true"></i></a></td>
                    <td class="center"><a href="{{ url($role . '/by-order-transaction/' . $order->id . '/detail') }}"><i class="fa fa-hand-o-right tosca" aria-hidden="true"></i></a></td>
                    <td class="center"><a href="{{ url($role . '/by-order-transaction/' . $order->id . '/edit') }}"><i class="fa fa-file orange" aria-hidden="true"></i></a></td>
                    @if($role == 'admin')
                      <td class="center">
                        <button type="button" class="no-btn center" data-toggle="modal" data-target="#modal-danger-{{$order->id}}"><i class="fa fa-times" aria-hidden="true" style="color: red !important"></i></button>

                        @include('layout' . '.delete-modal', ['id' => $order->id, 'data' => $order->name, 'formName' => 'delete-form-' . $order->id])

                        <form id="delete-form-{{$order->id}}" action="{{ url($role . '/by-order-transaction/' . $order->id . '/delete') }}" method="POST" style="display: none;">
                          {{ csrf_field() }}
                          {{ method_field('DELETE') }}
                        </form>
                      </td>
                    @endif
                  </tr>
                @endforeach
              </tbody>
              <div id="renderField">
                @if($pagination != 'all')
                  {{ $orders->render() }}
                @endif
              </div>
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
      window.location = window.location.origin + '/{{ $role }}/by-order-transaction/' + $("#datepicker").val() + '/' + $("#datepicker2").val() + '/{{ $pagination }}';
    }
  </script>
@endsection