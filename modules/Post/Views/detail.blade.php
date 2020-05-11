@extends ('main::master')

@push ('style')
<style>
	.form-reply, .post-reply{
		padding-left:2em;
	}
</style>
@endpush

@section ('content')
<div class="content-box">
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

			@if(method_exists($data, 'buildSeoTags'))
			<div class="card card-body mt-4">
				<h5>Generated SEO Tags</h5>
				<pre class="language-html"><code class="language-html">{{ $data->buildSeoTags() }}</code></pre>
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

			@include ('post::partials.detail-related')
			@include ('post::partials.detail-comment')

		</div>
	</div>
</div>

@stop