<?php
$base_class = ['form_control', 'select2'];
if(isset($attr['class'])){
  $class = $attr['class'];
}
if(isset($class)){
  $base_class = array_merge($base_class, $class);
}

$cleaned_name = str_replace('[]', '', $name);
$value = isset($value) ? $value : null;
$type = isset($type) ? $type : 'select';

if(is_array($source)){
  $data_source = $source;
}
else{
  $data_source = isset($source->output) ? $source->output : $source;
  if(is_callable($data_source)){
    $data_source = call_user_func($data_source, $data);
  }
}

if($type == 'select_multiple' && strpos($name, '[]') === false){
  $name = $name.'[]';
}

if(!isset($multi_language)){
  $multi_language = false;
}
?>
@if($multi_language)
  @foreach(LanguageInstance::available(true) as $lang)
    <?php
    if(strpos($name, '[]') !== false){
      $name = str_replace('[]', '['.$lang['code'].'][]', $name);
    }
    else{
      $name = $name.'['.$lang['code'].']';
    }
    ?>
    <div class="input-language" data-lang="{{ $lang['code'] }}" style="{!! def_lang() == $lang['code'] ? '' : 'display:none;' !!}">
      <select {{ $type == 'select_multiple' ? 'multiple' : '' }} name="{!! $name !!}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!}>
        <option value=""></option>
        @foreach($data_source as $key => $vl)
        <option {{ $vl == $value ? 'selected' : null }} value="{{ $key }}">{{ $vl }}</option>
        @endforeach
      </select>
    </div>
  @endforeach
@else
  <select {{ $type == 'select_multiple' ? 'multiple' : '' }} name="{!! $name !!}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!}>
    <option value=""></option>
    @foreach($data_source as $key => $vl)
    <option {{ $vl == $value ? 'selected' : null }} value="{{ $key }}">{{ $vl }}</option>
    @endforeach
  </select>
@endif
