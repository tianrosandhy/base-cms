@extends ('main::master')

@include ('main::assets.fancybox')
@if($as_ajax)
	@include ('main::assets.dropzone')
	@include ('main::assets.cropper')
@endif

@section ('content')
<?php
$dictionary = __($aliases['translation_module'].'::module.'.$aliases['translation_name']);
?>
<div class="header-box">
	<h3 class="display-4 mb-3">{!! $title !!}</h3>

	<div class="padd">
		<div class="pull-left float-xs-left">
			@if(Route::has('admin.'.$aliases['route'].'.store'))
			@if(has_access('admin.'.$aliases['route'].'.store'))
				<?php
				$hide = false;
				if(config('module-setting.'.$aliases['config'].'.hide_create')){
				    $hide=true;
		    	}
		    	?>
	    	    @if(!$hide)
				<a href="{{ url()->route('admin.'.$aliases['route'].'.store') }}" class="btn btn-primary" {{ $as_ajax ? 'as-ajax' : '' }}>
					{{ $dictionary['create_button'] ?? 'Add Data' }}
				</a>
	            @endif
	    	@endif
			@endif

			@if(Route::has('admin.'.$aliases['route'].'.delete'))
			@if(has_access('admin.'.$aliases['route'].'.delete'))
			<a href="{{ url()->route('admin.'.$aliases['route'].'.delete', ['id' => 0]) }}" class="btn btn-danger batchbox multi-delete">{{ $dictionary['delete_batch_button'] ?? 'Delete All Selected' }}</a>
			@endif
			@endif

			{!! $ctrl_button !!}
		</div>


		@if(Route::has('admin.'.$aliases['route'].'.export') && config('module-setting.'.$aliases['config'].'.export_excel'))
		<div class="pull-right float-xs-right">
			<a href="{{ url()->route('admin.'.$aliases['route'].'.export') }}" class="btn btn-info">{{ $dictionary['export_button'] ?? 'Export to Excel' }}</a>
		</div>
		@endif
		<div class="clearfix"></div>
	</div>	
</div>

<div class="content-box">
	{!! $prepend_index !!}
	{!! $datatable->view() !!}
	{!! $append_index !!}
</div>

<div class="modal fade fill-in" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<button type="button" title="Click to Dismiss" class="modal-custom-close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body default-modal-content">

			</div>
		</div>
	</div>
</div>

@stop

@push ('script')
@include ('main::assets.datatable', [
	'url' => url()->route('admin.'.$aliases['route'].'.datatable')
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
@if($as_ajax)
{!! MediaInstance::assets() !!}
<script>
$(function(){
	$(document).on('click', "[as-ajax], [body-ajax] .edit-btn", function(e){
		e.preventDefault();
		//load from ajax
		href = $(this).attr('href');
		showLoading();
		data = $.get(href, function(data){
			$("#form-modal .modal-body").html(data);
			$("#form-modal .modal-body form").attr('action', href);
			$("#form-modal").modal({
				backdrop: 'static',
				keyboard : false
			});
			if(typeof initPlugin == 'function'){
				initPlugin();
			}
			if(typeof refreshDropzone == 'function'){
				refreshDropzone();
			}
		}).always(function(){
			hideLoading();
		});
	});

	$(document).on('submit', "#form-modal .modal-body form", function(e){
		e.preventDefault();
		method = $(this).attr('method');
		if(!method){
			method = 'GET';
		}
		showLoading();
		$.ajax({
			url : $(this).attr('action'),
			type : method,
			dataType : 'json',
			data : $(this).serialize(),
			success : function(resp){
				hideLoading();
				if(resp.type == 'success'){
					$("#form-modal").modal('hide');
					toggleSuccess();
				}
			},
			error : function(resp){
				error_handling(resp);
			}
		});
	});

});


</script>
@endif
@endpush