<?php
namespace Module\Navigation\Http\Processors;

use Illuminate\Http\Request;

class NavigationProcessor
{
	public 
		$request, 
		$post,
		$stored;

	public function __construct(Request $request){
		$this->request = $request;
		$this->post = $request->all();
		$this->generateTypeConfig();
	}

	protected function generateTypeConfig(){
		$typelist = config('module-setting.navigation.action_type');
		$used = 'no action';
		if(isset($this->post['type'])){
			if(isset($typelist[$this->post['type']])){
				$used = $this->post['type'];
			}
		}

		$this->config = $typelist[$used];
		$this->stored['type'] = $used;
	}


	public function save(){
		//will return handled exception if validation failed
		$this->validateRequest();

		$this->stored['url'] = $this->getUrlByType();
		$this->handleSlug();
		$this->handleAdditionalFields();
		if(isset($this->stored['id'])){
			//update
			$instance = app(config('model.navigation_item'))->find($this->stored['id']);
			unset($this->stored['id']);
		}
		else{
			//insert
			$instance = app(config('model.navigation_item'));
		}

		foreach($this->stored as $field => $value){
			$instance->{$field} = $value;
		}
		$instance->save();
	}

	protected function validateRequest(){
		$this->request->validate([
			'group_id' => 'required',
			'type' => 'required',
		], [
			'group_id.required' => 'Invalid request',
			'type.required' => 'Navigation type is required'
		]);
	}

	protected function getUrlByType(){
		$url = $this->config['url'];
		if(strlen($this->post['url']) > 0){
			$url = $this->post['url'];
		}
		return $url;
	}

	

	protected function handleSlug(){
		$slug_for_saved = isset($this->post['slug'][$this->post['type']]) ? $this->post['slug'][$this->post['type']] : null;
		$this->stored['title'] = $this->post['title'];
		if($slug_for_saved && isset($this->config['model_source'])){
			//slug harus divalidasi biar ga sembarangan diisi
			$grab = app(config('model.'.$this->config['model_source']));
			if(isset($this->config['source_is_active_field'])){
				$grab = $grab->where($this->config['source_is_active_field'], 1);
			}

			$grab = $grab->where($this->config['source_slug'], $slug_for_saved)->first();
			if(!empty($grab)){
				$this->stored['slug'] = $slug_for_saved;
				if(!$this->post['title']){
					$this->stored['title'] = $grab->{$this->config['source_label']};
				}
			}
		}
		else{
			$this->stored['slug'] = null;
		}
	}

	protected function handleAdditionalFields(){
		$this->stored['group_id'] = $this->post['group_id'];
		$this->stored['icon'] = $this->post['icon'];
		$this->stored['new_tab'] = $this->post['new_tab'];
    if(isset($this->post['parent'])){
        $this->stored['parent'] = $this->post['parent'];
    }

		if(isset($this->post['navigation_item_id'])){
			$this->stored['id'] = $this->post['navigation_item_id'];
		}
		else{
			//ini cuma berlaku saat insert aja
			$this->stored['sort_no'] = 999;
		}
	}





	public function reorderData($row, $iteration=1, $parent=null){
		$instance = app(config('model.navigation_item'))->find($row['id']);
		$instance->sort_no = $iteration;
		$instance->parent = $parent;
		$instance->save();

		if(isset($row['children'])){
			$iter = 1;
			foreach($row['children'] as $child){
				$this->reorderData($child, $iter++, $row['id']);
			}
		}
	}

}