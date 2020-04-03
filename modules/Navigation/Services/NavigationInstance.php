<?php
namespace Module\Navigation\Services;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Services\BaseInstance;
use Module\Navigation\Exceptions\NavigationException;

class NavigationInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('navigation');
	}

    public function generateDefaultNavigation(){
        $default = $this->getDefaultNavigation();
        if($default->lists->count() > 0){
            return ;
        }

        //generate list navigation logic
        $site_list = \Route::getRoutes();
        $used = [];
        foreach($site_list as $value){
        	$name = $value->getName();
        	if(strpos($name, 'front.') !== false){
        		$used[] = $value;
        	}
        }

		$final = [];
		foreach($used as $route){
			if(!in_array('GET', $route->methods())){
				continue;
			}
			if(strpos($route->uri(), '{') !== false && strpos($route->uri(), '}') !== false){
				if(strpos($route->uri(), '?}') === false){
					continue;
				}
			}
			$full_url = route($route->getName());
			$final[] = str_replace(url('/'), '', $full_url);
		} 

		if(!empty($final)){
			//set this final url as default site URL in navigation
			$i = 0;
			foreach($final as $url){
				$name = strlen($url) > 1 ? str_replace('/', ' ', $url) : 'Home';
				$name = str_replace('-', ' ', $name);
				$name = ucwords($name);

				model('navigation_item')->insert([
					'group_id' => $default->id,
					'title' => $name,
					'type' => 'site',
					'url' => strlen($url) == 0 ? '/' : str_replace('/', '', $url),
					'sort_no' => $i++,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				]);
			}
		}

    }

    public function getDefaultNavigation(){
        return app('navigation')->where('group_name', 'Default')->where('is_active', 1)->first();
    }


	public function name($group_name){
		$this->data = app('navigation')->where('group_name', $group_name)->first();
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