<?php

namespace Core\Main\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Core\Main\Transformer\Resizeable;

class User extends Authenticatable
{
    use Notifiable;
    use Resizeable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'image', 'remember_token', 'activation_key', 'is_active', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles(){
        return $this->belongsTo('Core\Main\Models\Role', 'role_id');
    }
}
