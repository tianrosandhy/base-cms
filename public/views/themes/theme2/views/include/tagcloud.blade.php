@if(isset($tags))
<?php
$listtags = explode(',', $tags);
?>
<div class="tagcloud clearfix bottommargin">
	@foreach($listtags as $tg)
	<a href="#">{{ trim($tg) }}</a>
	@endforeach
</div>
@endif