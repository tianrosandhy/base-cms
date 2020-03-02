@if(LanguageInstance::isActive())
  @foreach(available_lang(true) as $lang => $langdata)
    <div class="input-language" data-lang="{{ $lang }}">
    <input 
        type="text" 
        class="form-control" 
        name="theme[{{ $name }}][{{ $lang }}][]" 
        value="{{ isset($value[$lang]) ? $value[$lang] : (isset($param->default) ? $param->default : null) }}"
        @if(isset($param->attr))
            @foreach($param->attr as $attr => $attrval)
                @if(in_array($attr, ['type', 'class', 'name', 'value']))    
                    @continue
                @endif
                {{ $attr }}="{{ $attrval }}"
            @endforeach
        @endif
    >
    </div>
  @endforeach
@else
<input 
    type="text" 
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