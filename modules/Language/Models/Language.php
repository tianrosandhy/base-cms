<?php
namespace Module\Language\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class Language extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
