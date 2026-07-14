<div class="panel-body" style="color: black !important;">
    <div class="row">
        <div class="col-sm-6">
            <h3>Data Pemesan</h3>
            <div class="form-group">
                {!! Form::label('name', 'Nama', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-10">
                    @if($SubmitButtonText == 'View')
                        {!! Form::text('name', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                    @else
                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('address', 'Alamat', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-10">
                    @if($SubmitButtonText == 'View')
                        {!! Form::text('address', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                    @else
                        {!! Form::text('address', null, array('class' => 'form-control')) !!}
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('phone_number', 'Nomor Telephone', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-10">
                    @if($SubmitButtonText == 'View')
                        {!! Form::text('phone_number', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                    @else
                        {!! Form::text('phone_number', null, array('class' => 'form-control')) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Data Perhiasan</h3>
            <div class="form-group">
                {!! Form::label('model', 'Model', array('class' => 'col-sm-12')) !!}
                <div class="col-sm-10">
                    @if($SubmitButtonText == 'View')
                        {!! Form::text('model', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                    @else
                        {!! Form::text('model', null, array('class' => 'form-control')) !!}
                    @endif
                </div>
            </div>

            <div class="col-sm-12" style="margin-left: -30px">
                <div class="form-group col-sm-6">
                    {!! Form::label('category', 'Kategori', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-12">
                        @if($SubmitButtonText == 'View')
                            {!! Form::text('category', $order->category->name, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                        @else
                            {!! Form::select('category_id', getCategories(), null, ['class' => 'form-control select2','required'=>'required', 'style'=>'width:100%', 'id' => 'category']) !!}
                        @endif
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    {!! Form::label('percentage', 'Kadar', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-12">
                        @if($SubmitButtonText == 'View')
                            {!! Form::text('percentage', $order->percentage->name, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                        @else
                            {!! Form::select('percentage_id', getPercentages(), null, ['class' => 'form-control select2','required'=>'required', 'style'=>'width:100%', 'id' => 'percentage']) !!}
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-12" style="margin-left: -30px">
                <div class="form-group col-sm-6">
                    {!! Form::label('weight', 'Berat', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-12">
                        @if($SubmitButtonText == 'View')
                            {!! Form::text('weight', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                        @else
                            {!! Form::text('weight', null, array('class' => 'form-control')) !!}
                        @endif
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    {!! Form::label('fee', 'Ongkos', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-12">
                        @if($SubmitButtonText == 'View')
                            {!! Form::text('fee', showRupiah($order->fee), array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                        @elseif($SubmitButtonText == 'Edit')
                            {!! Form::text('fee', formatNumber($order->fee), array('class' => 'form-control', 'onkeyup' => 'formatNumber("fee")')) !!}
                        @else
                            {!! Form::text('fee', null, array('class' => 'form-control', 'onkeyup' => 'formatNumber("fee")')) !!}
                        @endif
                    </div>
                </div>
            </div>
        

            <div class="col-sm-12" style="margin-left: -30px">
                <div class="form-group col-sm-6">
                    {!! Form::label('order_date', 'Tanggal Pesan', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-12">
                        @if($SubmitButtonText == 'View')
                            {!! Form::text('order_date', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                        @elseif($SubmitButtonText == 'Edit')
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right" id="order_date" name="order_date" value="{{ $order->order_date }}">
                            </div>
                        @else
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right" id="order_date" name="order_date">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    {!! Form::label('finish_date', 'Tanggal Jadi', array('class' => 'col-sm-12')) !!}
                    <div class="col-sm-12">
                        @if($SubmitButtonText == 'View')
                            {!! Form::text('finish_date', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                        @elseif($SubmitButtonText == 'Edit')
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right" id="finish_date" name="finish_date" value="{{ $order->finish_date }}">
                            </div>
                        @else
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right" id="finish_date" name="finish_date">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            {{ csrf_field() }}

            <div class="col-sm-12">
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

          $('#order_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
          })

          $('#finish_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
          })

    });

    function formatNumber(name)
    {
        num = document.getElementById(name).value;
        num = num.toString().replace(/,/g,'');
        document.getElementById(name).value = num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }
</script>
@endsection