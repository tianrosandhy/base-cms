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