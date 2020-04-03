<?php
namespace Core\Main\Transformer;

use SlugInstance;

//for model that has slug master relation
trait Sluggable
{

	public function slug($lang=null){
		return SlugInstance::get($this, $this->id, $lang);
	}

}