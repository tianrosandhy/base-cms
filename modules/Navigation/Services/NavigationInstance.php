<?php
namespace Module\Navigation\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Navigation\Exceptions\NavigationException;

class NavigationInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('navigation');
	}

}