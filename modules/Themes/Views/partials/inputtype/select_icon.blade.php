<select name="theme[{{ $name }}][]" class="form-control select-icon" style="width:100%"
    @if(isset($param->attr))
        @foreach($param->attr as $attr => $attrval)
            @if(in_array($attr, ['class', 'name']))    
                @continue
            @endif
            {{ $attr }}="{{ $attrval }}"
        @endforeach
    @endif
>
    <?php
    if(LanguageInstance::isActive()){
        $selected = isset($value[def_lang()]) ? $value[def_lang()] : (isset($param->default) ? $param->default : null);
    }
    else{
        $selected = $value ? $value : (isset($param->default) ? $param->default : null);
    }

    $file = file_get_contents(public_path('admin_theme/vendor/font-awesome/lists.txt'));
    $icon_lists = explode("\n", $file);
    ?>
    <option value="">No Icon</option>
    @foreach($icon_lists as $icon)
        <?php $icon = trim($icon); ?>
        <option value="fa fa-{{ $icon }}" {{ $selected == 'fa fa-'.$icon ? 'selected' : '' }} data-icon="fa-{{ $icon }}">{{ $icon }}</option>
    @endforeach
</select>