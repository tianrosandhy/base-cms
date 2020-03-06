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

<h3 class="display-4 mb-3">{{ $title }}</h3>
@if(!request()->ajax())
<div class="padd">
	<div class="pull-left">
		<a href="{{ url()->route($back) }}" class="btn btn-sm btn-default btn-secondary">&laquo; Back</a>	
	</div>
	
	<div class="clearfix"></div>
</div>
@endif

@if($multi_language)
@include ('main::inc.lang-switcher', [
	'model' => $forms->model,
	'reload' => false
])
@endif

<form action="" method="post" class="crud-post" with-loader>
	{{ csrf_field() }}
	<div class="card card-block card-body">
		@if(isset($prepend_field))
		{!! $prepend_field !!}
		@endif

		<div class="row">
			<?php $width = 0; ?>
			@foreach($forms->structure as $row)
				@if($row->hide_form == true)
					@php continue; @endphp
				@endif
				<?php
				$width += $row->form_column;
				if($width > 12){ //kalo lebarnya lebih dari 12 kolom, langsung tutup
					$width = 0;
					echo '</div><div class="row">'; //bikin baris baru
				}
				?>
				<div class="col-md-{{ $row->form_column }} col-sm-12">
					<div class="form-group custom-form-group {!! $row->input_type == 'radio' ? 'radio-box' : '' !!}">
						<label for="{{ $row->input_attribute['id'] }}" class="text-uppercase">{{ $row->name }}</label>
						<?php
						if(!isset($multi_language)){
							$multi_language = false; //default fallback
						}

						$pass_param = [
							'type' => $row->input_type,
							'name' => $row->field,
							'attr' => $row->input_attribute,
							'data' => $data,
						];


						if($multi_language){
							foreach(LanguageInstance::available(true) as $lang){
								$value[$lang['code']] = isset($data->{$row->field}) ? $data->outputTranslate($row->field, $lang['code'], true) : null;
							}
						}
						else{
							$value = isset($data->{$row->field}) ? $data->{$row->field} : null;
						}

						if($row->value_source){
							$grab_ = \DB::table($row->value_source[0])->find($row->value_source[1]);
							if($multi_language){
								$value[def_lang()] = $grab_->{$row->value_source[2]};
							}
							else{
								$value = $grab_->{$row->value_source[2]};
							}
						}
						elseif($row->array_source){
							$value = call_user_func($row->array_source, $data);
						}
						elseif($row->value_data){
							if($multi_language){
								$value[def_lang()] = call_user_func($row->value_data, $data);
							}
							else{
								$value = call_user_func($row->value_data, $data);
							}
						}

						$pass_param['value'] = $value;
						?>
						@if(in_array($row->input_type, ['text', 'number', 'password', 'email', 'color', 'richtext', 'textarea', 'gutenberg', 'image', 'image_multiple', 'tel', 'tags']))
							@includeFirst (['main::input.'.$row->input_type, 'main::input.text'] , $pass_param)
						@elseif($row->input_type == 'slug')
							<?php
							$pass_param['slug_target'] = $row->slug_target;
							?>
							@include ('main::input.slug', $pass_param)
						@elseif(in_array($row->input_type, ['date', 'time', 'datetime']))
							@includeFirst(['main::input.'.$row->input_type, 'main::input.datetime'], $pass_param)
						@elseif(in_array($row->input_type, ['file', 'file_multiple']))
							@includeFirst(['main::input'.$row->input_type, 'main::input.file'], $pass_param)
						@elseif(in_array($row->input_type, ['radio', 'checkbox']))
							<?php
							$pass_param['source'] = $row->data_source;
							?>
							@includeFirst (['main::input.'.$row->input_type, 'main::input.radio'], $pass_param)
						@elseif(in_array($row->input_type, ['select', 'select_multiple']))
							<?php
							$pass_param['source'] = $row->data_source;
							?>
							@includeFirst(['main::input.'.$row->input_type, 'main::input.select'], $pass_param)
						@else
						<div class="alert alert-danger">Input type <strong>{{ $row->input_type }}</strong> is still not created</div>
						@endif
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

		@if(isset($additional_field))
		{!! $additional_field !!}
		@endif

		@if(isset($seo))
		{!! $seo !!}
		@endif

		<div class="padd">
			<button type="submit" class="btn btn-primary">Save</button>
		</div>
			
	</div>
</form>

@stop

@push ('script')
@if($used_plugin['media'] || isset($seo))
	{!! MediaInstance::assets() !!}
@endif

<script>
var draft_interval;
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