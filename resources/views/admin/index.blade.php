@extends('layout.user', ['role' => 'admin', 'title' => 'Admin'])

@section('content')

  <style type="text/css">
    .box
    {
      border: solid 2px white;
    }

    .small-box:hover, .small-box>a
    {
      color: black !important;
    }
  </style>
  <div class="content-wrapper">
    @if(sizeof($server_payment) > 0)
      <div class="alert alert-danger alert-dismissible" id="message">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-warning"></i> Guna pelayanan yang maksimal, harap melakukan pembayaran tagihan server maksimal tanggal 20 pada setiap bulannya.<br>Status pembayaran saat ini adalah: tertunda {{ $server_payment->count() }} bulan sejak {{ $server_payment[0]->month_due }}</h4>
      </div>
    @endif
    <section class="content-header">
      <h1>
        Hallo, {{ \Auth::user()->name }}<br>
        Selamat datang di aplikasi {{ config('app.name') }}<br>
        <small>Anda login sebagai Admin</small>
      </h1>
    </section>
    <section class="content">
      <div class="row">
        @if(sizeof($percentages) > 0)
          <div class="alert alert-danger alert-dismissible" id="message">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4><i class="icon fa fa-warning"></i> Silahkan lengkapi data persentase guna menentukan harga beli emas pada saat transaksi</h4>
          </div>
        @endif
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #DFF5FF">
            <div class="inner">
              @if(isset($gold_price))
                <h3 style="font-size: 24px !important">
                  <!-- Harga jual: {{ showRupiah($gold_price->selling_price) }}<br> -->
                  Harga beli: {{ showRupiah($gold_price->buy_price) }}<br>
                  {{ displayDate($gold_price->created_at) }}
                </h3>
              @else
                <h3>-</h3>

                <p>Belum ada harga emas</p>
              @endif
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/indonesian-rupiah.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()">Tambah harga emas <i class="fa fa-arrow-circle-right"></i></a></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #DFF5FF">
            <div class="inner">
              <h3>{{ getCokimWeight() }} gram</h3>

              <p>Total emas cokim</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/ingots.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/lebur/history/sellOngoing/20') }}" class="small-box-footer" target="_blank()">Riwayat Lebur Cokim <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #DFF5FF">
            <div class="inner">
              <h3>{{ showRupiah($transactions['cash']->sum('total_sum_price') + $transactions['credit']->sum('money_paid') + $transactions['retur']->sum('total_sum_price') + $other_transactions->sum('debit')) }}</h3>

              <p>
                Rincian Transaksi {{ displayDate(date('Y-m-d')) }}
              </p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/shopping-cart.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/transaction/all/all/' . date('Y-m-d') . '/' . date('Y-m-d') . '/20') }}" class="small-box-footer" target="_blank()">Daftar Transaksi <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #C3E2C2">
            <div class="inner">
              <h3>{{ getGram('wolm', 'Siap dijual')->sum('weight') }} gram</h3>

              <p>Total seluruh emas</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/earrings.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #C3E2C2">
            <div class="inner">
              <h3>{{ getGram('wolm', 'Siap dijual')->sum('stone_weight') }} gram</h3>

              <p>Total seluruh batu</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/diamond.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #C3E2C2">
            <div class="inner">
              <h3>{{ getGram('wolm', 'Siap dijual')->sum('weight') + getGram('all', 'Siap dijual')->sum('stone_weight') }} gram</h3>

              <p>Total keseluruhan asset</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/jewelry.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
          </div>
        </div>
        @foreach(getCategoryObj() as $category)
          <div class="col-xs-12 col-sm-4">
            <div class="small-box" @if($category->code != 'LM') style="background-color: #FFE6E6" @else style="background-color: #FD8B51" @endif>
              <div class="inner">
                <h3>{{ getGram($category->code, 'Siap dijual')->sum('weight') }} gram<br>
                {{ getTotalItems($category->code, 'Siap dijual') }} pcs</h3>

                <p>Total asset {{ strtolower($category->name) }} siap jual</p>
              </div>
              <div class="icon">
                <?php $str = "assets/icon/" . $category->code . ".png"; ?>
                <span><img src='{{asset("$str")}}' style="width: 80px" id="icon-{{ $category->code }}"></span>
                <span><img src='{{asset("assets/icon/LM.png")}}' style="width: 80px" id="icon-lm-{{ $category->code }}" style="display: none"></span>
              </div>
              <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  </div>

@endsection

@section('js-addon')
  <script type="text/javascript">
    
    $(document).ready(function(){
      @foreach(getCategoryObj() as $category)
        str = "assets/icon/{{ $category->code }}.png";
        code = '{{ $category->code }}';
        $.get('{{asset(' + str + ')}}')
        .done(function() { 
            document.getElementById('icon-lm-' + code).style.display = 'none';
            document.getElementById('icon-' + code).style.display = 'block';

        }).fail(function() { 
            document.getElementById('icon-lm-' + code).style.display = 'block';
            document.getElementById('icon-' + code).style.display = 'none';

        })
      @endforeach
    }
  </script>
@endsection
