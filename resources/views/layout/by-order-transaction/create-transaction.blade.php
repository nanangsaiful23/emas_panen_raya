<div class="content-wrapper">
  @include('layout' . '.error')

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> {{ $default['page_name'] }}</h3>
          </div>

          {!! Form::model($order,array('url' => route($role . '.by-order-transaction.store-transaction', $order->id), 'enctype'=>'multipart/form-data', 'method' => 'POST', 'class' => 'form-horizontal')) !!}
            <div class="box-body">
              @include('layout' . '.by-order-transaction.form-transaction', ['SubmitButtonText' => 'Tambah'])
            </div>
          {!! Form::close() !!}

        </div>
      </div>
    </div>
  </section>
</div>