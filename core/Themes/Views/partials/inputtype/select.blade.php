<div class="form-group custom-form-group">
    <?php
    if(LanguageInstance::isActive()){
        $selected = isset($value[def_lang()]) ? $value[def_lang()] : (isset($param->default) ? $param->default : null);
    }
    else{
        $selected = $value ? $value : (isset($param->default) ? $param->default : null);
    }

    if(isset($param->source->as_model)){
        $datasource = new \Core\Main\Http\Repository\CrudRepository($param->source->model);
        if(isset($param->source->filter)){
            $datasource = $datasource->filter($param->source->filter);
        }
        $source = [];
        foreach($datasource as $row){
            $source[$row->{$param->source->key}] = $row->{$param->source->label};
        }
    }
    else{
        $source = $param->source;
    }
    ?>
    <select name="theme[{{ $name }}][]" class="form-control theme-select2" style="width:100%; border:1px solid #ccc;"
        @if(isset($param->attr))
            @foreach($param->attr as $attr => $attrval)
                @if(in_array($attr, ['class', 'name']))    
                    @continue
                @endif
                {{ $attr }}="{{ $attrval }}"
            @endforeach
        @endif
    >
        <option value=""></option>
        @foreach($source as $key => $value)
            <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select>    
</div>
