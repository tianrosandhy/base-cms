<?php
namespace Core\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;

class Media extends Model
{
  use Resizeable;

  public $table = 'medias';
}
