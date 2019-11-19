@extends ( request()->ajax() ? "main::master-ajax" : "main::master" )
@if(!request()->ajax())
	@if($used_plugin['dropzone'])
		@include ('main::assets.dropzone')
	@endif
	@if($used_plugin['cropper'])
		@include ('main::assets.cropper')
	@endif
	
	{{-- Gutenberg itu makan resource yg besar. Daripada sembarangan diload, bikin berat mending dibikin optional --}}
	@if($used_plugin['gutenberg'])
		@push ('style')
		<link rel="stylesheet" href="{{ asset('vendor/laraberg/css/laraberg.css') }}">
		@endpush
		@push ('script')
		<script src="https://unpkg.com/react@16.8.6/umd/react.production.min.js"></script>
		<script src="https://unpkg.com/react-dom@16.8.6/umd/react-dom.production.min.js"></script>
		<script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
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
	
	@if(isset($revisions))
	<div class="pull-right">
		<a href="#revisionModal" {!! count($revisions) > 0 ? '' : 'disabled' !!} data-toggle="modal" data-target="#revisionModal" class="btn btn-primary {!! count($revisions) > 0 ? '' : 'disabled' !!} btn-manage-revision">Manage Revisions</a>
	</div>
	@endif
	<div class="clearfix"></div>
</div>
@endif

@include ('main::inc.lang-switcher', [
	'model' => $forms->model,
	'reload' => false
])

<form action="" method="post" class="crud-post">
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
						?>
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

@if(isset($revisions))
<div class="modal fade fill-in" id="revisionModal" tabindex="-1" role="dialog" aria-hidden="true">
	<button type="button" class="modal-custom-close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="text-left p-b-5 default-modal-title">Manage Revisions</h5>
			</div>
			<div class="modal-body default-modal-content">
				<table class="table">
					<thead>
						<tr>
							<th>Rev. No</th>
							<th>Last Update</th>
							<th>Data</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach(collect($revisions)->sortKeysDesc() as $no => $data)
						<tr>
							<td>{{ $no }}</td>
							<td>{{ isset($data['updated_at']) ? date('d M Y H:i:s', strtotime($data['updated_at'])) : '-' }}</td>
							<td>
								@foreach($data as $key => $value)
									@if(in_array($key, ['id', 'created_at', 'is_active']))
										@continue;
									@endif
									@if(!empty($value))
									<div class="mb-2">
										<strong>{{ $key }}</strong> : {{ $value }}
									</div>
									@endif
								@endforeach
							</td>
							<td>
								<a href="{{ route('admin.'.$hint.'.revision', ['id' => $data['id'], 'rev' => $no]) }}" class="btn btn-secondary">Restore to this version</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif

@stop

@push ('script')
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


	$(document).on('change', '.crud-post input, .crud-post select, .crud-post textarea', function(){
		saveAsDraft();
	});

	draft_interval = setInterval(function(){
		saveAsDraft();
	}, 20000);
});

function saveAsDraft(){
	data = $(".crud-post").serialize();
	data += '&force_draft=true';
	$.ajax({
		url : $(".crud-post").attr('action'),
		type : 'POST',
		dataType : 'json',
		data : data,
		success : function(resp){
			if(resp.force_draft && resp.id){
				$(".crud-post").attr('action', resp.saveurl);
				toggleSuccess();
			}
		},
		error : function(resp){
			//kalo error, gausa jalanin lagi
			clearInterval(draft_interval);
		}
	});
}

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