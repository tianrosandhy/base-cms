<?php
namespace Module\Api\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Api\Exceptions\ApiException;

class ApiInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('api');
	}


  public function list($object_name, $param=[]){
    $reform = $this->getReformObject($object_name, $param);
    $filtered = $reform->filter();
    $out = [];
    foreach($filtered as $row){
      $out[] = $reform->batch($row);
    }

    return [
      'per_page' => $filtered->perPage(),
      'total_data' => $filtered->total(),
      'current_data_count' => $filtered->count(),
      'page' => $filtered->currentPage(),
      'max_page' => ceil($filtered->total() / $filtered->perPage()),
      'has_more_page' => $filtered->hasMorePages(),
      'items' => $out
    ];
  }

  public function single($object_name, $id, $param=[]){
    $reform = $this->getReformObject($object_name, $param);

    //pastikan id object exists
    $data = $reform->getSingle($id);
    return $reform->single($data);
  }






  protected function getReformObject($object_name, $param=[]){
    $base_model = config('model.'.$object_name);
    if(empty($base_model)){
      throw new ApiException('Invalid object name');
    }

    if(method_exists(app($base_model), 'reform')){
      $object = app(app($base_model)->reform());
      return new $object($param);
    }
    else{
      //get by static target "Module\Name\Transformer\NameReform"
      $target_path = str_replace('Models', 'Transformer', $base_model);
      $target_path = $target_path.'Reform';
      if(class_exists($target_path)){
        $object = app($target_path);
        return new $object($param);
      }
    }

    throw new ApiException('Object reform class not found');
  }

}