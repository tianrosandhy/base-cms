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
<?php
$dictionary = __($aliases['translation_module'].'::module.'.$aliases['translation_name']);
$config = config('module-setting.'.$aliases['config']);
?>
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
		@if(isset($prepend_form))
			@if(strlen(trim($prepend_form)) > 0)
			<div class="card card-body">
				{!! $prepend_form !!}
			</div>
			@endif
		@endif

		<?php 
		$tabs = array_unique(array_pluck($forms->structure, 'tab_group'));
		?>
		@if(count($tabs) > 0)
		<div class="card">
			@if(count($tabs) > 1 || isset($seo))
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				@foreach($tabs as $tabname)
					<li class="nav-item">
						<a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ slugify($tabname) }}-tab" data-toggle="tab" href="#form-tab-{{ slugify($tabname) }}" role="tab">{{ $tabname }}</a>
					</li>
				@endforeach
				@if(isset($seo))
				<li class="nav-item">
					<a href="#form-tab-seo" class="nav-link" id="seo-tab" data-toggle="tab" role="tab">SEO</a>
				</li>
				@endif
			</ul>
			@endif
		</div>
		<div class="tab-content card" id="myTabContent">
			@foreach($tabs as $tabname)
			<div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="form-tab-{{ slugify($tabname) }}" role="tabpanel">
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
			@if(isset($seo))
			<div class="tab-pane fade" id="form-tab-seo" role="tabpanel">
				{!! $seo !!}
			</div>
			@endif
		</div>			
		@endif


		@if(isset($append_form))
			@if(strlen(trim($append_form)) > 0)
			<div class="card card-body">
				{!! $append_form !!}
			</div>
			@endif
		@endif

		<div class="save-buttons">
			<?php
			$always_exit = $config['save_always_exit'] ?? false;
			?>
			<button type="submit" {!! $always_exit ? '' : 'name="save_only"' !!} value="1" class="btn btn-lg btn-success"><i class="fa fa-save"></i> {{ $dictionary['save_button'] ?? 'Save' }}</button>
			@if(!$always_exit)
			<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-save"></i> {{ $dictionary['save_and_exit_button'] ?? 'Save & Exit' }}</button>
			@endif
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

	$("input, select, textarea").on('change', function(){
		startNavigate();
	});

	$("form").on('submit', function(){
		clearNavigate();
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

	var save_button_trigger = $(".save-buttons").offset().top;;
	$(".save-buttons").addClass('stick');
	$(window).on('scroll', $.debounce(100, function(){
		scrollpos = $(window).scrollTop() + $(window).height() - 400;
		if(scrollpos > save_button_trigger){
			$(".save-buttons.stick").removeClass('stick');
		}
		else{
			$(".save-buttons:not(.stick)").addClass('stick');
		}
	}));
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

function clearNavigate(){
	$(window).off('beforeunload');
}

function startNavigate(){
	console.log('startNavigate called');
	$(window).on('beforeunload', function(){
		return 'Are you sure you want to leave?';
	});
}
</script>
@endpush