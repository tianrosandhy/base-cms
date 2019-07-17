<?php
namespace Module\Main\Models;

use Illuminate\Database\Eloquent\Model;

class SettingStructure extends Model
{
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
