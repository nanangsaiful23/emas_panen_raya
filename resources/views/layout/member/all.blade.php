<div class="content-wrapper">

  @include('layout' . '.alert-message', ['type' => $default['type'], 'data' => $default['data'], 'color' => $default['color']])

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">List Member</h3>
            @include('layout.search-form')
          </div>
          <div class="box-body">
            {!! Form::label('start_date', 'Tanggal Awal', array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-2">
              <div class="input-group date">
                <input type="text" class="form-control pull-right" id="datepicker" name="start_date" value="{{ $start_date }}" onchange="changeDate()">
              </div>
            </div>
            {!! Form::label('end_date', 'Tanggal Akhir', array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-2">
              <div class="input-group date">
                <input type="text" class="form-control pull-right" id="datepicker2" name="end_date" value="{{ $end_date }}" onchange="changeDate()">
              </div>
            </div>
          </div>
          <div class="box-body" style="overflow-x:scroll; color: black !important">
            @if(\Auth::user()->email == 'admin')
              {!! Form::model(old(),array('url' => route($role . '.member.export'), 'enctype'=>'multipart/form-data', 'method' => 'POST', 'class' => 'form-horizontal')) !!}
                {!! Form::submit('EXPORT DATA MEMBER', ['class' => 'btn form-control'])  !!}
              </form>
            @endif
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>ID</th>
                <th><a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/name/asc/15') }}"><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i></a> <a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/name/desc/15') }}"><i class="fa fa-sort-alpha-desc" aria-hidden="true"></i></a> Nama</th>
                <th><a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/address/asc/15') }}"><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i></a> <a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/address/desc/15') }}"><i class="fa fa-sort-alpha-desc" aria-hidden="true"></i></a> Alamat</th>
                <th><a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/village/asc/15') }}"><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i></a> <a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/village/desc/15') }}"><i class="fa fa-sort-alpha-desc" aria-hidden="true"></i></a> Desa</th>
                <th><a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/subdistrict/asc/15') }}"><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i></a> <a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/subdistrict/desc/15') }}"><i class="fa fa-sort-alpha-desc" aria-hidden="true"></i></a> Kecamatan</th>
                <th><a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/phone_number/asc/15') }}"><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i></a> <a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/phone_number/desc/15') }}"><i class="fa fa-sort-numeric-desc" aria-hidden="true"></i></a> No Telephone</th>
                <th>Tanggal Lahir</th>
                <th>No KTP/SIM</th>
                <th>Total Transaksi</th>
                <th>Riwayat Transaksi</th>
                <!-- <th><a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/total_transaction/asc/15') }}"><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i></a> <a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/total_transaction/desc/15') }}"><i class="fa fa-sort-numeric-desc" aria-hidden="true"></i></a> Total Transaksi</th> -->
                <!-- <th><a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/total_sum_price/asc/15') }}"><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i></a> <a href="{{ url($role . '/member/' . $start_date . '/' . $end_date . '/total_sum_price/desc/15') }}"><i class="fa fa-sort-numeric-desc" aria-hidden="true"></i></a> Riwayat Transaksi</th> -->
                <th>Total Point</th>
                <th class="center">Detail Data Member</th>
                <th class="center">Ubah Data Member</th>
                @if($role == 'admin')
                  <th class="center">Hapus Member</th>
                @endif
              </tr>
              </thead>
              <tbody id="table-good">
                @foreach($members as $member)
                  <tr>
                    <td>
                      {{ $member->id }}
                    </td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->address == null ? '-' : $member->address }}</td>
                    <td>{{ $member->village == null ? '-' : $member->village }}</td>
                    <td>{{ $member->subdistrict == null ? '-' : $member->subdistrict }}</td>
                    <td>{{ $member->phone_number == null ? '-' : $member->phone_number }}</td>
                    <td>{{ $member->birth_date == null ? '-' : displayDate($member->birth_date) }}</td>
                    <td>{{ $member->no_ktp == null ? '-' : $member->no_ktp }}</td>
                    <td class="center">Jumlah transaksi: {{ $member->transaction->count() }}<br><a href="{{ url($role . '/member/' . $member->id . '/transaction/2019-01-01/' . date('Y-m-d') . '/20') }}"><i class="fa fa-hand-o-right pink" aria-hidden="true"></i> detail</a></td>
                    <td class="center">Total transaksi: {{ showRupiah($member->transaction->sum('total_sum_price')) }}<br>{{ $member->getTotalGramTransaction() }} gram<br><a href="{{ url($role . '/member/' . $member->id . '/transaction/2019-01-01/' . date('Y-m-d') . '/20') }}"><i class="fa fa-hand-o-right pink" aria-hidden="true"></i> detail</a></td>
                    <td>
                      <b>Sisa Point: {{ $member->getCurrentPoint() }}</b><br>
                      Total Point: {{ $member->getTotalPoint() }}<br>
                      Total Point Ditukar: {{ $member->getRedeemPoint() }}<br>
                      <a href="{{ url($role . '/member/' . $member->id . '/point/2024-01-01/' . date('Y-m-d') . '/20') }}"><i class="fa fa-hand-o-right green" aria-hidden="true"></i> detail</a>
                      <a href="{{ url($role . '/member/' . $member->id . '/redeem') }}" class="btn">TUKAR POINT</a>
                    </td>
                    <td class="center"><a href="{{ url($role . '/member/' . $member->id . '/detail') }}"><i class="fa fa-hand-o-right tosca" aria-hidden="true"></i></a></td>
                    <td class="center"><a href="{{ url($role . '/member/' . $member->id . '/edit') }}"><i class="fa fa-file orange" aria-hidden="true"></i></a></td>
                    @if($role == 'admin')
                      <td class="center">
                        <button type="button" class="no-btn center" data-toggle="modal" data-target="#modal-danger-{{$member->id}}"><i class="fa fa-times" aria-hidden="true" style="color: red !important"></i></button>

                        @include('layout' . '.delete-modal', ['id' => $member->id, 'data' => $member->name, 'formName' => 'delete-form-' . $member->id])

                        <form id="delete-form-{{$member->id}}" action="{{ url($role . '/member/' . $member->id . '/delete') }}" method="POST" style="display: none;">
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
                  {{ $members->render() }}
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
      window.location = window.location.origin + '/{{ $role }}/member/' + $("#datepicker").val() + '/' + $("#datepicker2").val() + '/{{ $sort }}/{{ $order }}/{{ $pagination }}';
    }

    function ajaxFunction()
    {
      $.ajax({
        url: "{!! url($role . '/member/searchByName/') !!}/" + $("#search-input").val(),
        success: function(result){
          console.log(result);
          var htmlResult = '<thead><tr><th>ID</th><th>Nama</th><th>Alamat</th><th>Kecamatan</th><th>Desa</th><th>No Telephone</th><th>Tempat, Tanggal Lahir</th><th>No KTP/SIM</th><th>Total Transaksi</th><th>Riwayat Transaksi</th><th>Total Point</th><th class="center">Detail Data Member</th><th class="center">Ubah Data Member</th>@if($role == "admin")<th class="center">Hapus Member</th>@endif </tr></thead><tbody>';
          if(result != null)
          {
            var r = result.members;
            for (var i = 0; i < r.length; i++) {
              htmlResult += "<tr><td>" + r[i].id + "</td>";
              htmlResult += "<td>" + r[i].name + "</td>";
              if(r[i].address == null)
                htmlResult += "<td>-</td>";
              else
                htmlResult += "<td>" + r[i].address + "</td>";
              if(r[i].subdistrict == null)
                htmlResult += "<td>-</td>";
              else
                htmlResult += "<td>" + r[i].subdistrict + "</td>";
              if(r[i].village == null)
                htmlResult += "<td>-</td>";
              else
                htmlResult += "<td>" + r[i].village + "</td>";
              if(r[i].phone_number == null)
                htmlResult += "<td>-</td>";
              else
                htmlResult += "<td>" + r[i].phone_number + "</td>";
              if(r[i].birth_date == null)
                htmlResult += "<td>-</td>";
              else
                htmlResult += "<td>" + r[i].birth_date + "</td>";
              if(r[i].no_ktp == null)
                htmlResult += "<td>-</td>";
              else
                htmlResult += "<td>" + r[i].no_ktp + "</td>";
              htmlResult += "<td class=\"center\">Jumlah transaksi: " + r[i].total_transaction + "<br><a href=\"" + window.location.origin + "/{{ $role }}/member/" + r[i].id + "/transaction/2019-01-01/{{ date('Y-m-d')}}/20\"> <i class=\"fa fa-hand-o-right pink\" aria-hidden=\"true\"></i> detail</a></td>";
              htmlResult += "<td class=\"center\">Total transaksi: " + r[i].sum_transaction + "<br>" + r[i].total_gram + " gram<br><a href=\"" + window.location.origin + "/{{ $role }}/member/" + r[i].id + "/transaction/2019-01-01/{{ date('Y-m-d')}}/20\"> <i class=\"fa fa-hand-o-right pink\" aria-hidden=\"true\"></i> detail</a></td>";
              htmlResult += "<td class=\"center\"><b>Sisa Point: " + r[i].current_point + "</b><br>Total Point: " + r[i].total_point + "<br>Total Point Ditukar: " + r[i].redeem_point + "<br><a href=\"" + window.location.origin + "/{{ $role }}/member/" + r[i].id + "/point/2024-01-01/{{ date('Y-m-d')}}/20\"> <i class=\"fa fa-hand-o-right pink\" aria-hidden=\"true\"></i> detail</a><a href=\"" + window.location.origin + "/{{ $role }}/member/" + r[i].id + "/redeem\" class=\"btn\"> TUKAR POINT</a></td>";
              htmlResult += "<td class=\"center\"><a href=\"" + window.location.origin + "/{{ $role }}/member/" + r[i].id + "/detail\"> <i class=\"fa fa-hand-o-right pink\" aria-hidden=\"true\"></i></a></td><td class=\"center\"><a href=\"" + window.location.origin + "/{{ $role }}/member/" + r[i].id + "/edit\"> <i class=\"fa fa-file pink\" aria-hidden=\"true\"></i></a></td>@if($role == 'admin')<td><a href=\"" + window.location.origin + "/" + '{{ $role }}' + "/member/" + r[i].id + "/delete\" onclick=\"event.preventDefault(); document.getElementById('delete-form-" + r[i].id + "').submit();\"><i class=\"fa fa-times red\"></i></a><form id='delete-form-" + r[i].id + "' action=\"" + window.location.origin + "/" + '{{ $role }}' + "/member/" + r[i].id + "/delete\" method=\"POST\" style=\"display: none;\">" + '{{ csrf_field() }}' + '{{ method_field("DELETE") }}' + "</form></td>@endif";
              htmlResult += "</tr>";
            }
          }

          htmlResult += "</tbody>";

          $("#example1").html(htmlResult);
        },
        error: function(){
            console.log('error');
        }
      });
    }
  </script>
@endsection