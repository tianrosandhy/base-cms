<?php
namespace Module\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;

class PostComment extends Model
{
	use Resizeable;

    protected $fillable = [
    ];


    public function post(){
    	return $this->belongsTo('Module\Post\Models\Post');
    }

    public function parent(){
    	return $this->belongsTo('Module\Post\Models\PostComment', 'reply_to');
    }

    public function child(){
    	return $this->hasMany('Module\Post\Models\PostComment', 'reply_to');
    }

}
