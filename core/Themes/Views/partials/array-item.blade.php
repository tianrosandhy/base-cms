<div class="card card-body array-item mb-3" style="padding:1em 5em">
    <div class="custom-closer">&times;</div>
    @foreach($param->loop as $subname => $subinput)
    <div class="form-group custom-form-group">
        <label>{{ $subinput->label }}</label>
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
    @endforeach
</div>  