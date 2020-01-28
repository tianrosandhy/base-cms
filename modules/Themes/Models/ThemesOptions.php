<?php
namespace Module\Themes\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class ThemesOptions extends Model
{
    use Resizeable;
    
    protected $table = 'themes_option';

    protected $fillable = [
    ];

}
