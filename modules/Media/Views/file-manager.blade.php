<div class="media-container">
  <div class="media-preview" style="overflow-y:scroll; max-height:300px;">
    @each('media::partials.preview-thumbnail', $data, 'item')

    @if($data->count() == 0)
    <p style="cursor:pointer;" class="text-mute text-center" onclick="goToUpload()">No media data. Start upload now.</p>
    @endif
  </div>
</div>