<?php
//debug purpose
$posts = SiteInstance::post()->paginate(1);
?>
@extends ('site::template.master')
@section ('content')
<div class="container">
	<h2 class="mt-3 mb-3 display-4">This is just <b>Theme 1</b> Scaffolding Example</h2>

	@if($posts->count() == 0)
	<div class="alert alert-warning">Sorry, we dont have any post to show right now.</div>
	@endif
	@foreach($posts as $row)
	<?php
	$data = SiteInstance::post()->setData($row)->structure();
	?>
	<div class="card card-body mt-3 mb-3">
		<div class="row">
			<div class="col-sm-3">
				<img src="{{ $data['image'] }}" style="width:100%;" alt="{{ $data['title'] }}">				
			</div>
			<div class="col-sm-9">
				<h4>{{ $data['title'] }}</h4>
				<div class="ket">
					@foreach($data['tags'] as $tg)
					<span class="badge badge-primary">{{ $tg }}</span>
					@endforeach
				</div>
				<div class="ket">
					Category : 
					@foreach($data['category'] as $cat)
					<span class="badge badge-secondary">{{ $cat['name'] }}</span>
					@endforeach
				</div>

				<div class="excerpt mt-3">
					<p class="lead">
						{!! $data['excerpt'] !!}
					</p>
				</div>
			</div>
		</div>
	</div>
	@endforeach

	{{ $posts->links() }}
</div>
@stop