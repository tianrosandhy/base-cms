<div id="posts" class="post-grid  grid-container grid-3 clearfix">
	@foreach($data as $row)
	<div class="entry clearfix">
		@if($row->image)
		<div class="entry-image">
			<a href="{{ route('front.detail', ['slug' => $row->slug()]) }}"><img class="image_fade" src="{{ $row->image ? $row->getThumbnailUrl('image', 'small') : broken_image() }}" alt="{{ $row->title }}"></a>
		</div>
		@endif
		<div class="entry-title">
			<h2><a href="{{ route('front.detail', ['slug' => $row->slug()]) }}">{{ $row->outputTranslate('title') }}</a></h2>
		</div>
		<ul class="entry-meta clearfix">
			<li><i class="icon-calendar3"></i> {{ date('d M Y H:i:s', strtotime($row->created_at)) }}</li>
			<li>
				@foreach($row->category as $cat)
				<a href="{{ route('front.post.category', ['slug' => $cat->slug()]) }}">{{ $cat->outputTranslate('name') }}</a>
				@endforeach
			</li>
		</ul>
		<div class="entry-content">
			<p>{{ $row->excerpt ? $row->excerpt : descriptionMaker($row->description, 15) }}</p>
		</div>
		<a href="{{ route('front.detail', ['slug' => $row->slug()]) }}" class="more-link">Read More</a>
	</div>
	@endforeach

	@if($data->count() == 0)
	<div class="alert alert-danger">We cannot find the post with that keyword. <a href="#" class="reset-post-filter">Reset filter</a></div>
	@endif

	{!! $data->links('include.pagination') !!}
</div>