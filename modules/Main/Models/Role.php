<?php
namespace Module\Main\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $fillable = [
    	'name',
        'role_owner',
        'is_sa',
    	'priviledge_list'
    ];

    public function owner(){
    	return $this->belongsTo('Module\Main\Models\Role', 'role_owner');
    }
    
    public function children(){
    	return $this->hasMany('Module\Main\Models\Role', 'role_owner');
    }
    

}
