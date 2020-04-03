<div class="item text-center">
  <div style="position:relative;">
    <a href="#" class="image-thumb-selection" data-id="{{ $item->id }}" title="{{ datethis($item->created_at, 'd M Y H:i:s') }}" data-origin="{{ storage_url($item->path) }}" data-thumb="{{ $item->getRawThumbnailUrl('path', 'thumb') }}" data-filename="{{ $item->filename }}">
      <img src="{{ $item->getRawThumbnailUrl('path', 'thumb') }}" alt="{{ $item->filename }}" style="width:100%;">
      <div class="file-title">{{ $item->filename }}</div>
    </a>
  </div>
</div>