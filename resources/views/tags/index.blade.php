@extends('default')

@section('content')

	<div class="d-flex justify-content-end mb-3"><a href="{{ route('tags.create') }}" class="btn btn-info">Create</a></div>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>id</th>
				<th>name</th>

				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($tags as $tag)

				<tr>
					<td>{{ $tag->id }}</td>
					<td>{{ $tag->name }}</td>

					<td>
						<div class="d-flex gap-2">
                            <a href="{{ route('tags.show', [$tag->id]) }}" class="btn btn-info">Show</a>
                            <a href="{{ route('tags.edit', [$tag->id]) }}" class="btn btn-primary">Edit</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['tags.destroy', $tag->id]]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </div>
					</td>
				</tr>

			@endforeach
		</tbody>
	</table>

@stop
