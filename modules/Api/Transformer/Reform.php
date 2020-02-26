<?php
namespace Module\Api\Transformer;

use Module\Api\Exceptions\ApiException;

class Reform
{
  public 
    $model,
    $request;

  public function __construct($param=[]){
    $this->model = $this->model();
    $this->setRequest($param);
  }


  // overriden start from here

  public function filter(){
    $builder = $this->builderRequest($this->model());

    //return collection
    return $this->paginateResponse($builder);
  }

  public function batch($data){
    return $this->single($data);
  }

  public function single($data){
    return $data->toArray();
  }

  // overriden end here







  public function setRequest($param=[]){
    $this->request = $param;
  }

  public function getRequest($request_name){
    if(isset($this->request[$request_name])){
      return $this->request[$request_name];
    }
  }

  public function getSingle($id){
    $data = $this->model->find($id);
    if(empty($data)){
      throw new ApiException('Data not found');
    }

    return $data;
  }

  public function paginateResponse($builder){
    $per_page = 20; //default
    if(isset($this->request['per_page'])){
      if($this->request['per_page'] > 0 && $this->request['per_page'] < 100){
        $per_page = intval($this->request['per_page']);
      }
    }
    $page = 1;
    if(isset($this->request['page'])){
      $page = intval($this->request['page']);
    }
    return $builder->paginate($per_page, ['*'], 'page', $page);
  }
}