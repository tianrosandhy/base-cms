<?php
namespace Module\Banner\Transformer;

use Module\Api\Transformer\Reform;
use Module\Api\Transformer\ReformInterface;
use Module\Post\Models\Post;

class BannerReform extends Reform implements ReformInterface
{
  public function model(){
    return (new Post);
  }

  public function batch(){

  }

  public function single(){

  }

}