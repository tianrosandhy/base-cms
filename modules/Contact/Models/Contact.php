<?php
namespace Module\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;

class Contact extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
