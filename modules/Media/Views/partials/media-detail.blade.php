<?php
$shown = isset($data['shown']) ? (bool)$data['shown'] : false;
?>
<div class="media-detail" {!! $shown ? '' : 'style="display:none;"' !!}>
  <div class="image-closer" style="font-size:14px;">Back to Gallery</div>
  <div class="row">
    <div class="col-2 text-right">
      <img class="image-thumbnail" src="{{ isset($data['thumbnail']) ? $data['thumbnail'] : admin_asset('img/broken-image.jpg') }}" style="max-width:100%; width:80px;">
      <div class="padd">
        <a class="badge badge-danger d-block btn-remove-media" data-id="{{ isset($data['id']) ? $data['id'] : null }}" style="font-size:11px; cursor:pointer; color:#fff;" title="Delete this Image">
          <i class="fa fa-trash"></i> Delete
        </a>
      </div>
    </div>
    <div class="col-10">
      <div class="mb-2">
        <strong class="filename">{{ isset($data['filename']) ? $data['filename'] : '-' }}</strong>
      </div>
      <div class="mb-2">
        <span class="url">
          <a href="{{ isset($data['url']) ? $data['url'] : url('/') }}" target="_blank">{{ isset($data['url']) ? $data['url'] : url('/') }}</a>
        </span>
      </div>
      <div class="form-group custom-form-group" style="max-width:300px;">
        <label>Select Thumbnail Size</label>
        <select class="form-control thumbnail_size">
          <option value="origin">Origin</option>
          @foreach(config('image.thumbs') as $name => $width)
          <option value="{{ $name }}">{{ ucwords($name) }} (Max Width : {{ $width }}px)</option>
          @endforeach
        </select>
      </div>
      <input type="hidden" id="media-selected-id" value="">
      <button id="set-this-image" class="btn btn-primary">Use This Image</button>
    </div>
  </div>
</div>