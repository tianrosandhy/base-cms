<?php
$posts = SiteInstance::post()->paginate(4);
?>
@if($posts->count() > 0)
<section id="homepage-blog">
		
	<div class="heading-block topmargin-sm center">
		<h3>Our Latest Blog</h3>
	</div>		
	<div class="row">
		@foreach($posts as $row)
		<?php
		$data = SiteInstance::post()->setData($row)->structure();
		?>
		<div class="col-lg-3 col-md-6 bottommargin">
			<div class="ipost clearfix">
				<div class="entry-image">
					<a href="{{ route('front.post.detail', ['slug' => $data['slug']]) }}"><img class="image_fade" src="{{ isset($data['image_list']['small']) ? storage_url($data['image_list']['small']) : broken_image() }}" alt="{{ $data['title'] }}"></a>
				</div>
				<div class="entry-title">
					<h3><a href="{{ route('front.post.detail', ['slug' => $data['slug']]) }}">{{ $data['title'] }}</a></h3>
				</div>
				<ul class="entry-meta clearfix">
					<li><i class="icon-calendar3"></i> {{ date('d M Y H:i:s', strtotime($data['created_at'])) }}</li>
					<li><a href="{{ route('front.post.detail', ['slug' => $data['slug']]) }}"><i class="icon-comments"></i> 53</a></li>
				</ul>
				<div class="entry-content">
					<p>{{ $data['excerpt'] ? $data['excerpt'] : descriptionMaker($data['description'], 15) }}</p>
				</div>
			</div>
		</div>
		@endforeach
	</div>

	<div align="center">
		<a href="#" class="button button-xlarge button-dark button-rounded tright">
			Check Our Older Blogs <i class="icon-circle-arrow-right"></i>
		</a>
	</div>
</section>
@endif