<?php
namespace Module\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;

class PostToCategory extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
