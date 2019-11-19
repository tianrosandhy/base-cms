<?php
namespace Module\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class PostCategory extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
