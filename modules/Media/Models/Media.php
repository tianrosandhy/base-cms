<?php
namespace Module\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class Media extends Model
{
  use Resizeable;

  public $table = 'medias';
}
