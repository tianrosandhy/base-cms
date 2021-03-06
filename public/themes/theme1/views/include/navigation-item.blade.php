<?php
$has_submenu = isset($data['submenu']); 
$is_active = url($data['url']) == url()->current();
$alias = slugify($label);
$level++;
?>
<li class="{{ $is_active ? 'active' : '' }} {{ $has_submenu ? 'dropdown' : '' }}">
	<a 
		class="{{ $has_submenu ? 'dropdown-toggle' : '' }}" 
		href="{{ $has_submenu ? '#' : $data['url'] }}"
		@if($has_submenu)
		id="{{ $alias }}-navigation" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
		@endif
		@if($data['new_tab'])
		target="_blank"
		@endif
	>
		@if($data['icon'])
		<i class="fa-fw {{ $data['icon'] }}"></i>
		@endif
		{{ $label }}
	</a>
	@if($has_submenu)
		<ul>
		@foreach($data['submenu'] as $sublabel => $subdata)
			@include('include.navigation-item', [
				'label' => $sublabel,
				'data' => $subdata,
				'level' => $level
			])
		@endforeach
		</ul>
	@endif
</li>
