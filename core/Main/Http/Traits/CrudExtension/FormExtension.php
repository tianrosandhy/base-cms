<?php
namespace Core\Main\Http\Traits\CrudExtension;

trait FormExtension
{
	public function additionalField($data=null){
		if($this->hint){
			if(view()->exists($this->hint.'::partials.crud.after-form')){
				return view($this->hint.'::partials.crud.after-form', compact('data'));
			}
		}		
		return '';
	}

	public function prependField($data=null){
		if($this->hint){
			if(view()->exists($this->hint.'::partials.crud.before-form')){
				return view($this->hint.'::partials.crud.before-form', compact('data'));
			}
		}		
		return '';
	}	
}