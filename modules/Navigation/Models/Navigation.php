<?php
namespace Module\Navigation\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class Navigation extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
