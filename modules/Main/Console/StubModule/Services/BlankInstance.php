<?php
namespace Module\Blank\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;

class BlankInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('blank');
	}

}