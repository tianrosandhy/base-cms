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
    $selected = $value ? $value : (isset($param->default) ? $param->default : null);

    $file = file_get_contents('admin_theme\vendor\font-awesome\lists.txt');
    $icon_lists = explode("\n", $file);
    ?>
    <option value="">No Icon</option>
    @foreach($icon_lists as $icon)
        <option value="fa fa-{{ $icon }}" {{ $selected == 'fa fa-'.$icon ? 'selected' : '' }} data-icon="fa-{{ $icon }}">{{ $icon }}</option>
    @endforeach
</select>