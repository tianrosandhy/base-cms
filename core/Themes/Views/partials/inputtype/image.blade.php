@if(LanguageInstance::isActive())
  {!! MediaInstance::input('theme['.$name.'][]', (isset($value[def_lang()]) ? $value[def_lang()] : (isset($param->default) ? $param->default : null))) !!}    
@else
{!! MediaInstance::input('theme['.$name.'][]', ($value ? $value : (isset($param->default) ? $param->default : null))) !!}
@endif