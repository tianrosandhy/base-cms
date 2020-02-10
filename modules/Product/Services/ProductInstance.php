<?php
namespace Module\Product\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Product\Exceptions\ProductException;

class ProductInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('product');
	}

}