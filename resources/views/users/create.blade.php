@extends('default')

@section('content')

	@if($errors->any())
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
				{{ $error }} <br>
			@endforeach
		</div>
	@endif

	{!! Form::open(['route' => 'users.store']) !!}

		<div class="mb-3">
			{{ Form::label('email', 'Email', ['class'=>'form-label']) }}
			{{ Form::text('email', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('password', 'Password', ['class'=>'form-label']) }}
			{{ Form::text('password', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('fullname', 'Fullname', ['class'=>'form-label']) }}
			{{ Form::text('fullname', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('photo', 'Photo', ['class'=>'form-label']) }}
			{{ Form::text('photo', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('', '', ['class'=>'form-label']) }}
			{{ Form::text('', null, array('class' => 'form-control')) }}
		</div>


		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}


@stop