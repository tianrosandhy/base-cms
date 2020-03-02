@if(config('cms.lang.active'))
<input 
    type="color" 
    class="form-control" 
    name="theme[{{ $name }}][]" 
    value="{{ isset($value[def_lang()]) ? $value[def_lang()] : (isset($param->default) ? $param->default : null) }}"
    @if(isset($param->attr))
        @foreach($param->attr as $attr => $attrval)
            @if(in_array($attr, ['type', 'class', 'name', 'value']))    
                @continue
            @endif
            {{ $attr }}="{{ $attrval }}"
        @endforeach
    @endif
>
@else
<input 
    type="color" 
    class="form-control" 
    name="theme[{{ $name }}][]" 
    value="{{ $value ? $value : (isset($param->default) ? $param->default : null) }}"
    @if(isset($param->attr))
        @foreach($param->attr as $attr => $attrval)
            @if(in_array($attr, ['type', 'class', 'name', 'value']))    
                @continue
            @endif
            {{ $attr }}="{{ $attrval }}"
        @endforeach
    @endif
>
@endif