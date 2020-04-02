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
			$instance = model('navigation_item')->find($this->stored['id']);
			unset($this->stored['id']);
		}
		else{
			//insert
			$instance = model('navigation_item');
		}

		foreach($this->stored as $field => $value){
			$instance->{$field} = $value;
		}
		
		$instance->save();
		return $instance;
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
		if(strlen($this->post['url']) > 0 && $this->post['type'] == 'url'){
			$url = $this->post['url'];
		}

		if(isset($this->config['route_prefix']) && isset($this->post['slug']['site'])){
			$url = $this->post['slug']['site'];
		}
		return $url;
	}

	

	protected function handleSlug(){
		$slug_for_saved = isset($this->post['slug'][$this->post['type']]) ? $this->post['slug'][$this->post['type']] : null;
		if(is_array($this->post['title'])){
			$this->stored['title'] = get_lang($this->post['title']);
		}
		else{
			$this->stored['title'] = $this->post['title'];
		}
		if($slug_for_saved && isset($this->config['model_source'])){
			$instance = \SlugInstance::instance($slug_for_saved, [$this->config['model_source']]);
			if(isset($instance->{$this->config['source_label']})){
				$this->stored['slug'] = $slug_for_saved;
			}
			if(!$this->post['title']){
				$this->stored['title'] = $instance->{$this->config['source_label']};
			}
		}
		else{
			$this->stored['slug'] = null;
		}
	}

	protected function handleAdditionalFields(){
		$this->stored['group_id'] = $this->post['group_id'];
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
		$instance = model('navigation_item')->find($row['id']);
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