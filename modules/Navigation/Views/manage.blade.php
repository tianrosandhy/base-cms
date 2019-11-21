@extends ('main::master')

@push ('style')
<link rel="stylesheet" href="{{ admin_asset('vendor/jquery-nestable/jquery.nestable.min.css') }}">
<style>
	.navigation-example{
		padding:1em;
	}
	.navigation-example::after{
		content:'';
		display:block;
		position:relative;
		clear:both;
	}

	.navigation-example ul{
		display:block;
		list-style:none;
		margin:0; 
		padding:0;
	}
	
	.navigation-example ul li, .navigation-example ul a{
		display:block;
		white-space:nowrap;
	}

	.navigation-example>ul>li{
		float:left;
		position:relative;
		margin-right:5px;
	}
	.navigation-example ul>li>a{
		background:#ddd;
		padding:.5em;
		color:#000;
		text-transform:uppercase;
	}

	.navigation-example ul>li>ul{
		position:absolute;
		left:0;
		padding-top:5px;
	}

	.navigation-example ul>li>ul>li>ul{
		left:100%;
		top:0;
	}

	.navigation-example ul ul{
		display:none;
	}

	.navigation-example ul>li:hover>ul{
		display:block;
	}

	.navigation-example>ul>li>a{
		background:#222;
		color:#fff;
	}



	.dd{
		width:100%;
		max-width:initial;
	}

	.dd-handle{
		cursor:move;
		height:40px;
	}
	.dd3-content{
		height:40px;
		line-height:30px;
	}
	.dd3-handle:before{
		top:10px;
	}

	.navigation-buttons{
		float:right;
	}

	.navigation-buttons .btn{
		padding:.25em .6em!important;
		font-size:10px;
	}
	.navigation-buttons .btn i{
		font-size:10px;
	}
</style>
@endpush

@section ('content')
<h2>{{ $title }} "{{ $data->group_name }}"</h2>

<a href="{{ route('admin.navigation.index') }}" class="btn btn-sm btn-secondary">Back</a>

<div class="card card-body mt-3 nav-holder">
	
	<a href="#" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add New Menu</a>

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
</div>
@stop

@push ('script')
<script src="{{ admin_asset('vendor/jquery-nestable/jquery.nestable.min.js') }}"></script>
<script>
$(function(){
	$(".nav-nestable").each(function(){
		$(this).nestable({
			maxDepth : parseInt($(this).attr('data-level'))
		}).on('change', function(){
			serializeGroup($(this).attr('data-group'));
		});
		serializeGroup($(this).attr('data-group'), true);
	});

});

function serializeGroup(group_id, first_try){
	first_try = first_try || false;

	inputInstance = $("input[name='order-data'][data-group='"+group_id+"']");
	oldVal = inputInstance.val();

	json = $(".nav-nestable[data-group='"+group_id+"']").nestable('serialize');
	jsonData = window.JSON.stringify(json);
	inputInstance.val(jsonData);

	if(!first_try){
		if(oldVal != jsonData){
			//ada perubahan order, munculkan tombol show reorder button
			$(".reorder-btn[data-group='"+group_id+"']").slideDown();
		}
	}
}	
</script>
@endpush