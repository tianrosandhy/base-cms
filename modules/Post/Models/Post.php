<?php
namespace Module\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;
use Module\Main\Transformer\Translator;

class Post extends Model
{
	use Resizeable;
    use Translator;

    protected $fillable = [
    ];

    public function category(){
    	return $this->belongsToMany('Module\Post\Models\PostCategory', 'post_to_categories', 'post_id', 'post_category_id');
    }

    public function related(){
    	return $this->belongsToMany('Module\Post\Models\Post', 'post_relateds', 'post_id', 'post_related_id');
    }

    public function likes(){
    	return $this->hasMany('Module\Post\Models\PostLike');
    }

    public function comment(){
        return $this->hasMany('Module\Post\Models\PostComment');
    }

}
