@include ('media::inc.dropzone-asset')

@push ('modal')
@if(!isset($without_modal))
  @include ('media::partials.base-modal')
@endif
@endpush

@push ('script')
  @include ('media::partials.media-script')
@endpush