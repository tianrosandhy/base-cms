<?php
namespace Module\Api\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Api\Http\Traits\BaseResponse;
use ApiInstance;
use Module\Api\Exceptions\ApiException;

class ApiBaseController extends Controller
{
  use BaseResponse;

  public $request;

  public function __construct(Request $request){
    $this->request = $request;
  }

  public function index(){
    $this->setMessage('Connection success');
    return $this->getResponse();
  }


  public function listObject($object){
    try{
      $data = ApiInstance::list($object, $this->request->all());
    }catch(ApiException $e){
      $this->setType('error', 404);
      $this->setMessage($e->getMessage());
      $data = null;
    }

    return $this->getResponse($data);
  }

  public function objectDetail($object, $id){
    try{
      $data = ApiInstance::single($object, $id, $this->request->all());
    }catch(ApiException $e){
      $this->setType('error', 404);
      $this->setMessage($e->getMessage());
      $data = null;
    }

    return $this->getResponse($data);
  }
}