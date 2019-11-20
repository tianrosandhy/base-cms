@extends ('main::master')
@section ('content')

<a href="{{ route('admin.post.index') }}" class="btn btn-secondary btn-sm">Back to Data</a>

<div class="row">
	<div class="col-sm-7">
		<h1 class="display-3" align="center">{{ $structure['title'] }}</h1>

		<div class="ket mt-2 mb-2" align="center">
			@foreach($structure['tags'] as $tg)
			<span class="badge badge-secondary">{{ trim($tg) }}</span>
			@endforeach
		</div>

		<div class="ket mb-2 mt-2" align="center">
			Created at <strong>{{ date('d M Y H:i:s', strtotime($structure['created_at'])) }}</strong>
		</div>

		<img src="{{ $structure['image'] }}" alt="{{ $structure['title'] }}" style="width:100%;">

		@if($structure['excerpt'])
		<div class="card card-body">
			<div style="font-size:smaller" class="mt-3">Excerpt</div>
			<p class="lead mb-5">{{ $structure['excerpt'] }}</p>
		</div>
		@endif

		@if($structure['description'])
		<div class="content card card-body">
			{!! $structure['description'] !!}
		</div>
		@endif
	</div>
	<div class="col-sm-5">
		<div class="card card-body">
			@if($structure['is_active'] == 1)
			<span class="badge badge-success">Active</span>
			@elseif($structure['is_active'] == 9)
			<span class="badge badge-danger">Deleted</span>
			@else
			<span class="badge badge-secondary">Draft</span>
			@endif

			<table class="table table-compact compact small">
				<tr>
					<td>Last Updated</td>
					<td>{{ date('d M Y H:i:s', strtotime($structure['updated_at'])) }}</td>
				</tr>
				<tr>
					<td>Likes Count</td>
					<td>{!! $data->likes->count() . ( $data->likes->count() > 0 ? ' <i class="fa fa-heart text-danger"></i>' : ' <i class="fa fa-heart-o text-danger"></i>' ) !!}</td>
				</tr>
			</table>

		</div>

		@if($data->related->count() > 0)
			<div class="card card-body mt-4">
				<h4 class="mb-3">Related Posts : </h4>
				<hr>
				@foreach($data->related as $rel)
					<?php
					$obj = PostInstance::setData($rel)->structure();
					?>
					<div class="row">
						<div class="col-sm-4">
							<a href="{{ route('admin.post.detail', ['id' => $obj['id']]) }}">
								<img src="{{ $obj['image'] }}" style="width:100%">
							</a>
						</div>
						<div class="col-sm-8">
							<a href="{{ route('admin.post.detail', ['id' => $obj['id']]) }}">
								<h5>{{ $obj['title'] }}</h5>
							</a>
							<div>
								@foreach($obj['category'] as $cat)
								<span class="badge badge-primary">{{ $cat['name'] }}</span>
								@endforeach
							</div>
							<div style="font-size:smaller">
								Last Update : {{ date('d M Y H:i:s', strtotime($obj['updated_at'])) }}
							</div>
						</div>
					</div>
				@endforeach
			</div>
		@else
		<div class="mt-3 alert alert-danger">No related posts. <a class="text-danger" href="{{ route('admin.post.edit', ['id' => $data->id]) }}">You can set the related post here.</a></div>
		@endif



		<div class="card card-body mt-4">
			<h4 class="mb-3">Comment Section</h4>
			<?php
			$baseComment = $data->comment->where('reply_to', null);
			?>
			@if($baseComment->count() > 0)
				@foreach($baseComment as $comm)
				<div class="card card-body" style="{!! $comm->is_active == 1 ? 'opacity:1' : 'opacity:.25' !!}">
					<div>
						<strong>{{ $comm->name }}</strong> {!! $comm->is_admin_reply ? '<span class="badge badge-primary">Admin</span>' : '' !!}
						<br>
						{{ $comm->email }} - {{ $comm->phone }}
					</div>
					<div style="font-size:smaller;">{{ date('d M Y H:i:s', strtotime($comm->created_at)) }}</div>
					<div class="mt-3">
						<p class="lead">{{ $comm->message }}</p>
					</div>

					<a href="#" class="btn btn-sm btn-secondary">Reply</a>
					
				</div>
				@endforeach
			@else
			<div class="alert alert-warning">No comment in this post</div>
			@endif


			<form action="{{ route('admin.post.comment', ['id' => $data->id]) }}" method="post" class="post-comment mt-4">
				{{ csrf_field() }}
				<input type="hidden" name="is_admin_reply" value="">
				<input type="hidden" name="reply_to" value="">
				<textarea name="message" class="form-control" placeholder="Admin Comment"></textarea>
				<button class="btn btn-primary" type="submit">Post Comment</button>
			</form>
		</div>

	</div>
</div>

@stop