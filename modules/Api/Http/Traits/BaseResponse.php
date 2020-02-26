<?php
namespace Module\Api\Http\Traits;

trait BaseResponse
{
  public 
    $type = 'success',
    $http_code = 200,
    $data = null,
    $message = null;
    

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

  public function getResponse($data=null){
    if($data){
      $this->data = $data;
    }

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