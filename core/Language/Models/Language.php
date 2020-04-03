<?php
namespace Core\Language\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;

class Language extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

}
