<?php
namespace Module\Banner\Transformer;

use Module\Api\Transformer\Reform;
use Module\Api\Transformer\ReformInterface;
use Module\Banner\Models\Banner;
use MediaInstance;

class BannerReform extends Reform implements ReformInterface
{
  public function model(){
    return (new Banner);
  }

  public function builderRequest($model){
    //builder request example
    if($this->getRequest('title')){
      $title = $this->getRequest('title');
      $search_title = str_replace(' ', '%', $title);
      $model = $model->where('title', 'like', '%'.$search_title.'%');
    }
    return $model;
  }

  public function single($data){
    $resp = $data->toArray();
    $resp['is_active'] = (bool)$resp['is_active'];
    $resp['image'] = $data->getThumbnailUrl('image');
    $resp['image_list'] = $data->listAllThumbnailUrl('image');
    return $resp;
  }

}