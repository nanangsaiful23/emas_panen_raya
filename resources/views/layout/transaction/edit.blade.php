<div class="content-wrapper">
  @include('layout' . '.error')

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ $default['page_name'] }}</h3>
          </div>

		      {!! Form::model($transaction, array('url' => route($role . '.transaction.update', $transaction->id), 'method' => 'POST', 'class' => 'form-horizontal')) !!}
            <div class="box-body">
              @include('layout' . '.transaction.form-edit', ['SubmitButtonText' => 'Edit'])
			        {{ method_field('PUT') }}
              {{  Form::hidden('url', URL::previous())  }}
            </div>
          {!! Form::close() !!}

        </div>
      </div>
    </div>
  </section>
</div>