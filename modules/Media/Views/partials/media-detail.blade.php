<?php
$shown = isset($data['shown']) ? (bool)$data['shown'] : false;
?>
<div class="media-detail" {!! $shown ? '' : 'style="display:none;"' !!}>
  <div class="image-closer">&times;</div>
  <div class="row">
    <div class="col-2 text-right">
      <img class="image-thumbnail" src="{{ isset($data['thumbnail']) ? $data['thumbnail'] : admin_asset('img/broken-image.jpg') }}" style="max-width:100%; width:80px;">
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