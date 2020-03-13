<?php
namespace Module\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;
use Module\Main\Transformer\Translator;
use Module\Main\Transformer\Sluggable;

class PostCategory extends Model
{
	use Resizeable;
  use Translator;
  use Sluggable;

  protected $fillable = [
  ];

}
