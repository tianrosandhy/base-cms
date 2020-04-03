<?php
namespace Core\Main\Transformer;

use Core\Main\Models\Translator as TModel;
use LanguageInstance;

//trait transformer for model that need translation in some fields.
trait Translator
{

	public function scopeGetTranslate($qry){
		$base = $qry->with('translate');
		return $base;
	}

	public function outputTranslate($field, $lang='', $strict=false){
		//kalau config multi language ga aktif, langsung kembalikan nilai asli
		if(!LanguageInstance::isActive()){
			return $this->{$field};
		}

		if(strlen($lang) == 0){
			$lang = current_lang();
		}

		$defaultLang = def_lang();

		if($lang == $defaultLang){
			return $this->{$field};
		}
		else{
			$getLang = $this->translate
				->where('lang', $lang)
				->where('field', $field)
				->first();

			if(!empty($getLang)){
				return $getLang->content;
			}
			else{
				if($strict){
					return null;
				}
				return $this->{$field};
			}
		}
	}


	public function translate(){
		return $this->hasMany('Core\Main\Models\Translator', 'id_field')
			->where('table', $this->getTable());
	}

}