<?php
namespace Module\Navigation\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Navigation\Exceptions\NavigationException;

class NavigationInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('navigation');
	}

	public function generateStructure(){
		if($this->data){
			$lists = $this->data->lists;
			$max_level = $this->data->max_level;
			$out = [];
			if($lists->where('parent', null)->count() > 0){
				foreach($lists->where('parent', null)->sortBy('sort_no') as $row){
					$resp = $this->makeItemInstance($row, $max_level, 0);
					if($resp){
						$out[$row->title] = $resp;
					}
				}
			}
			return $out;
		}
		else{
			throw new NavigationException($this->getMessage('NO_DATA_DEFINED'));
		}
	}

	protected function makeItemInstance($row, $max_level=0, $current_level=0){
		$resp = null;
		if($current_level <= $max_level){
			$resp = [
				'id' => $row->id,
				'type' => $row->type,
				'url' => $row->url,
				'new_tab' => $row->new_tab,
			];

			if($row->children->count() > 0){
				$current_level++;
				foreach($row->children->sortBy('sort_no') as $child){
					$getresp = $this->makeItemInstance($child, $max_level, $current_level);
					if($getresp){
						$resp['submenu'][$child->title] = $getresp;
					}
				}
			}
		}

		return $resp;
	}

}