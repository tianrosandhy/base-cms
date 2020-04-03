<!-- Input type custom view -->
@if(view()->exists($view_source))
	@include($view_source, [
		'name' => $name,
		'data' => $data,
		'attr' => isset($attr) ? $attr : [],
	])
@else
	<div class="alert alert-danger">View <strong>{{ $view_source }}</strong> still not defined</div>
@endif