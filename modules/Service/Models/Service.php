<?php
namespace Module\Service\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;

class Service extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

    public function category(){
    	return $this->belongsTo('Module\Service\Models\ServiceCategory', 'service_category_id');
    }

}
