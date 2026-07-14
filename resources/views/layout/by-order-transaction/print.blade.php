<html>
	<style type="text/css">
		@media print {
			body {
				-webkit-print-color-adjust: exact;
			}

			.print-red span, .print-red b, .print-red div, .print-red i
			{
				color: red !important;
/*				background-color: red !important;*/
				-webkit-print-color-adjust: exact;
			}
		}
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
				<div style="height: 140mm !important; border-bottom: dotted black 2px;">
					<div class="wrapper" style="padding-top: 20px;">
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-2">	
			            			<img src="{{asset('assets/icon/TokoPaktani.png')}}" alt="about" style="display: block;width: 100%;margin-left: auto;margin-right: auto;display: block;">
								</div>
								<div class="col-sm-5 print-red" style="text-align: center; font-size: 20px; color: red !important; ">	
									<b>TOKO PERHIASAN EMAS<br>
									<span style="font-family: 'Dancing script'; font-size: 28px;">{{ config('app.store_name') }}</span><br></b>
									<div style="font-size: 11px">{{ config('app.address') }}<br>
									{{ config('app.phone_number') }}<br><i class="fa fa-whatsapp"></i> {{ config('app.wa_number') }}<br></div>
									<div class="col-sm-12" style="font-size: 11px;">
										<i class="fa fa-facebook-square"></i> Panen Raya <i class="fa fa-instagram"></i> tmpanenraya<br>
									</div>
									<div class="col-sm-12" style="font-size: 12px">
										<b>NPWP: 47 131 385 8 515 000</b>
									</div>
									<div class="col-sm-12" style="font-size: 12px; border: solid red 3px; text-align: center; font-weight: bold;">
										MINGGU KE 4 TUTUP
									</div>
								</div>
								<div class="col-sm-5">	
									<table class="none no-pad" style="font-size: 14px">
										<tr>
											<td>Nama</td>
											<td>:</td>
											<td style="border-bottom: 0.5px dotted !important; padding-left: 3px">{{ $order->name }}</td>
										</tr>
										<tr>
											<td>Alamat</td>
											<td>:</td>
											<td style="border-bottom: 0.5px dotted !important; padding-left: 3px">{{ $order->address }}</td>
										</tr>
										<tr>
											<td>Telp/HP</td>
											<td>:</td>
											<td style="border-bottom: 0.5px dotted !important; padding-left: 3px">{{ hidePhoneNumber($order->phone_number, 2) }}</td>
										</tr>
										<tr>
											<td>Tanggal</td>
											<td>:</td>
											<td style="border-bottom: 0.5px dotted !important; padding-left: 3px">{{ displayDate($order->created_at) }}</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<h3 style="text-align: center; text-decoration: underline; text-transform: uppercase;">Nota Pesanan</h3>
					<table class="col-sm-11" style="font-size: 14px; text-align: center; margin-top: 25 px;">
						<thead style="font-weight: bold;">
							<td>Model</td>
							<td>Berat</td>
							<td>Kadar</td>
							<td>Tanggal Pesan</td>
							<td>Tanggal Jadi</td>
							<td>Ongkos</td>
						</thead>
						<tbody>	
							<tr>
								<td>{{ $order->model }}</td>
								<td>{{ $order->weight }} gram</td>
								<td>{{ $order->percentage->name }}</td>
								<td>{{ displayDate($order->order_date) }}</td>
								<td>{{ displayDate($order->finish_date) }}</td>
								<td>{{ showRupiah($order->fee) }}</td>
							</tr>
							<tr>
								<td colspan="5" style="border-color: white !important; border-right-color: black !important;"></td>
								<td><b>{{ showRupiah($order->fee) }}</b></td>
							</tr>
						</tbody>
					</table>
					<hr>
					<div class="wrapper">
						<div class="row">
							<div class="col-sm-12" style="margin-top: 35 px">
								<<!-- div class="col-sm-offset-1 col-sm-7" style="font-size: 10px; border: solid black 3px; text-align: left;">
									<span style="font-size: 11px;"><b>PERHATIAN:</b></span><br>
									Apabila pesanan saudara sampai batas waktu 2 bulan tidak diambil,<br>
									maka pesanan dianggap hilang.
								</div> -->
								<div class="col-sm-offset-1 col-sm-3">
									Hormat kami,<br><br><br>
									TM. Panen Raya
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
        $(document).ready (function (){
        	window.print();
        }); 

	    window.setTimeout(function(){
      		window.location = window.location.origin + '/{{ $role }}/by-order-transaction/create';
	    }, 5000);
	</script>
</html>