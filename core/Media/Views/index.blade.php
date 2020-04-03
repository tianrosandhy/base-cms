@extends ('main::master')

@include ('media::use-media', [
  'without_modal' => true
])

@section ('content')
<div class="header-box mb-0">
	<h2 class="display-4 mb-3">{{ $title }}</h2>
</div>

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