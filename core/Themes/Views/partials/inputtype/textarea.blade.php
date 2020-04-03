@if(LanguageInstance::isActive())
  @foreach(available_lang(true) as $lang => $langdata)
    <div class="input-language" data-lang="{{ $lang }}">
    <textarea 
        class="form-control" 
        name="theme[{{ $name }}][{{ $lang }}][]" 
        @if(isset($param->attr))
            @foreach($param->attr as $attr => $attrval)
                @if(in_array($attr, ['class', 'name']))    
                    @continue
                @endif
                {{ $attr }}="{{ $attrval }}"
            @endforeach
        @endif
    >{{ isset($value[$lang]) ? $value[$lang] : (isset($param->default) ? $param->default : null) }}</textarea>
    </div>
  @endforeach
@else
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
@endif