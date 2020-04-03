<?php
namespace Core\Themes\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;
use Core\Main\Transformer\Translator;

class ThemesOptions extends Model
{
    use Resizeable;
    use Translator;
    
    protected $table = 'themes_option';

    protected $fillable = [
    ];

}
