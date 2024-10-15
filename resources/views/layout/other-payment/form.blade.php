<div class="panel-body" style="color: black !important;">
    <div class="row">
        <div class="form-group">
            {!! Form::label('name', 'Nama', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('name', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('name', null, array('class' => 'form-control')) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('date', 'Tanggal Pembayaran', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-3">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('date', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    <div class="input-group date">
                        <input type="text" class="form-control pull-right" name="date" id="date" @if($SubmitButtonText == 'Edit') value="{{ $server_payment->date }}" @endif>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('nominal', 'Nominal', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('nominal', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('nominal', null, array('class' => 'form-control', 'onkeyup' => 'formatNumber("nominal")')) !!}
                @endif
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
                    <a href="{{ url($role . '/other-payment/' . $other_payment->id . '/edit') }}" class="btn form-control">Ubah Data Biaya Lain</a>
                @endif
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@section('js-addon')
    <script type="text/javascript">
        $(document).ready (function (){
            $('.select2').select2();

            $('#date').datepicker({
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