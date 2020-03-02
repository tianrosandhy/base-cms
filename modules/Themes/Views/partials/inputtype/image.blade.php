@if(config('cms.lang.active'))
  {!! MediaInstance::input('theme['.$name.'][]', (isset($value['en']) ? $value['en'] : (isset($param->default) ? $param->default : null))) !!}    
@else
{!! MediaInstance::input('theme['.$name.'][]', ($value ? $value : (isset($param->default) ? $param->default : null))) !!}
@endif