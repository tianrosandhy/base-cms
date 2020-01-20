@extends ('main::master')

@include ('main::assets.fancybox')
@if($as_ajax)
	@include ('main::assets.dropzone')
	@include ('main::assets.cropper')
@endif

@section ('content')

<h3>{!! $title !!}</h3>


<div class="padd">
	<div class="pull-left float-xs-left">
		@if(Route::has('admin.'.$hint.'.store'))
		@if(has_access('admin.'.$hint.'.store'))
			<?php
			$hide = false;
			if(config('module-setting.'.$hint.'.hide_create')){
			    $hide=true;
	    	}
	    	?>
    	    @if(!$hide)
			<a href="{{ url()->route('admin.'.$hint.'.store') }}" class="btn btn-primary" {{ $as_ajax ? 'as-ajax' : '' }}>Add Data</a>
            @endif
    	@endif
		@endif

		@if(Route::has('admin.'.$hint.'.delete'))
		@if(has_access('admin.'.$hint.'.delete'))
		<a href="{{ url()->route('admin.'.$hint.'.delete', ['id' => 0]) }}" class="btn btn-danger batchbox multi-delete">Delete All Selected</a>
		@endif
		@endif

		{!! $ctrl_button !!}
	</div>


	@if(Route::has('admin.'.$hint.'.export') && config('module-setting.'.$hint.'.export_excel'))
	<div class="pull-right float-xs-right">
		<a href="{{ url()->route('admin.'.$hint.'.export') }}" class="btn btn-info">Export to Excel</a>
	</div>
	@endif
	<div class="clearfix"></div>
</div>

{!! $prepend_index !!}
<div class="card card-body">
	<div style="overflow-x:scroll; padding:1em 0;">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Path</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach($datatable as $theme)
				<tr>
					<td>{{ $theme->tname }}</td>
					<td>{{ $theme->tdirectory }}</td>
					<td>
						<input type="checkbox" data-init-plugin="switchery" data-size="small" name="themes-active" value="{{ ($theme->active) ? 'enable' : 'disable' }}" data-theme="{{ $theme->tname }}" {{ ($theme->active) ? 'checked' : '' }}>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>	
	</div>
</div>

{!! $append_index !!}

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
<script>
$(function(){
	$('[data-init-plugin="switchery"]').each(function() {
		var el = $(this);
		new Switchery(el.get(0), {
			size : el.data("size")
		});
	});

	$('body').on('change', 'input[name="themes-active"]', function(evt) {
		_theme = evt.currentTarget.dataset.theme;
		$.ajax({
			url : "{{ url()->route('admin.themes.set_active') }}",
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				theme : _theme,
			},
			success : function(resp) {
				console.log(resp);
			},
			error : function(resp){
			}
		});
	});
});
</script>
@endpush