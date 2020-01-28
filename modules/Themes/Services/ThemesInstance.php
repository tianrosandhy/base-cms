<?php
namespace Module\Themes\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Themes\Exceptions\ThemesException;

class ThemesInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('themes');
	}

}