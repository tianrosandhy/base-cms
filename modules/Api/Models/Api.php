<?php
namespace Module\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class Api extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
