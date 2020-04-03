<?php
namespace Module\Page\Services;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Services\BaseInstance;
use Module\Page\Exceptions\PageException;

class PageInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('page');
	}

}