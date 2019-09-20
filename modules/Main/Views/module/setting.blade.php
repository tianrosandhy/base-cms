@extends ('main::master')

@include ('main::assets.dropzone')

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
<h3>Settings</h3>

@if(session('artisan'))
<div style="margin:1em 0;">
	<strong>Artisan Command Result</strong>
	<pre class="language"><code class="language">{!! session('artisan') !!}</code></pre>
</div>
@endif

@if(has_access('admin.setting.update'))

<form action="{{ url()->route('admin.setting.update') }}" method="post">
	{{ csrf_field() }}
	<ul class="nav nav-tabs nav-tabs-fillup d-md-flex d-lg-flex d-xl-flex" role="tablist">
		@foreach($settings as $group => $data)
		<li class="nav-item">
			<a href="#" class="nav-link {{ $loop->iteration == 1 ? 'active' : '' }}" data-toggle="tab" data-target="#slide-{{ $group }}"><span>{{ strtoupper($group) }}</span></a>
		</li>
		@endforeach
	</ul>


	<div class="tab-content">
		@foreach($settings as $group => $data)
		<div class="tab-pane slide-left card card-block card-body {{ $loop->iteration == 1 ? 'show active' : '' }}" id="slide-{{ $group }}">
			@foreach($data as $row)
			<div class="form-group custom-form-group searchable pos-rel close-target mt-2">
				@if(has_access('admin.setting.delete'))
				<span class="btn btn-danger close-btn delete-button" data-id="{{ $row->id }}" data-target="{{ url()->route('admin.setting.delete', ['id' => $row->id]) }}">&times;</span>
				@endif
				<label>{{ ucwords($row->name) }} - <small><mark>setting('{{ $row->group }}.{{ $row->param }}')</mark></small></label>
				@if($row->type == 'text')
				<input type="text" name="value[{{ $row->id }}]" value="{{ $row->default_value }}" class="form-control">
				@elseif($row->type == 'textarea')
				<textarea name="value[{{ $row->id }}]" class="form-control">{!! $row->default_value !!}</textarea>
				@elseif($row->type == 'image')
					@include ('main::inc.dropzone', [
						'name' => 'value['.$row->id.']',
						'value' => $row->default_value,
						'horizontal' => true
					])
				@endif
			</div>
			@endforeach
		</div>
		@endforeach
	</div>

	<div>
		<button class="btn btn-primary">Update Setting</button>
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
							<div data-type="textarea" style="display:none">
								<textarea name="value[textarea]" class="form-control" value="{{ old('value.textarea') }}"></textarea>
							</div>
							<div data-type="image" style="display:none">
								@include ('main::inc.dropzone', [
									'name' => 'value[image]',
									'value' => old('value.image')
								])
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
<script type="text/javascript" src="{{ admin_asset('assets/plugins/classie/classie.js') }}"></script>
<script>
$(function(){
	$(document).on('change', '.switcher', function(){
		type = $(this).val();
		$(".value-holder>div").slideUp();
		$(".value-holder>div[data-type='"+type+"']").slideDown();
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