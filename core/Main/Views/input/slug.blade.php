<?php
$base_class = ['form-control'];
if(isset($attr['class'])){
  $class = $attr['class'];
}
if(isset($class)){
  $base_class = array_merge($base_class, $class);
}

$cleaned_name = str_replace('[]', '', $name);

if(!isset($multi_language)){
  $multi_language = false;
}
?>
@if($multi_language)
  @foreach(LanguageInstance::available(true) as $lang)
    <?php
    if(strpos($name, '[]') !== false){
      $input_name = str_replace('[]', '['.$lang['code'].'][]', $name);
    }
    else{
      $input_name = $name.'['.$lang['code'].']';
    }
    ?>
    <div class="input-language" data-lang="{{ $lang['code'] }}" style="{!! def_lang() == $lang['code'] ? '' : 'display:none;' !!}">
      <input type="text" data-slug="{{ $slug_target.'-'.$lang['code'] }}" name="{!! $input_name !!}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!} value="{{ old($cleaned_name.'.'.$lang['code'], (isset($value[$lang['code']]) ? $value[$lang['code']] : null)) }}" id="input-{{ $cleaned_name }}-{{ $lang['code'] }}">
    </div>
  @endforeach
@else
  <input type="text" data-slug="{{ $slug_target }}" name="{{ $name }}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!} value="{{ old($cleaned_name, (isset($value) ? $value : null)) }}" id="input-{{ $cleaned_name }}">
@endif
