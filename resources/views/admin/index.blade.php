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
    @if(\Auth::user()->server_payment > 0)
      <div class="alert alert-danger alert-dismissible" id="message">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-warning"></i> Guna pelayanan yang maksimal, harap melakukan pembayaran tagihan server maksimal tanggal 20 pada setiap bulannya.<br>Status pembayaran saat ini adalah: tertunda {{ \Auth::user()->server_payment }} bulan</h4>
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
              <h3>{{ getGram('all', 'Siap dijual')->sum('weight') }} gram</h3>

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
              <h3>{{ getGram('all', 'Siap dijual')->sum('stone_weight') }} gram</h3>

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
              <h3>{{ getGram('all', 'Siap dijual')->sum('weight') + getGram('all', 'Siap dijual')->sum('stone_weight') }} gram</h3>

              <p>Total keseluruhan asset</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/jewelry.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #FFE6E6">
            <div class="inner">
              <h3>{{ getGram('AT', 'Siap dijual')->sum('weight') }} gram<br>
              {{ getTotalItems('AT', 'Siap dijual') }} pcs</h3>

              <p>Total asset anting/cuplik</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/earings.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #FFE6E6">
            <div class="inner">
              <h3>{{ getGram('CC', 'Siap dijual')->sum('weight') }} gram<br>
              {{ getTotalItems('CC', 'Siap dijual') }} pcs</h3>

              <p>Total asset cincin</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/diamond-ring.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #FFE6E6">
            <div class="inner">
              <h3>{{ getGram('GL', 'Siap dijual')->sum('weight') }} gram<br>
              {{ getTotalItems('GL', 'Siap dijual') }} pcs</h3>

              <p>Total asset gelang</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/bracelet.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #FFE6E6">
            <div class="inner">
              <h3>{{ getGram('KL', 'Siap dijual')->sum('weight') }} gram<br>
              {{ getTotalItems('KL', 'Siap dijual') }} pcs</h3>

              <p>Total asset kalung</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/necklace.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="small-box" style="background-color: #FFE6E6">
            <div class="inner">
              <h3>{{ getGram('LT', 'Siap dijual')->sum('weight') }} gram<br>
              {{ getTotalItems('LT', 'Siap dijual') }} pcs</h3>

              <p>Total asset liontin</p>
            </div>
            <div class="icon">
              <span><img src="{{asset('assets/icon/pendant.png')}}" style="width: 80px"></span>
            </div>
            <a href="{{ url('/admin/gold-price/create') }}" class="small-box-footer" target="_blank()"></a>
          </div>
        </div>
      </div>
    </section>
  </div>

@endsection
