<?php
namespace Module\Page\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class Page extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
