<?php
$value = $value ? $value : (isset($param->default) ? $param->default : null);
?>


<div style="padding:.75em 0;">
    <label class="yes_label" style="padding:0 .5em">
        <input type="radio" name="theme[{{ $name }}][]" value="1" {{ $value ? 'checked' : '' }}> <span>Yes</span>
    </label>
    <label class="no_label" style="padding:0 .5em">
        <input type="radio" name="theme[{{ $name }}][]" value="0" {{ $value ? '' : 'checked' }}> <span>No</span>
    </label>
</div>
