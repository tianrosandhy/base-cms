<?php
namespace Core\Main\Models;

use Illuminate\Database\Eloquent\Model;

class Translator extends Model
{
    //
	protected $table = 'translator';

    protected $fillable = [
    	'table',
    	'field',
    	'id_field',
    	'lang',
    	'content'
    ];

}
