<?php
namespace Module\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class PostRelated extends Model
{
	use Resizeable;

	public $table = 'post_relateds';

    protected $fillable = [
    ];

}
