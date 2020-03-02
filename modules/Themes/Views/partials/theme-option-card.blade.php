<div class="card mb-3">
    <div class="card-header">{{ ucwords($card_name) }}</div>
    <div class="card-body">
        @foreach($card_data as $field_name => $input)
            @if(isset($input->label))
            <div class="row mb-3">
                <div class="col-sm-4">
                    <h5 style="padding:.75em 0">{{ $input->label }}</h5>
                </div>
                <div class="col-sm-8">
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
            </div>
            @endif
        @endforeach
    </div>
</div>