<div class="panel-body" style="color: black !important;">
    <div class="row">
        <div class="form-group">
            {!! Form::label('last_point', 'Point Saat Ini', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('last_point', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('last_point', $member->getCurrentPoint(), array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'last_point')) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('point', 'Point yang Ingin di Redeem', array('class' => 'col-sm-12')) !!}
            <div class="col-sm-5">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('point', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('point', null, array('class' => 'form-control', 'id' => 'point', 'required' => 'required')) !!}
                @endif
            </div>
        </div>
        
        <div class="form-group">
            {{ csrf_field() }}

            <div class="col-sm-5">
                <hr>
                @if($SubmitButtonText == 'Edit')
                @elseif($SubmitButtonText == 'Tambah')
                    <div onclick="event.preventDefault(); submitForm(this);" class= 'btn btn-success btn-flat btn-block form-control'>Redeem Point</div>
                @elseif($SubmitButtonText == 'View')
                @endif
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@section('js-addon')
    <script type="text/javascript">
        function submitForm(btn)
        {
            if($('#last_point').val() >= $('#point').val())
            {
                document.getElementById('transaction-form').submit();
            }
            else
            {
                alert('Point anda tidak mencukupi. Silahkan masukkan nominal point yang sesuai');
            }
        }
    </script>
@endsection