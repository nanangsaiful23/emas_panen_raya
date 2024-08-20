<style type="text/css">
  .select2-container--default .select2-selection--multiple .select2-selection__choice
  {
    background-color: rgb(60, 141, 188) !important;
  }

  .modal-body {
    overflow-y: auto;
    }

    .modal-content {
        /*width: 1500px;
        margin-left: -500px;*/
    }
</style>

<div class="panel-body">
    <div class="alert alert-danger alert-dismissible" id="message" style="display:none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> Barang kosong</h4>
            <div id="empty-item"></div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group col-sm-5">
                {!! Form::label('member_id', 'Member', array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    @if($SubmitButtonText == 'View')
                        {!! Form::text('member', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                    @else
                        {!! Form::text('member_name', null, array('class' => 'form-control', 'id' => 'member_name')) !!}
                        <select class="form-control select2" style="width: 100%;" name="member_id" id="all_member">
                            <div>
                                @foreach(getMembers() as $member)
                                <option value="{{ $member->id }}" @if($transaction->member_id == $member->id) selected @endif>
                                    {{ $member->name . ' (' . $member->address . ')'}}</option>
                                @endforeach
                            </div>
                        </select>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group col-sm-5">
            {!! Form::label('note', 'Keterangan', array('class' => 'col-sm-4 control-label')) !!}
            <div class="col-sm-8">
                @if($SubmitButtonText == 'View')
                    {!! Form::text('note', null, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                @else
                    {!! Form::text('note', null, array('class' => 'form-control', 'style' => 'height: 70px')) !!}
                @endif
            </div>
        </div>
        <table class="table table-bordered table-striped" style="overflow-x: auto;">
            <thead>
                <th>Kode</th>
                <th>Berat</th>
                <th>Kadar</th>
                <th>Harga Emas</th>
                <th>Barang</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Harga Batu</th>
                <th>Potongan</th>
                <th>Total Akhir</th>
            </thead>
            <tbody id="table-transaction">
                <?php $i = 1; ?>
                @foreach($transaction->details as $detail)
                    <tr id="row-data-{{ $i }}">
                        <td>
                            {!! Form::hidden('detail_ids[]', $detail->id, array('id' => 'detail_id-'.$i)) !!}
                            {!! Form::textarea('codes[]', $detail->good_unit->good->code, array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'code-'.$i, 'style' => 'height: 70px')) !!}
                        </td>
                        <td>
                            {!! Form::text('weights[]', $detail->good_unit->good->weight, array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'weight-'.$i)) !!}
                        </td>
                        <td>
                            {!! Form::text('percentage_shows[]', $detail->good_unit->good->percentage->name, array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'percentage_show-'.$i)) !!}
                        </td>
                        <td>
                            {!! Form::textarea('gold_prices[]', showRupiah($detail->gold_price), array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'gold_price-'.$i, 'style' => 'height: 70px')) !!}
                        </td>
                        <td width="10%">
                            {!! Form::textarea('name_temps[]', $detail->good_unit->good->name, array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'name_temp-'.$i, 'style' => 'height: 70px')) !!}
                        </td>
                        <td>
                            {!! Form::textarea('buy_prices[]', formatNumber($detail->buy_price), array('class' => 'form-control','id'=>'buy_price-' . $i, 'onkeyup' => "formatNumber('buy_price-" . $i . "')", 'style' => 'height: 70px')) !!}
                        </td>
                        <td>
                            {!! Form::textarea('prices[]', formatNumber($detail->selling_price), array('class' => 'form-control', 'id' => 'price-'.$i, 'onchange' => "editPrice('" . $i . "')", 'onkeyup' => "formatNumber('price-" . $i . "')", 'style' => 'height: 70px')) !!}
                        </td>
                        <td>
                            {!! Form::textarea('stone_prices[]', formatNumber($detail->stone_price), array('class' => 'form-control', 'id' => 'stone_price-'.$i, 'onchange' => "editPrice('" . $i . "')", 'onkeyup' => "formatNumber('stone_price-" . $i . "')", 'style' => 'height: 70px')) !!}
                        </td>
                        <td>
                            <input type="text" name="discounts[]" class="form-control" id="discount-{{ $i }}" onchange="editPrice('{{ $i }}')" value="{{ formatNumber($detail->discount_price) }}">
                        </td>
                        <td>
                            {!! Form::textarea('sums[]', formatNumber($detail->sum_price), array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'sum-'.$i, 'style' => 'height: 70px')) !!}
                        </td>
                    </tr>
                    <?php $i++ ?>
                @endforeach
            </tbody>
        </table>
        <div class="form-group">
            {!! Form::label('total_item_price', 'Total Harga', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-3">
                {!! Form::text('total_item_price', formatNumber($transaction->total_item_price), array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'total_item_price')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('total_discount_items_price', 'Total Potongan Harga Per Barang', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-3">
                {!! Form::text('total_discount_items_price', formatNumber($transaction->total_discount_items_price), array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'total_discount_items_price')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('total_discount_price', 'Potongan Harga Akhir', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-3">
                <input type="text" name="total_discount_price" class="form-control" id="total_discount_price" onchange="changeTotal()" onkeypress="changeTotal()" required="required" onkeyup="formatNumber('total_discount_price')">
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('total_sum_price', 'Total Akhir', array('class' => 'col-sm-3 control-label', 'style' => "font-size: 40px; height: 40px;")) !!}
            <div class="col-sm-3">
                {!! Form::text('total_sum_price', formatNumber($transaction->total_sum_price), array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'total_sum_price', 'style' => "font-size: 40px; height: 40px;")) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('money_paid', 'Bayar', array('class' => 'col-sm-3 control-label', 'style' => "font-size: 40px; height: 40px;")) !!}
            <div class="col-sm-3">
                <input type="text" name="money_paid" class="form-control" id="money_paid" onchange="changeReturn()" onkeypress="changeReturn()" required="required" onkeyup="formatNumber('money_paid')" style="font-size: 40px; height: 40px;">
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('money_returned', 'Kembali', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-3">
                {!! Form::text('money_returned', formatNumber($transaction->money_returned), array('class' => 'form-control', 'readonly' => 'readonly', 'id' => 'money_returned')) !!}
            </div>
        </div>
        {{ Form::hidden('type', 'normal') }}
    </div>
</div>

{{ csrf_field() }}

<hr>
@if($SubmitButtonText == 'Edit')
    {!! Form::submit($SubmitButtonText, ['class' => 'btn btn-warning btn-flat btn-block form-control',])  !!}
@elseif($SubmitButtonText == 'Tambah')
    <div onclick="event.preventDefault(); submitForm(this);" class= 'btn btn-success btn-flat btn-block form-control' style="height: 80px; font-size: 40px;">Proses Transaksi</div>
@elseif($SubmitButtonText == 'View')
@endif

{!! Form::close() !!}

@section('js-addon')
    <script type="text/javascript">
        var total_item = '{{ sizeof($transaction->details) }}';
        var total_item_retur = 1;
        $(document).ready (function (){
            $('.select2').select2();
            $("#all_barcode").focus();
            document.getElementById("total_discount_price").value = 0;
        });


        function changeTotal()
        {
            total_item_price = parseInt(0);
            total_discount_price = parseInt(0);
            total_sum_price = parseInt(0);
            total_discount_items = parseInt(0);

            for (var i = 1; i <= total_item; i++)
            {
                if(document.getElementById("detail_id-" + i))
                {
                    if(document.getElementById("detail_id-" + i).value != '')
                    {
                        items = parseInt(unFormatNumber(document.getElementById("price-" + i).value)) + parseInt(unFormatNumber(document.getElementById("stone_price-" + i).value));
                        
                        total_item_price += parseInt(items);

                        sums = document.getElementById("sum-" + i).value;
                        sums = sums.replace(/,/g,'');

                        total_sum_price += parseInt(sums);

                        discount = document.getElementById("discount-" + i).value;
                        discount = discount.replace(/,/g,'');

                        total_discount_items += parseInt(discount);
                    }
                }
            }

            discount = document.getElementById("total_discount_price").value;
            discount = discount.replace(/,/g,'');
            total_sum_price = parseInt(total_item_price) - parseInt(discount);

            document.getElementById("total_item_price").value = total_item_price;
            document.getElementById("total_discount_items_price").value = total_discount_items;
            document.getElementById("total_sum_price").value = total_sum_price;

            formatNumber("total_item_price");
            formatNumber("total_discount_items_price");
            formatNumber("total_sum_price");

            changeReturn();
        }

        function changeReturn()
        {
            total = document.getElementById("money_paid").value;
            total = total.replace(/,/g,'');

            sum = document.getElementById("total_sum_price").value;
            sum = sum.replace(/,/g,'');
            money_returned = parseInt(total) - parseInt(sum);

            document.getElementById("money_returned").value = money_returned;
            formatNumber("money_returned");
        }

        function submitForm(btn)
        {
            if($('#money_paid').val() != '' && $('#total_discount_price').val() != '')
            {
                if(parseInt(unFormatNumber($('#money_paid').val())) < parseInt(unFormatNumber($('#total_sum_price').val())) && ($('#all_member').val() == '1' && $('#member_name').val() == ''))
                {
                    alert('Jumlah pembayaran kurang dari total belanja. Silahkan pilih member');
                }
                else
                {
                    btn.disabled = true;
                    document.getElementById('transaction-form').submit();
                    // alert('hay');
                }
            }
            else
            {
                alert('Silahkan masukkan jumlah uang dan potongan toko');
            }
        }

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

        function deleteItem(index)
        {
            console.log('masuk delet');
            $("#row-data" + index).remove();
            changeTotal();
        }

        function editPrice(index)
        {
            temp1=parseInt(index)+1
            var type = '';

            document.getElementById("sum-" + type + index).value = parseInt(unFormatNumber(document.getElementById("price-" + type + index).value)) - parseInt(unFormatNumber(document.getElementById("discount-" + type + index).value)) + parseInt(unFormatNumber(document.getElementById("stone_price-" + type + index).value));

            formatNumber("sum-" + type + index);
            formatNumber("discount-" + type + index);

            changeTotal();

        }

    </script>
@endsection
