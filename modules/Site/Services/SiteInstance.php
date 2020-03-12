<?php
namespace Module\Site\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Site\Exceptions\SiteException;

class SiteInstance
{
	public function __call($name, $arguments=[]){
		$instance_location = 'Module\\'.ucfirst($name).'\\Services\\' . ucfirst($name).'Instance';
		if(isset($arguments[0])){
			$instance_location = $arguments[0];
		}
		if(class_exists($instance_location)){
			return app($instance_location);
		}
		else{
			return new BlankInstance;
		}
	}	
}