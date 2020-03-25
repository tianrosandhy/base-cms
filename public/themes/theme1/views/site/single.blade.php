@extends ('layouts.master')

@section ('slider')
	@include ('include.breadcrumb', [
		'path' => ($mode == 'post' ? ['Blog' => 'blog'] : [])
	])
@stop
@section ('content')
<div class="container clearfix">
	<div class="single-post nobottommargin">
		<div class="entry clearfix">
			<div class="entry-title">
				<h2>{{ $data->title }}</h2>
			</div>

			<ul class="entry-meta clearfix">
				<li><i class="icon-calendar3"></i> {{ date('d M Y H:i:s', strtotime($data->created_at)) }}</li>
			</ul><!-- .entry-meta end -->

			@if($data->image)
			<div class="entry-image bottommargin">
				<img src="{{ $data->getThumbnailUrl('image', 'large') }}" alt="Blog Single">
			</div>
			@endif

			<!-- Entry Content
			============================================= -->
			<div class="entry-content notopmargin">
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
