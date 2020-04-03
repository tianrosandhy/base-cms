<div class="card mb-3">
    <div class="card-header">{{ ucwords($card_name) }}</div>
    <div class="card-body">
        @foreach($card_data as $field_name => $input)
            @if(isset($input->label))
            <div class="form-group custom-form-group mt-2">
                <label>{{ $input->label }}</label>
                @if(view()->exists('themes::partials.inputtype.'.$input->type))
                    @include ('themes::partials.inputtype.'.$input->type, [
                        'name' => $group.'.'.$card_name.'.'.$field_name,
                        'param' => $input,
                        'value' => ThemesInstance::grabRaw($group.'.'.$card_name.'.'.$field_name)
                    ])
                @else
                    <div class="alert alert-warning">Input view <strong>{{ $input->type }}</strong> doesn't exists</div>
                @endif
            </div>
            @endif
        @endforeach
    </div>
</div>