<?php
namespace Module\Page\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;
use Module\Main\Transformer\Translator;
use Module\Main\Transformer\Sluggable;

class Page extends Model
{
	use Resizeable;
  use Translator;
  use Sluggable;

  protected $fillable = [
  ];

}
