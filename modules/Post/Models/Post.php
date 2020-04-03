<?php
namespace Module\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;
use Core\Main\Transformer\Translator;
use Core\Main\Transformer\Sluggable;
use Core\Main\Transformer\Seoable;

class Post extends Model
{
	use Resizeable;
    use Translator;
    use Sluggable;
    use Seoable;

    protected $fillable = [
    ];

    //override description SEO source
    public function seoDescription(){
        return ($this->excerpt ? descriptionMaker($this->excerpt) : descriptionMaker($this->description));
    }


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
