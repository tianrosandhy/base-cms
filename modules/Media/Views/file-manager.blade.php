<div class="media-container">
	@foreach($data as $item)
		<div class="item text-center">
			<div style="position:relative;">
				<a href="#" class="image-thumb-selection" data-id="{{ $item->id }}" title="{{ $item->filename }}" data-origin="{{ storage_url($item->path) }}" data-thumb="{{ $item->getThumbnailUrl('path', 'thumb') }}" data-filename="{{ $item->filename }}">
					<img src="{{ $item->getThumbnailUrl('path', 'thumb') }}" alt="{{ $item->filename }}" style="width:100%;">
					<div class="file-title">{{ $item->filename }}</div>
				</a>
			</div>
		</div>
	@endforeach

	@if(empty($data))
	<p class="text-mute text-center">No media data. Start <a href="#" data-toggle="tabs" data-target="#manual">upload now</a>.</p>
	@endif
</div>