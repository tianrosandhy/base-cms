@if($theme_option)
<div class="card">
    <ul class="nav nav-tabs nav-tabs-fillup d-md-flex d-lg-flex d-xl-flex" role="tablist">
        @foreach($theme_option as $group => $basedata)
            <li class="nav-item">
                <a href="#" class="nav-link {{ $loop->iteration == 1 ? 'active' : '' }}" data-toggle="tab" data-target="#slide-{{ $group }}"><span>{{ strtoupper($group) }}</span></a>
            </li>
        @endforeach
    </ul>
</div>
<div class="tab-content card">
    @foreach($theme_option as $group => $basedata)
    <div class="tab-pane slide-left {{ $loop->iteration == 1 ? 'show active' : '' }}" id="slide-{{ $group }}">
        @foreach($basedata as $card_name => $card_data)
            @include ('themes::partials.theme-option-card')
        @endforeach
    </div>
    @endforeach
</div>
@else
<div class="alert alert-danger">No theme option for this theme.</div>
@endif