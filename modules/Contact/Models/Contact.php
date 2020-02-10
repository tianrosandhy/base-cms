<?php
namespace Module\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class Contact extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
