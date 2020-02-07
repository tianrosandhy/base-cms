<?php
namespace Module\Banner\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Banner\Exceptions\BannerException;

class BannerInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('banner');
	}

}