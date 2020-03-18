<?php
namespace Module\Post\Transformer;

use Module\Api\Transformer\Reform;
use Module\Api\Transformer\ReformInterface;
use Module\Post\Models\Post;

class PostReform extends Reform implements ReformInterface
{
  public function model(){
    return (new Post);
  }

  public function batch(){

  }

  public function single(){

  }

}