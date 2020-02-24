<?php
$hash = sha1(rand(1, 10000) . uniqid() . time());

$value_decode = json_decode($value, true);
$data = MediaInstance::imageNotFoundUrl();
if(isset($value_decode['id']) && isset($value_decode['thumb'])){
  $data = MediaInstance::getImageById($value_decode['id'], $value_decode['thumb']);
}
?>
<div class="image-input-holder card" data-hash="#{{ $hash }}">
  <input type="hidden" name="{{ $name }}" value="{{ $value }}" class="form-control" id="{{ $hash }}">
  <div class="card-body text-center">
    <img src="{{ $data }}" alt="Uploaded Image">
    <div class="image-closer">&times;</div>
  </div>
  <div class="card-footer">
    <button type="button" class="btn btn-block btn-primary media-set-image" data-target="#{{ $hash }}">Set Image</button>
  </div>
</div>