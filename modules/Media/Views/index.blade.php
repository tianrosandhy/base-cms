@extends ('main::master')

@include ('media::use-media', [
  'without_modal' => true
])

@section ('content')
<h2 class="mb-3">{{ $title }}</h2>

<div class="card card-body">
  @include ('media::partials.base-media')
</div>
@stop

@push ('script')
<script>
var PREVIEW_ONLY = true;
$(function(){
  openPage();
  initMonthpicker();
});
</script>
@endpush