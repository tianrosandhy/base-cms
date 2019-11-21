<?php
namespace Module\Page\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Page\Exceptions\PageException;

class PageInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('page');
	}

}