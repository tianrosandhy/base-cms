<?php
namespace Module\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;

class Api extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
