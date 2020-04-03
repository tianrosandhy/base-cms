<?php
namespace Core\Main\Http\Traits\CrudExtension;

trait IndexExtension
{
	//index extension
	public function prependIndex(){
		if($this->hint){
			if(view()->exists($this->hint.'::partials.index.before-table')){
				return view($this->hint.'::partials.index.before-table');
			}
		}
		//fallback : blank
		return '';
	}

	public function appendIndex(){
		if($this->hint){
			if(view()->exists($this->hint.'::partials.index.after-table')){
				return view($this->hint.'::partials.index.after-table');
			}
		}
		//fallback : blank
		return '';
	}

	public function appendIndexControlButton(){
		if($this->hint){
			if(view()->exists($this->hint.'::partials.index.control-button')){
				return view($this->hint.'::partials.index.control-button');
			}
		}
		//fallback : blank
		return '';
	}
	
}