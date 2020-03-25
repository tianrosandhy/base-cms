@if(isset($tags))
<?php
$listtags = explode(',', $tags);
?>
<div class="tagcloud clearfix bottommargin">
	@foreach($listtags as $tg)
	<a href="#" class="badge badge-primary">{{ trim($tg) }}</a>
	@endforeach
</div>
@endif