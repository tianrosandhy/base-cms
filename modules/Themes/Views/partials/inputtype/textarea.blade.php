<textarea 
    class="form-control" 
    name="theme[{{ $name }}][]" 
    @if(isset($param->attr))
        @foreach($param->attr as $attr => $attrval)
            @if(in_array($attr, ['class', 'name']))    
                @continue
            @endif
            {{ $attr }}="{{ $attrval }}"
        @endforeach
    @endif
>{{ $value ? $value : (isset($param->default) ? $param->default : null) }}</textarea>