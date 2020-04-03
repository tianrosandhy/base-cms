<div class="card card-body array-item mb-3">
    <div class="custom-closer">&times;</div>
    @foreach($param->loop as $subname => $subinput)
    <div class="row mb-2">
        <div class="col-sm-4">
            {{ $subinput->label }}
        </div>
        <div class="col-sm-8">
            @if(view()->exists('themes::partials.inputtype.'.$subinput->type))
                @include ('themes::partials.inputtype.'.$subinput->type, [
                    'name' => $group.'.'.$card_name.'.'.$field_name . '.'.$subname,
                    'param' => $subinput,
                    'value' => isset($stored_group[$subname][$i]) ? $stored_group[$subname][$i] : null,
                ])
            @else
                <div class="alert alert-warning">Input view <strong>{{ $subinput->type }}</strong> doesn't exists</div>
            @endif
        </div>
    </div>
    @endforeach
</div>  