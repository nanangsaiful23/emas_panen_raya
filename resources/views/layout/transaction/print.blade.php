<html>
	<style type="text/css">
		@page {
		  size: A4;
		  margin: 0;
		}
		/*@media print {
		  html, body {
		    width: 210mm;
		    height: 297mm;
		  }*/
			table {
			  border-collapse: collapse;
			}

			table, th, td {
			  border: 2px solid black;
			  margin: 10px;
			  padding: 20px;
			  height: 40px;
			}

			.none, .none td
			{
				border: none !important;
			}

			.no-pad, .no-pad td
			{
				height: 20px;
			}
/*		}*/
		.container {
		  width: 210mm;
		  height: 297mm;
		  position: relative;
		}
		.navi,
		.infoi {
		  width: 100%;
		  height: 100%;
		  position: absolute;
		  top: 0;
		  left: 0;
		}
		.infoi {
		  z-index: 10;
		}
	</style>
    <link rel="stylesheet" href="{{asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/print-style.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cookie&family=Cormorant&family=Dosis&family=Noto+Sans+Arabic&family=Crimson+Text&family=Dancing+Script&family=Inter:wght@300;400;500;600">
	<link rel="stylesheet" href="{{asset('assets/bower_components/font-awesome/css/font-awesome.min.css')}}">
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>

	<body style="font-family: 'inter';" class="container">
		<div class="infoi">
			@for($i = 0; $i < 2; $i++)
				<div style="height: 148.5mm !important; border-bottom: dotted black 2px;">
					<div class="wrapper" style="padding-top: 20px;">
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-2">	
			            			<img src="{{asset('assets/icon/TokoPaktani.png')}}" alt="about" style="display: block;width: 100%;margin-left: auto;margin-right: auto;display: block;">
								</div>
								<div class="col-sm-5" style="text-align: center; font-size: 20px;">	
									<b>PUSAT PERHIASAN EMAS<br>
									<span style="font-family: 'Dancing script'; font-size: 28px;">{{ config('app.store_name') }}</span><br></b>
									<div style="font-size: 16px">{{ config('app.address') }}<br>
									{{ config('app.phone_number') }}<br><i class="fa fa-whatsapp"></i> {{ config('app.wa_number') }}<br></div>
									<div class="col-sm-12" style="font-size: 14px;">
										<i class="fa fa-facebook-square"></i> Panen Raya <i class="fa fa-instagram"></i> tmpanenraya<br>
									</div>
								</div>
								<div class="col-sm-5">	
									<table class="none no-pad" style="font-size: 14px">
										<tr>
											<td>Nama</td>
											<td>:</td>
											<td style="border-bottom: 0.5px dotted !important; padding-left: 3px">{{ $transaction->member->name }}</td>
										</tr>
										<tr>
											<td>Alamat</td>
											<td>:</td>
											<td style="border-bottom: 0.5px dotted !important; padding-left: 3px">{{ $transaction->member->address }}</td>
										</tr>
										<tr>
											<td>Telp/HP</td>
											<td>:</td>
											<td style="border-bottom: 0.5px dotted !important; padding-left: 3px">{{ hidePhoneNumber($transaction->member->phone_number, 2) }}</td>
										</tr>
										<tr>
											<td>Tanggal</td>
											<td>:</td>
											<td style="border-bottom: 0.5px dotted !important; padding-left: 3px">{{ displayDate($transaction->created_at) }}</td>
										</tr>
										<tr>
											<td>Sales person</td>
											<td>:</td>
											<td style="border-bottom: 0.5px dotted !important; padding-left: 3px">{{ $transaction->actor()->name }}</td>
										</tr>
									</table>
								</div>
							</div>
							<div class="col-sm-12" style="text-align:center; margin-top: 3px; font-weight: bold;">
								<!-- <div class="col-sm-offset-1 col-sm-7" style="font-size: 18px; border: black solid 3px;">
									TERDEPAN DALAM MODEL DAN PENAMPILAN
								</div> -->
								<div class="col-sm-offset-2 col-sm-5" style="font-size: 16px">
									NPWP: 47 131 385 8 515 000
								</div>
							</div>
						</div>
					</div>
					<hr>
					<table class="col-sm-11" style="font-size: 16px; text-align: center;">
						<thead style="font-weight: bold;">
							<td>Nama Barang</td>
							<td>Kode</td>
							<td>Berat</td>
							<td>Kadar</td>
							<td>Harga</td>
							<!-- <td>Jumlah</td> -->
						</thead>
						<tbody>	
							@foreach($transaction->details as $detail)
								<tr>
									<td>{{ $detail->good_unit->good->name }}</td>
									<td>{{ $detail->good_unit->good->code }}</td>
									<td>{{ $detail->good_unit->good->weight }}</td>
									<td>{{ $detail->good_unit->good->percentage->name }}</td>
									<td>{{ showRupiah($detail->sum_price) }}</td>
									<!-- <td style="text-align: right !important;">{{ showRupiah($detail->sum_price) }}</td> -->
								</tr>
							@endforeach
							<tr>
								<td colspan="4" style="border-color: white !important; border-right-color: black !important;"></td>
								<td><b>{{ showRupiah($transaction->total_sum_price) }}</b></td>
							</tr>
						</tbody>
					</table>
					<hr>
					<div class="wrapper">
						<div class="row">
							<div class="col-sm-12" style="margin-top: 10px">
								<div class="col-sm-offset-1 col-sm-6" style="font-size: 10px; border: solid black 3px; text-align: left;">
									<span style="font-size: 11px;"><b>PERHATIAN:</b></span><br>
									1. Harap teliti dalam membeli, baik kadar maupun berat timbangan emas. Demikian untuk mencegah kesalahan yang merugikan PEMBELI.<br>
									2. Jual-beli menurut harga pasaran toko.<br>
									3. Barang yang sudah rusak/peok/gerang/putus maka kami akan beli dengan harga leburan/harga rosok yang berlaku.
								</div>
								<div class="col-sm-offset-1 col-sm-3">
									<div class="col-sm-12" style="font-size: 16px; border: solid black 3px; text-align: center; font-weight: bold;">
										MINGGU KE 4 TUTUP
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endfor
		  	<div class="navi">
		  		@for($i = 0; $i < 36; $i++)
            		<img src="{{asset('assets/icon/watermark.png')}}" alt="about" style="margin-left: 10px; display: relative;width: 30%; opacity: 10%;">
            	@endfor
            </div>
			
		</div>
	</body>

	<script type="text/javascript">		
        // $(document).ready (function (){
        // 	window.print();
        // }); 

	    // window.setTimeout(function(){
      	// 	window.location = window.location.origin + '/{{ $role }}/transaction/create';
	    // }, 5000);
	</script>
</html>