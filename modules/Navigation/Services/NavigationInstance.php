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

	public function name($group_name){
		$this->data = $this->model->where('group_name', $group_name)->first();
		return $this;
	}

	public function structure($group_name){
		$this->name($group_name);
		if($this->data){
			return $this->generateStructure();
		}
		else{
			return [];
		}
	}

	public function generateStructure($max_level=null){
		if($this->data){
			$lists = $this->data->lists;
			$max_level = strlen($max_level) > 0 ? $max_level : $this->data->max_level;
			$out = [];
			if($lists->where('parent', null)->count() > 0){
				foreach($lists->where('parent', null)->sortBy('sort_no') as $row){
					$resp = $this->makeItemInstance($row, $max_level, 0);
					if($resp){
						$out[$row->outputTranslate('title')] = $resp;
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
			$url = $row->url;
			if($row->type <> 'url' && !empty($row->slug)){
				$url .= $row->slug;
			}
			$resp = [
				'id' => $row->id,
				'type' => $row->type,
				'url' => url($url),
				'new_tab' => $row->new_tab,
				'icon' => $row->icon,
			];

			if($row->children->count() > 0){
				$current_level++;
				foreach($row->children->sortBy('sort_no') as $child){
					$getresp = $this->makeItemInstance($child, $max_level, $current_level);
					if($getresp){
						$resp['submenu'][$child->outputTranslate('title')] = $getresp;
					}
				}
			}
		}

		return $resp;
	}

}