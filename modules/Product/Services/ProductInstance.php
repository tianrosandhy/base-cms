<?php
namespace Module\Product\Services;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Services\BaseInstance;
use Module\Product\Exceptions\ProductException;

class ProductInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('product');
	}

}