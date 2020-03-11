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


	<div class="tab-content">
		@foreach($settings as $group => $data)
		<div class="tab-pane slide-left card card-block card-body {{ $loop->iteration == 1 ? 'show active' : '' }}" id="slide-{{ $group }}">
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


<br>
<br>
<br>
<br>


@if(has_access('admin.setting.store'))
<!-- Add new Setting -->
<div class="card">
	<div class="card-header">
		<div class="card-heading">
			Add New Setting Data
		</div>
	</div>
	<div class="card-block card-body">
		<form action="" method="post">
			{{ csrf_field() }}
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label>Group Name</label>
						<select name="group" class="form-control select2 select_custom">
							@foreach($settings as $grp => $sett)
							<option value="{{ $grp }}">{{ ucwords($grp) }}</option>
							@endforeach							
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Parameter Name</label>
						<input type="text" class="form-control" name="param" value="{{ old('param') }}">
					</div>
				</div>
			</div>
			<div class="row">

				<div class="col-sm-4">
					<div class="form-group">
						<label>Field Label</label>
						<input type="text" class="form-control" name="name" value="{{ old('name') }}">
					</div>

				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<label>Field Type</label>
						<?php
						$type = old('type');
						?>
						<select name="type" class="form-control switcher">
							<option value="text" {{ $type == 'text' ? 'selected' : '' }}>Text</option>
							<option value="number" {{ $type == 'number' ? 'selected' : '' }}>Number</option>
							<option value="textarea" {{ $type == 'textarea' ? 'selected' : '' }}>Textarea</option>
							<option value="image" {{ $type == 'image' ? 'selected' : '' }}>Image</option>
						</select>
					</div>
				</div>
				
				<div class="col-sm-4">
					<div class="form-group">
						<label>Value</label>
						<div class="value-holder">
							<div data-type="text">
								<input type="text" name="value[text]" class="form-control" value="{{ old('value.text') }}">
							</div>
							<div data-type="number" style="display:none;">
								<input data-mask="000000000000" type="number" name="value[number]" class="form-control" value="{{ old('value.number') }}">
							</div>
							<div data-type="textarea" style="display:none">
								<textarea name="value[textarea]" class="form-control" value="{{ old('value.textarea') }}"></textarea>
							</div>
							<div data-type="image" style="display:none">
								{!! MediaInstance::input('value[image]', old('value.image')) !!}
							</div>

						</div>
					</div>
				</div>
			</div>

			<div>
				<button class="btn btn-primary">Add Setting</button>
			</div>

		</form>		
	</div>

</div>
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