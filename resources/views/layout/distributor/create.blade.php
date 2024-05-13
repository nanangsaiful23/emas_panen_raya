<div class="content-wrapper">
  @include('layout' . '.error')

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ $default['page_name'] }}</h3>
          </div>

          {!! Form::model(old(),array('url' => route($role . '.distributor.store', [$type]), 'enctype'=>'multipart/form-data', 'method' => 'POST', 'class' => 'form-horizontal')) !!}
            <div class="box-body">
              @include('layout' . '.distributor.form', ['SubmitButtonText' => 'Tambah'])
            </div>
          {!! Form::close() !!}

        </div>
      </div>
    </div>
  </section>
</div>