<?php
namespace Module\Page\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;
use Module\Main\Transformer\Translator;

class Page extends Model
{
	use Resizeable;
  use Translator;

  protected $fillable = [
  ];

}
