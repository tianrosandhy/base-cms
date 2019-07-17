@extends ("main::master")
@include ('main::assets.dropzone')
@include ('main::assets.cropper')
<?php
if(!isset($multi_language)){
	$multi_language = false; //default fallback
}
?>
@section ('content')

<h3>{{ $title }}</h3>
<div class="padd">
	<a href="{{ url()->route($back) }}" class="btn btn-sm btn-default btn-secondary">&laquo; Back</a>
</div>

@include ('main::inc.lang-switcher', [
	'model' => $forms->model,
	'reload' => false
])

<form action="" method="post">
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
						@if($multi_language)
							@include ('main::inc.dynamic_input_multilanguage')
						@else
							@include ('main::inc.dynamic_input_singlelanguage')
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
<script>
$(function(){
	$('.radio-box').each(function(){
		setFormGroupBg($(this).find('input:checked'));
	});

	$(document).on('change', '.radio-box input:checked', function(){
		setFormGroupBg($(this));
	});

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