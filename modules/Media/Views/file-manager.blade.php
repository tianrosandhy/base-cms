<div class="media-header">
	@foreach($links as $link)
	<a href="#" class="open-directory directory-breadcrumb" shortlink="{{ $link['target'] }}">{{ $link['name'] }}</a>
	@endforeach
</div>
<div class="media-control" style="padding:.5em; display:none;">
	<a href="#" class="btn btn-primary btn-select-all-file"><i class="fa fa-check"></i> Select All</a>
	<a href="#" class="btn btn-secondary btn-unselect-all-file"><i class="fa fa-check"></i> Unselect All</a>
	<a href="#" class="btn btn-danger btn-remove-selected"><i class="fa fa-trash"></i> Delete Selected Files</a>
</div>
<div class="media-container">
	@foreach($data as $item)
		@if($item['type'] == 'directory')
		<a class="item text-center open-directory" href="#" shortlink="{{ $item['shortlink'] }}">
			<div title="{{ $item['count'] == 0 ? 'Empty directory' : $item['count'] .' data in this directory' }}">
				<i class="fa fa-3x fa-fw fa-folder-o"></i>
			</div>
			<div class="file-title" title="{{ $item['count'] > 0 ? 'Empty directory' : $item['count'] .' data in this directory' }}">{{ $item['name'] }}</div>
		</a>
		@else
		<div class="item text-center">
			<div style="position:relative;">
				<label class="file-checker-label">
					<input type="checkbox" name="file[]" value="{{ $item['shortlink'] }}" class="file-checker" style="cursor:pointer;">
				</label>
				<?php
				$thumbnail = ImageService::getThumbnailInstance($item['shortlink'], true);
				?>
				<a data-fancybox="gallery" href="{{ Storage::url($item['shortlink']) }}" shortlink="{{ $item['shortlink'] }}" data-toggle="tooltip" data-placement="bottom" title="Size : {{ $item['filesize'] }}">
					@if($thumbnail)
					<img src="{{ storage_url($thumbnail) }}" alt="{{ $item['name'] }}" style="width:100%">
					@else
					<i class="fa fa-3x fa-fw fa-file-image-o"></i>
					@endif
					<div class="file-title">{{ $item['name'] }}</div>
				</a>
			</div>
		</div>
		@endif
	@endforeach
	@if(empty($data))
	<p class="text-mute text-center">Directory is empty or not found</p>
	@endif
</div>