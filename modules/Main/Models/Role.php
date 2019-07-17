<?php
namespace Module\Main\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $fillable = [
    	'name',
    	'priviledge_list'
    ];

}
