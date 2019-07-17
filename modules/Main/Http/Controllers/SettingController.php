<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Module\Base\Models\SettingStructure;
use ImageService;
use Module\Main\Http\Repository\CrudRepository;
use Storage;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class SettingController extends AdminBaseController
{
	public $repo;

	public function __construct(Request $req){
		parent::__construct($req);
		$this->repo = new CrudRepository('setting_structure');
	}

	public function index(){
		$settings = SettingStructure::get()->groupBy('group');
		return view('main::setting', compact(
			'settings'
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
		foreach($instance as $row){
			if(array_key_exists($row->id, $this->request->value)){
				$value = $this->request->value[$row->id];
				$this->repo->update($row->id, [
					'default_value' => $value
				]);
			}
		}

		return back()->with('success', 'Setting data has been updated');
	}




	public function delete($id=0){
		$cek = SettingStructure::findOrFail($id);

		\CMS::log($cek, 'ADMIN DELETE SETTING');

		//if type == image, hapus di storage juga
		if($cek->type == 'image'){
			ImageService::removeImage($cek->default_value);
		}
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