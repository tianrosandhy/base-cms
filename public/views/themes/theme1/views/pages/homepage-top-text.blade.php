<?php
$top_text = themeoption('homepage.summary.lists');
?>
@if(isset($top_text['title']))
<div class="row nopadding align-items-stretch">
    <?php
    $n = count(themeoption('title', $top_text));
    ?>
    @for($i=0; $i<$n; $i++)
    <div class="col-lg-{{ 12 / $n }} dark col-padding ohidden" style="background-color: {{ themeoption('background.'.$i, $top_text) }};">
        <div>
            <h3 class="uppercase" style="font-weight: 600;">{{ themeoption('title.'.$i, $top_text) }}</h3>
            <p style="line-height: 1.8;">{{ themeoption('description.'.$i, $top_text) }}</p>
            @if(isset($top_text['icon'][$i]))
            <i class="fa-fw {{ themeoption('icon.'.$i, $top_text) }} bgicon"></i>
            @endif
        </div>
    </div>
    @endfor
</div>
@endif