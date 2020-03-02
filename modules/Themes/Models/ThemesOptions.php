<?php
namespace Module\Themes\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;
use Module\Main\Transformer\Translator;

class ThemesOptions extends Model
{
    use Resizeable;
    use Translator;
    
    protected $table = 'themes_option';

    protected $fillable = [
    ];

}
