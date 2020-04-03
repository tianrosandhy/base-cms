<!-- Sama kayak use-media, cuma ini dalam model include, bukan push2an -->
@include ('media::inc.dropzone-asset')

@if(!isset($without_modal))
  @include ('media::partials.base-modal')
@endif

@include ('media::partials.media-script')
