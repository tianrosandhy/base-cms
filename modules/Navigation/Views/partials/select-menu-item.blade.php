<?php
$level_label = '';
for($i=0; $i<=$level; $i++){
	$level_label .= '--';
}
?>
<option value="{{ $param['id'] }}" {{ $selected == $param['id'] ? 'selected' : '' }}>{{ $level_label }} {{ $label }}</option>
@if(isset($param['submenu']))
	<?php
	$level++;
	?>
	@foreach($param['submenu'] as $sublabel => $subparam)
		@include ('navigation::partials.select-menu-item', [
			'label' => $sublabel,
			'param' => $subparam,
			'level' => $level,
			'selected' => $selected
		])
	@endforeach
@endif
