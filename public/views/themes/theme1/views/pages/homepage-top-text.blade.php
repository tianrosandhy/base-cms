<?php
$top_text = themeoption('homepage.summary');
?>
@if(isset($top_text['title']))
<div class="row nopadding align-items-stretch">
    <?php
    $n = count($top_text['title']);
    ?>
    @for($i=0; $i<$n; $i++)
    <div class="col-lg-{{ 12 / $n }} dark col-padding ohidden" style="background-color: {{ $top_text['background'][$i] }};">
        <div>
            <h3 class="uppercase" style="font-weight: 600;">{{ $top_text['title'][$i] }}</h3>
            <p style="line-height: 1.8;">{{ $top_text['description'][$i] }}</p>
            @if(isset($top_text['icon'][$i]))
            <i class="{{ $top_text['icon'][$i] }} bgicon"></i>
            @endif
        </div>
    </div>
    @endfor

</div>
@endif