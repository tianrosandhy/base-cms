<?php
namespace Module\Api\Http\Middleware;

use Closure;
use Module\Api\Models\Api;

class KeyCheck {

  public function handle($request, Closure $next)
  {
    $headers = $request->header();

    //basic header check
    if(!isset($headers['accept'][0])){
      return $this->errorResponse('Incomplete header requirements');
    }
    else{
      if(strtolower($headers['accept'][0]) <> 'application/json'){
        return $this->errorResponse('Incomplete header requirements');
      }
    }

    if(isset($headers['authorization']) || isset($headers['Authorization'])){
      $token = isset($headers['authorization']) ? $headers['authorization'][0] : (isset($headers['Authorization']) ? $headers['Authorization'][0] : '-');
      $hashtoken = explode(' ', $token);
      if(count($hashtoken) <> 2){
        return $this->errorResponse('Invalid authorization request');
      }
      if(strtolower($hashtoken[0]) <> 'basic'){
        return $this->errorResponse('Invalid authorization method. Basic required');
      }

      $real_token = $hashtoken[1];
      $hashed = base64_decode($real_token);
      $split = explode(':', $hashed);
      if(count($split) <> 2){
        return $this->errorResponse('Not authorized');
      }

      //cek ke database
      $check = Api::where('public', $split[0])->where('secret', $split[1])->first();
      if(empty($check)){
        return $this->errorResponse('Not authorized');
      }
      if(!$check->is_active){
        return $this->errorResponse('Not authorized');
      }

      //jika request ok, catet token holder
      $request_id = date('YmdHis') . '-' . uniqid();
      $request->attributes->add([
        'token' => $real_token,
        'request_id' => $request_id
      ]);

    }
    else{
      return $this->errorResponse('Restricted request. You need to define the authorization token');
    }

    return $next($request); 
  }


  public function errorResponse($msg, $code=403){
    return response()->json([
      'type' => 'error',
      'alert' => [
        'code' => $code,
        'message' => $msg
      ],
      'data' => (object)[]
    ], $code);
  }
}