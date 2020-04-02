@extends ( request()->ajax() ? "main::master-ajax" : "main::master" )
@if(!request()->ajax())
	@if($used_plugin['dropzone'])
		@include ('main::assets.dropzone')
	@endif
	@if($used_plugin['cropper'])
		@include ('main::assets.cropper')
	@endif
	@if($used_plugin['richtext'])
		@include ('main::assets.tinymce')
	@endif
	
	{{-- Gutenberg itu makan resource yg besar. Daripada sembarangan diload, bikin berat mending dibikin optional --}}
	@if($used_plugin['gutenberg'])
		@push ('style')
		<link rel="stylesheet" href="{{ asset('admin_theme/vendor/laraberg/css/laraberg.css') }}">
		@endpush
		@push ('script')
		<script src="{{ asset('admin_theme/vendor/react/react.production.min.js') }}"></script>
		<script src="{{ asset('admin_theme/vendor/react/react-dom.production.min.js') }}"></script>
		<script src="{{ asset('admin_theme/vendor/laraberg/js/laraberg.js') }}"></script>
		@endpush
	@endif
@endif

@section ('content')
<div class="header-box">
	<h3 class="display-4 mb-3">{{ $title }}</h3>
	@if(!request()->ajax())
	<div class="padd">
		<div class="pull-left">
			<a href="{{ url()->route($back) }}" class="btn btn-sm btn-default btn-secondary">&laquo; Back</a>	
		</div>
		
		<div class="clearfix"></div>
	</div>
	@endif
</div>

@if($multi_language)
@include ('main::inc.lang-switcher', [
	'model' => $forms->model,
	'reload' => false
])
@endif

<div class="content-box">
	<form action="" method="post" class="crud-post" with-loader>
		{{ csrf_field() }}
		<div class="card card-block card-body">
			@if(isset($prepend_field))
			{!! $prepend_field !!}
			@endif

			<?php 
			$tabs = array_unique(array_pluck($forms->structure, 'tab_group'));
			?>
			@if(count($tabs) > 0)

			<ul class="nav nav-tabs" id="myTab" role="tablist">
				@foreach($tabs as $tabname)
			  <li class="nav-item">
			    <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ slugify($tabname) }}-tab" data-toggle="tab" href="#form-tab-{{ slugify($tabname) }}" role="tab">{{ $tabname }}</a>
			  </li>
			  @endforeach
			</ul>
			<div class="tab-content card" id="myTabContent">
				@foreach($tabs as $tabname)
			  <div class="tab-pane card-body fade {{ $loop->first ? 'show active' : '' }}" id="form-tab-{{ slugify($tabname) }}" role="tabpanel">
			  	<div class="row">
		  		<?php
		  		$width = 0;
		  		?>
					@foreach(collect($forms->structure)->where('tab_group', $tabname) as $row)
						@if($row->hide_form == true)
							@php continue; @endphp
						@endif
						<?php
						$width += $row->form_column;
						if($width > 12){ //kalo lebarnya lebih dari 12 kolom, langsung tutup
							$width = 0;
							echo '</div><div class="row">'; //bikin baris baru
						}

						if(isset($data->id)){
							$validation_rule = $row->update_validation;
						}
						else{
							$validation_rule = $row->create_validation;
						}
						?>
						<div class="col-md-{{ $row->form_column }} col-sm-12">
							<div class="form-group custom-form-group {!! $row->input_type == 'radio' ? 'radio-box' : '' !!}">
								<label for="{{ $row->input_attribute['id'] }}" class="text-uppercase {{ strpos($validation_rule, 'required') !== false ? 'required' : '' }}">{{ $row->name }}</label>
								{!! $row->createInput($data, $multi_language) !!}
							</div>
						</div>
						<?php
						if($width == 12){
							$width = 0;
							echo '</div><div class="row">'; //bikin baris baru
						}
						?>
					@endforeach
					</div>
			  </div>
			  @endforeach
			</div>			
			@endif



			@if(isset($additional_field))
			{!! $additional_field !!}
			@endif

			@if(isset($seo))
			{!! $seo !!}
			@endif

			<div class="padd">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
			</div>
				
		</div>
	</form>	
</div>



@stop

@push ('script')
@if($used_plugin['media'] || isset($seo))
	{!! MediaInstance::assets() !!}
@endif

<script>
$(function(){
	$('.radio-box').each(function(){
		setFormGroupBg($(this).find('input:checked'));
	});

	$(document).on('change', '.radio-box input:checked', function(){
		setFormGroupBg($(this));
	});

	var inc = 1;
	if($("[data-gutenberg]").length){
		$("[data-gutenberg]").each(function(){
			obj = $(this);
			obj.attr('id', 'gutenberg-' + inc);
			Laraberg.init('gutenberg-'+inc, { 
				laravelFilemanager: { 
					prefix: '/laravel-filemanager' 
				} 
			});
			inc++;
		});
	}

});


function setFormGroupBg(instance){
	boxval = instance.val();
	if(boxval == 0){
		instance.closest('.radio-box').addClass('danger');
		instance.closest('.radio-box').removeClass('success');
	}
	else if(boxval == 1){
		instance.closest('.radio-box').addClass('success');
		instance.closest('.radio-box').removeClass('danger');
	}
}
</script>
@endpush