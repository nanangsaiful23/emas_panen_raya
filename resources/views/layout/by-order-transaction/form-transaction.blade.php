<div class="panel-body" style="color: black !important;">
    <div class="row">
        <div class="col-sm-6">
            <h3>Data Pemesan</h3>

            <div class="form-group">
                {!! Form::label('name', 'Nama', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-10">
                    {!! Form::text('name', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('address', 'Alamat', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-10">
                    {!! Form::text('address', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('phone_number', 'Nomor Telephone', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-10">
                    {!! Form::text('phone_number', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                </div>
            </div>

            <h3>Biaya</h3>

            <div class="col-sm-6" style="margin-left: -18px">
                <div class="form-group">
                    {!! Form::label('price_gram', 'Harga per Gram', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('price_gram', null, array('class' => 'form-control', 'onkeyup' => 'formatNumber("price_gram")', 'onchange' => 'changeSellingPrice(); formatNumber("selling_price"); formatNumber("total_price")')) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('selling_price', 'Harga Jual', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('selling_price', null, array('class' => 'form-control', 'id' => 'selling_price', 'onkeyup' => 'formatNumber("selling_price")', 'onchange' => 'changeTotalPrice(); formatNumber("total_price")')) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6" style="margin-left: -18px">
                <div class="form-group">
                    {!! Form::label('fee', 'Ongkos', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('fee', formatNumber($order->fee), array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'fee')) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('total_price', 'Harga Total', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('total_price', null, array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'total_price')) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Data Perhiasan</h3>
            <div class="form-group">
                {!! Form::label('model', 'Model', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-10">
                    {!! Form::text('model', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('percentage', 'Kadar', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-5">
                    {!! Form::text('percentage', $order->percentage->name, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('weight', 'Berat', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-5">
                    {!! Form::text('weight', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                </div>
            </div>
        
            <div class="form-group">
                {!! Form::label('order_date', 'Tanggal Pesan', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-5">
                    {!! Form::text('order_date', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('finish_date', 'Tanggal Jadi', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-5">
                    {!! Form::text('finish_date', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                </div>
            </div>
        </div>
        
        <div class="form-group">
            {{ csrf_field() }}

            <div class="col-sm-5">
                <hr>
                @if($SubmitButtonText == 'Edit')
                    {!! Form::submit($SubmitButtonText, ['class' => 'btn form-control'])  !!}
                @elseif($SubmitButtonText == 'Tambah')
                    {!! Form::submit($SubmitButtonText, ['class' => 'btn form-control'])  !!}
                @elseif($SubmitButtonText == 'View')
                    <a href="{{ url($role . '/by-order-transaction/' . $order->id . '/edit') }}" class="btn form-control">Ubah Data Pesanan</a>
                @endif
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@section('js-addon')
  <script type="text/javascript">
    $(document).ready(function(){
        $('.select2').select2();

    });

    function formatNumber(name)
    {
        num = document.getElementById(name).value;
        num = num.toString().replace(/,/g,'');
        document.getElementById(name).value = num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

    function unFormatNumber(num)
    {
        return num.replace(/,/g,'');
    }

    function changeSellingPrice()
    {
        nominal = '{{ $order->percentage->nominal }}';
        profit  = '{{ $order->percentage->profit }}';
        document.getElementById('selling_price').value = unFormatNumber(document.getElementById('price_gram').value) * parseFloat(document.getElementById('weight').value) * parseFloat(nominal) * ((parseInt(100) + parseInt(profit)) / 100);
        document.getElementById('selling_price').value = parseInt(document.getElementById('selling_price').value);
        changeTotalPrice();
    }

    function changeTotalPrice()
    {
        document.getElementById('total_price').value = parseFloat(unFormatNumber(document.getElementById('selling_price').value)) + parseFloat(unFormatNumber(document.getElementById('fee').value));
    }
</script>
@endsection