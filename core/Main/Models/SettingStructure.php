<?php
namespace Core\Main\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;

class SettingStructure extends Model
{
    use Resizeable;
    //
    protected $table = "settings_structure";
    protected $fillable = [
    	'param',
    	'name',
    	'description',
    	'default_value',
    	'options',
        'group',
    	'type',
    ];

}
