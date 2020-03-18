<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Module\Main\Models\SettingStructure;
use Module\Main\Http\Repository\CrudRepository;
use Storage;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Setting;

class SettingController extends AdminBaseController
{
	public $repo;

	public function __construct(Request $req){
		parent::__construct($req);
		$this->repo = new CrudRepository('setting_structure');
	}

	public function index(){
		$settings = Setting::all();
		$title = "Setting";
		return view('main::module.setting', compact(
			'settings',
			'title'
		));
	}

	public function store(){
		$validate = self::validateInput();
		if($validate){
			return $validate;
		}

		//cek value
		$value = '';
		if(isset($this->request->value[$this->request->type])){
			$value = $this->request->value[$this->request->type];
		}

		$instance = $this->repo->insert([
			'param' => $this->request->param,
			'name' => strtolower($this->request->name),
			'description' => $this->request->description,
			'default_value' => $value,
			'type' => $this->request->type,
			'group' => strtolower((strlen($this->request->group) == 0 ? 'site' : $this->request->group)),
		]);

		\CMS::log($instance, 'ADMIN STORE SETTING');

		return back()->with('success', 'New setting data has been saved');
	}

	protected function validateInput($id=null){
		//uniq param n group
		$arg = [
			'param' => $this->request->param,
			'group' => $this->request->group
		];
		if($id > 0){
			$arg['id'] = $id;
		}

		$cek = $this->repo->filter($arg);
		if($cek->count() > 0){
			return back()->withErrors(['param' => 'Parameter name in that group already exists'])->withInput();
		}

		$validate = Validator::make($this->request->all(), [
			'param' => 'required',
			'name' => 'required',
			'type' => 'required',
		]);

		if($validate->fails()){
			return $validate->validate();
		}
		return false;
	}



	public function update($id=0){
		$instance = $this->repo->all();
		foreach($this->request->value as $keys => $value){
			$pch = explode('.', $keys);
			if(count($pch) <> 2){
				continue; //invalid request
			}
			$grab = $instance->where('group', $pch[0])->where('param', $pch[1])->first();
			if(!empty($grab)){
				//update value
				$grab->default_value = $value;
				$grab->save();
			}
			else{
				//create new setting instance
				$name = ucwords(implode(' ', explode('_', $pch[1])));

				$this->repo->insert([
					'group' => $pch[0],
					'param' => $pch[1],
					'default_value' => $value,
					'name' => $name,
					'type' => 'custom'
				]);
			}
		}

		return back()->with('success', 'Setting data has been updated');
	}




	public function delete($id=0){
		$cek = SettingStructure::findOrFail($id);

		\CMS::log($cek, 'ADMIN DELETE SETTING');

		$cek->delete();

		return [
			'type' => 'success',
			'message' => 'Setting data has been deleted'
		];
	}




	public function postArtisan(){
		//simple artisan command
		$key = $this->request->key;
		try{
			\Artisan::call($this->request->key);
			$output = \Artisan::output();
		}catch(Exception $e){
			return back()->with('artisan', 'Command not available');			
		}catch(CommandNotFoundException $e){
			return back()->with('artisan', 'Command not found');
		}

		\CMS::log($this->request->all(), 'ADMIN RUN ARTISAN');
		return back()->with('artisan', $output);
	}


}