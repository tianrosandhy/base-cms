@extends ('main::master')

@push ('style')
<style>
	pre.language{
		background:#222;
		color:#fff;
		padding:1em;
	}
</style>
@endpush

@section ('content')
<div class="padd">
	<h3 class="display-4 mb-3">Settings</h3>
</div>

@if(session('artisan'))
<div style="margin:1em 0;">
	<strong>Artisan Command Result</strong>
	<pre class="language"><code class="language">{!! session('artisan') !!}</code></pre>
</div>
@endif

@if(has_access('admin.setting.update'))

<form action="{{ url()->route('admin.setting.update') }}" method="post">
	{{ csrf_field() }}
	<div class="card">
		<ul class="nav nav-tabs nav-tabs-fillup d-md-flex d-lg-flex d-xl-flex" role="tablist">
			@foreach($settings as $group => $data)
				@if($group != 'hide')
				<li class="nav-item">
					<a href="#" class="nav-link {{ $loop->iteration == 1 ? 'active' : '' }}" data-toggle="tab" data-target="#slide-{{ $group }}"><span>{{ strtoupper($group) }}</span></a>
				</li>
				@endif
			@endforeach
		</ul>
	</div>


	<div class="tab-content card">
		@foreach($settings as $group => $data)
		<div class="tab-pane slide-left {{ $loop->iteration == 1 ? 'show active' : '' }}" id="slide-{{ $group }}">
			@if($group != 'hide')
				@foreach($data as $row)
				@if($row['type'] == 'select' && !isset($row['source']))
					@continue
				@endif
				<?php
				$input_name = $row['group'].'.'.$row['param'];
				$value_filled = isset($row['default_value']) ? $row['default_value'] : null;
				$attr = '';
				$attribute = [];
				if(isset($row['attribute'])){
					foreach($row['attribute'] as $att => $attval){
						$attr.= $att.'='.'"'.$attval.'" ';
					}
					$attribute = $row['attribute'];
				}
				?>
				<div class="form-group custom-form-group searchable pos-rel close-target mt-2">
					@if(has_access('admin.setting.delete') && isset($row['id']))
					<span class="btn btn-danger close-btn delete-button" data-id="{{ $row['id'] }}" data-target="{{ url()->route('admin.setting.delete', ['id' => $row['id']]) }}">&times;</span>
					@endif
					<label>{{ ucwords($row['name']) }} - <small><mark>setting('{{ $row['group'] }}.{{ $row['param'] }}')</mark></small></label>

					@if($row['type'] == 'number')
					<input {!! $attr !!} data-mask="000000000000" type="number" name="value[{{ $input_name }}]" value="{{ $value_filled }}" class="form-control">
					@elseif($row['type'] == 'textarea')
					<textarea {!! $attr !!}  name="value[{{ $input_name }}]" class="form-control">{!! $value_filled !!}</textarea>
					@elseif($row['type'] == 'image')
					<div>
						{!! MediaInstance::input('value['.$input_name.']', $value_filled) !!}
					</div>
					@elseif($row['type'] == 'select')
					<select {!! $attr !!} name="value[{{ $input_name }}]" class="form-control select2">
						<option value=""></option>
						<?php
						$ssource = [];
						if(is_callable($row['source'])){
							$ssource = call_user_func($row['source']);
						}
						else if(is_array($row['source'])){
							$ssource = $row['source'];
						}
						?>
						@foreach($ssource as $select_index => $select_value)
						<option value="{{ $select_index }}" {{ $select_index == $value_filled ? 'selected' : '' }}>{{ $select_value }}</option>
						@endforeach
					</select>
					@else
					<input {!! $attr !!} type="text" name="value[{{ $input_name }}]" value="{{ $value_filled }}" class="form-control">
					@endif
				</div>
				@endforeach
			@endif
		</div>
		@endforeach
	</div>

	<div class="card card-body">
		<button class="btn btn-lg btn-primary"><i class="fa fa-save"></i> Update Setting</button>
	</div>
</form>
		
@endif




@if(has_access('admin.maintenance.artisan'))
<div class="card card-block card-body mt-5">
	<h4>Artisan Runner</h4>
	<form action="{{ route('admin.maintenance.artisan') }}" method="post">
		{{ csrf_field() }}
		<div class="alert alert-warning">
			<strong>Danger Zone.</strong>
			Make sure you know what are you really doing here. For development only
		</div>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">php artisan</span>
			</div>
			<input type="text" name="key" class="form-control" placeholder="Put your artisan command here">
			<div class="input-group-btn">
				<button class="btn btn-danger">Run Command</button>
			</div>
		</div>
	</form>
</div>
@endif

@stop


@push ('script')
{!! MediaInstance::assets() !!}

<script>
$(function(){
	$(document).on('change', '.switcher', function(){
		type = $(this).val();
		$(".value-holder>div").hide();
		$(".value-holder>div[data-type='"+type+"']").show();
	});

	$(".select_custom").select2({
        tags: true,
        width: 'resolve',
        placeholder: 'Select Existing Group or Create New'
    });
    $(".select_custom").val('').trigger('change');
})
</script>
@endpush