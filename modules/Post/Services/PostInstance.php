<?php
namespace Module\Post\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;

class PostInstance extends BaseInstance
{
	public $data;

	public function __construct(){
		parent::__construct('post');
	}


}