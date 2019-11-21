<li class="dd-item dd3-item close-target" data-id="{{ $list['id'] }}">
	<div class="dd-handle dd3-handle"></div>
	<div class="dd3-content">
		<a class="btn-update-menu btn-as-link" href="#">{{ $label }}</a>
		<div class="navigation-buttons">
	    	<a href="#" class="btn btn-sm btn-info btn-update-menu" title="Edit">
	    		<i class="fa fa-pencil"></i>
	    		Edit
	    	</a>
	    	<a href="#" class="btn btn-sm btn-danger delete-button" title="Delete">
	    		<i class="fa fa-trash"></i>
	    		Delete
	    	</a>
		</div>
	</div>
	@if(isset($list['submenu']))
		<?php
		$current_level++;
		?>
		@if($current_level <= $max_level)
			<ol class="dd-list">
			@foreach($list['submenu'] as $sublabel => $sublist)
				@include ('navigation::partials.nav-handle', [
					'label' => $sublabel,
					'list' => $sublist,
					'max_level' => $max_level,
					'current_level' => $current_level,
				])
			@endforeach
			</ol>
		@endif
	@endif
</li>