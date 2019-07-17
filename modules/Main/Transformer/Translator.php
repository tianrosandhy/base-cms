<?php
namespace Module\Main\Transformer;

use Module\Main\Models\Translator as TModel;

//trait transformer for model that need translation in some fields.
trait Translator
{

	public function scopeGetTranslate($qry){
		$base = $qry->with('translate');
		return $base;
	}

	public function outputTranslate($field, $lang='', $strict=false){
		$langs = config('cms.lang');
		//kalau config multi language ga aktif, langsung kembalikan nilai asli
		if(!$langs['active']){
			return $this->{$field};
		}

		if(strlen($lang) == 0){
			$lang = current_lang();
		}

		$defaultLang = $langs['default'];
		$availableLang = array_diff($langs['available'], [$defaultLang]);
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
		return $this->hasMany('Module\Main\Models\Translator', 'id_field')
			->where('table', $this->getTable());
	}

}