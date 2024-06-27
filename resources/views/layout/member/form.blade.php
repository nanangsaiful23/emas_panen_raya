<div class="panel-body" style="color: black !important;">
    <div class="row">
        <div class="form-group">
            {!! Form::label('name', 'Nama Member', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('name', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('name', null, array('class' => 'form-control')) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('address', 'Alamat', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('address', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('address', null, array('class' => 'form-control')) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('village', 'Desa', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('village', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('village', null, array('class' => 'form-control')) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('subdistrict', 'Kecamatan', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('subdistrict', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('subdistrict', null, array('class' => 'form-control')) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('phone_number', 'No HP', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('phone_number', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('phone_number', null, array('class' => 'form-control')) !!}
                @endif
            </div>
        </div>

        <!-- <div class="form-group">
            {!! Form::label('birth_place', 'Tempat Lahir', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('birth_place', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('birth_place', null, array('class' => 'form-control')) !!}
                @endif
            </div>
        </div> -->

        <div class="form-group">
            {!! Form::label('birth_date', 'Tanggal Lahir', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('birth_date', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @elseif($SubmitButtonText == 'Edit')
                    <div class="input-group date">
                        <input type="text" class="form-control pull-right" name="birth_date" id="birth_date" value="{{ $member->birth_date }}">
                    </div>
                @else
                    <div class="input-group date">
                        <input type="text" class="form-control pull-right" name="birth_date" id="birth_date">
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('no_ktp', 'No KTP/SIM', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('no_ktp', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('no_ktp', null, array('class' => 'form-control')) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('start_point', 'Point Awal', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                Koma menggunakan simbol .
                @if($SubmitButtonText == 'View')
                    {!! Form::text('start_point', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('start_point', null, array('class' => 'form-control')) !!}
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
                    <a href="{{ url($role . '/member/' . $member->id . '/edit') }}" class="btn form-control">Ubah Data Member</a>
                @endif
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@section('js-addon')
<script type="text/javascript">
    
      $(document).ready (function (){
            $('#birth_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                todayHighlight: true
            });
      });
</script>
@endsection