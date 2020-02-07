<?php
namespace Module\Service\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class ServiceCategory extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

    public function services(){
    	return $this->hasMany('Module\Service\Models\Service', 'service_category_id');
    }

}
?>