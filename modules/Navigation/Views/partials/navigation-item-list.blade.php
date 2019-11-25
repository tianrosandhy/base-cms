@if(!empty($structure))
	<small class="text-mute text-info">
		<em>Click and drag left handle to reorder</em>
	</small>
    <div class="dd nav-nestable" data-group="{{ $data->id }}" data-level="{{ intval($data->max_level) + 1 }}">
        <ol class="dd-list">
        	@foreach($structure as $label => $list)
                @include ('navigation::partials.nav-handle', [
                	'label' => $label,
                	'list' => $list,
                	'max_level' => $data->max_level,
                	'current_level' => 0
                ])
            @endforeach
        </ol>
    </div>
    <input type="hidden" readonly name="order-data" data-group="{{ $data->id }}">

    <div class="padd reorder-btn" style="display:none;" data-group="{{ $data->id }}">
    	<div href="#" data-group="{{ $data->id }}" class="btn btn-primary">Save Order Data</div>
    </div>
@else
	<div class="alert alert-warning">No navigation item data yet. Click [Add Menu] button above to start create new menu in <strong>{{ $data->group_name }}</strong></div>
@endif