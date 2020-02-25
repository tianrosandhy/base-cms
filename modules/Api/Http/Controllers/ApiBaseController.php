<?php
namespace Module\Api\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
  public $request;

  public 
    $type = 'success',
    $http_code = 200,
    $data = null,
    $message = null;

  public function __construct(Request $request){
    $this->request = $request;
  }

  public function index(){
    $this->setMessage('Connection success');
    return $this->getResponse();
  }




  public function setType($type='success', $code=200){
    $this->type = $type;
    $this->setHttpCode($code);
  }

  public function setHttpCode($http_code=200){
    $this->http_code = $http_code;
  }

  public function setMessage($msg){
    $this->message = $msg;
  }

  public function setData($data){
    $this->data = $data;
  }

  public function getResponse(){
    return response()->json([
      'type' => $this->type,
      'alert' => [
        'code' => $this->http_code,
        'message' => $this->message,
      ],
      'data' => $this->data
    ], $this->http_code);
  }

}