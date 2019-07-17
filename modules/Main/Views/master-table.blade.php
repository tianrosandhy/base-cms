@extends ('main::master')

@include ('main::assets.fancybox')

@section ('content')

<h3>{!! $title !!}</h3>


<div class="padd">
	<div class="pull-left float-xs-left">
		@if(Route::has('admin.'.$hint.'.store'))
		@if(has_access('admin.'.$hint.'.store'))
		<a href="{{ url()->route('admin.'.$hint.'.store') }}" class="btn btn-primary">Add Data</a>
		@endif
		@endif

		@if(Route::has('admin.'.$hint.'.delete'))
		@if(has_access('admin.'.$hint.'.delete'))
		<a href="{{ url()->route('admin.'.$hint.'.delete', ['id' => 0]) }}" class="btn btn-danger batchbox multi-delete">Delete All Selected</a>
		@endif
		@endif

		<a href="#" class="btn btn-info btn-sm table-search-btn active"><i class="fa fa-sm fa-search fa-fw"></i> <span>Show</span> Search Box</a>
	</div>


	@if(Route::has('admin.'.$hint.'.export') && config('module-setting.'.$hint.'.export_excel'))
	<div class="pull-right float-xs-right">
		<a href="{{ url()->route('admin.'.$hint.'.export') }}" class="btn btn-info">Export to Excel</a>
	</div>
	@endif
	<div class="clearfix"></div>
</div>



{!! $datatable->view() !!}


@stop

@push ('script')
@include ('main::assets.datatable', [
	'url' => url()->route('admin.'.$hint.'.datatable')
])
<script>
	$(".table-search-btn").on('click', function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(".table-search-filter").slideDown();
			$(this).find('span').html('Hide');
		}
		else{
			$(this).addClass('active');
			$(".table-search-filter").slideUp();
			$(this).find('span').html('Show');
		}
	});
</script>
@endpush