@extends ('layouts.master')

@section ('content')
<div class="container clearfix non-homepage">
	@include ('include.breadcrumb', [
		'path' => ($mode == 'post' ? ['Blog' => 'blog'] : [])
	])
	<div class="single-post nobottommargin">

		<div class="entry clearfix">
			<div class="entry-title">
				<h2 class="display-header">{{ $data->title }}</h2>
			</div>
			<div class="ket">
				<i class="fa fa-calendar"></i> {{ date('d M Y H:i:s', strtotime($data->created_at)) }}
			</div>

			@if($data->image)
			<div class="entry-image">
				<img src="{{ $data->getThumbnailUrl('image', 'large') }}" alt="Blog Single">
			</div>
			@endif

			<!-- Entry Content
			============================================= -->
			<div class="entry-content my-3">
				{!! $data->description !!}

				@if(isset($data->tags))
					@include ('include.tagcloud', ['tags' => $data->tags])
				@endif
				<div class="clear"></div>
				@include ('include.sharer', [
					'title' => $data->title,
					'url' => url()->current(),
					'text' => $data->excerpt ? $data->excerpt : descriptionMaker($data->description)
				])

			</div>
		</div><!-- .entry end -->
	</div>
</div>
@stop
