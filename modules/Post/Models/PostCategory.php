<?php
namespace Module\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;
use Module\Main\Transformer\Translator;

class PostCategory extends Model
{
	use Resizeable;
  use Translator;

  protected $fillable = [
  ];

}
