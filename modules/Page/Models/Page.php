<?php
namespace Module\Page\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;
use Core\Main\Transformer\Translator;
use Core\Main\Transformer\Sluggable;

class Page extends Model
{
	use Resizeable;
  use Translator;
  use Sluggable;

  protected $fillable = [
  ];

}
