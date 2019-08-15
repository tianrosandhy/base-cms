<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Module\Main\Http\Repository\CrudRepository;

class PermissionController extends AdminBaseController
{
	public $repo;

	public function __construct(Request $req){
		parent::__construct($req);
		$this->repo = new CrudRepository('role');
	}

	public function index(){
		$lists = $this->repo->all();
		return view('main::module.permission', compact(
			'lists'
		));
	}



	public function store(){
		$validate = self::validateRole();
		if($validate){
			return back()->withErrors(['name' => $validate]);
		}

		//simpen
		$this->repo->insert([
			'name' => $this->request->name,
			'priviledge_list' => ''
		]);

		return back()->with('success', 'Role has been added');
	}

	public function update($id=0){
		$validate = self::validateRole($id);
		if($validate){
			return back()->withErrors(['name' => $validate]);
		}

		$this->repo->update($id, [
			'name' => $this->request->name
		]);

		return back()->with('success', 'Role has been updated');
	}

	protected function validateRole($id=0){
		if(strlen($this->request->name) == 0){
			return 'Please fill the role name';
		}

		if($id == 0){
			$cek = $this->repo->filter([
				['name', '=', $this->request->name]
			])->count();
		}
		else{
			$cek = $this->repo->filter([
				['name', '=', $this->request->name],
				['id', '<>', $id]
			])->count();
		}

		if($cek > 0){
			return 'Role name already exists';
		}

		return false;
	}


	public function delete($id=0){
		if(!$this->repo->exists($id)){
			abort(404);
		}

		$data = $this->repo->show($id);
		if($data->is_sa){
			//admin pertama ga bole dihapus
			return [
				'type' => 'error',
				'message' => 'You cannot delete the main administrator role'
			];
		}
		$this->repo->delete($id);

		return [
			'type' => 'success',
			'message' => 'User priviledge data has been deleted'
		];
	}





	public function showPermission($id){
		$role = $this->repo->show($id);
		$all = config('permission');
		$checked = json_decode($role->priviledge_list);
		$checked = !$checked ? [] : $checked;

		return view('main::module.show-priviledge', compact(
			'role',
			'all',
			'checked',
			'id'
		))->render();
	}

	public function savePermission($id){
		$val = Validator::make($this->request->all(), [
			'check' => 'array'
		])->validate();

		$check = empty($this->request->check) ? '' : json_encode($this->request->check);

		$role = $this->repo->show($id);
		$this->repo->update($id, [
			'priviledge_list' => $check
		]);

		return back()->with('success', 'Priviledge data has been saved');
	}

}