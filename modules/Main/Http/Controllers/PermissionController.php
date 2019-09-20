<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Transformer\RoleStructure;

class PermissionController extends AdminBaseController
{
	public $repo;

	public function __construct(Request $req){
		parent::__construct($req);
		$this->repo = new CrudRepository('role');
	}

	public function index(){
		$structure = (new RoleStructure(request()->get('role')));
		return view('main::module.permission', compact(
			'user_info',
			'structure'
		));
	}



	public function store(){
		$validate = self::validateRole();
		if($validate){
			return back()->withErrors(['name' => $validate]);
		}

		//simpen
		$param = [
			'name' => $this->request->name,
			'priviledge_list' => '',
			'risk_limit' => $this->request->risk_limit,
			'role_owner' => $this->request->role_owner ? $this->request->role_owner : null
		];

		if(!$this->request->get('is_sa')){
			$param['role_owner'] = $this->request->get('role')->id;
		}

		$this->repo->insert($param);

		return back()->with('success', 'Role has been added');
	}

	public function update($id=0){
		$validate = self::validateRole($id);
		if($validate){
			return back()->withErrors(['name' => $validate]);
		}

		$saveparam = [
			'name' => $this->request->name,
			'risk_limit' => $this->request->risk_limit,
			'role_owner' => ($this->request->role_owner ? $this->request->role_owner : null)
		];
		//gaboleh nyimpen role diri sendiri supaya ga recursive
		if($id == $this->request->role_owner){
			unset($saveparam['role_owner']);
		}

		if(empty($saveparam['role_owner'])){
			unset($saveparam['role_owner']);
		}
		$this->repo->update($id, $saveparam);

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

		//$all variable need to be rechecked
		if(!$this->request->get('is_sa')){
			$available = $this->request->get('base_permission');
			$temp = [];
			//loop 3 level
			foreach($all as $label => $first){
				if(!is_array($first)){
					if(in_array($first, $available)){
						$temp[$label] = $first;
					}
				}
				else{
					foreach($first as $sublabel => $second){
						if(!is_array($second)){
							if(in_array($second, $available)){
								$temp[$label][$sublabel] = $second;
							}
						}
						else{
							foreach($second as $lastlabel => $third){
								if(!is_array($third)){
									if(in_array($third, $available)){
										$temp[$label][$sublabel][$lastlabel] = $third;
									}
								}
							}
						}
					}
				}
			}
			$all = $temp;
		}


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