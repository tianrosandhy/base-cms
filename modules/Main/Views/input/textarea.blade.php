<?php
$base_class = ['form_control'];
if(isset($attr['class'])){
  $class = $attr['class'];
}
if(isset($class)){
  $base_class = array_merge($base_class, $class);
}

if(!isset($attr['maxlength'])){
  $attr['maxlength'] = 255;
}

$cleaned_name = str_replace('[]', '', $name);
?>
@if(isset($multi_language))
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
      <textarea data-textarea name="{!! $name !!}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!}>{!! isset($value) ? $value : null !!}</textarea>
      <span class="feedback"></span>
    </div>
  @endforeach
@else
  <textarea data-textarea name="{!! $name !!}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!}>{!! isset($value) ? $value : null !!}</textarea>
  <span class="feedback"></span>
@endif
