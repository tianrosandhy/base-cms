<?php
namespace Core\Main\Transformer;

use Core\Main\Http\Repository\CrudRepository;

class RoleStructure
{
	public 
		$structured_role,
		$role_list,
		$dropdown_list,
		$dropdown_risk_limit_list,
		$array_only;

	public function __construct($current_user=null){
		$this->structured_role = $this->structuredRole($current_user);
		$this->structured_role = $this->sortStructuredRole($this->structured_role);
		$this->role_list = [];
		$this->generateRoleList();
		$this->generateDropdownList();
		$this->generateArrayOnly();
	}

	public function sortStructuredRole($item=[]){
		if(isset($item['children'])){
			ksort($item['children']);
			foreach($item['children'] as $id => $row){
				$item['children'][$id] = $this->sortStructuredRole($row);
			}
		}
		return $item;
	}


	

	public function structuredRole($current_role=null){
		$base_role = (new CrudRepository('role'))->filter([
			['role_owner', '(null)']
		]);

		$out = [];
		foreach($base_role as $row){
			$out[$row->id] = $this->handleLoopStructure($row);
		}

		if(!$current_role->is_sa){
			//ambil hanya berdasarkan id yg ditemukan
			$temporary_out = [];
			foreach($out as $first){
				$value = $this->returnCurrentRoleOnly($first, $current_role->id);
				if($value){
					return $value;
				}
			}

		}
		else{
			//if as super admin, $out with index [current_super_admin] will be removed
			if(isset($out[$current_role->id])){
				unset($out[$current_role->id]);
			}

			return [
				'id' => $current_role->id,
				'label' => $current_role->name,
				'priviledge' => json_decode($current_role->priviledge, true),
				'role_owner' => null,
				'is_sa' => 1,
				'children' => $out
			];
		}

	}

	protected function returnCurrentRoleOnly($out, $stop_id){
		if($out['id'] == $stop_id){
			return $out;
		}
		if(isset($out['children'])){
			foreach($out['children'] as $child){
				$grabbed = $this->returnCurrentRoleOnly($child, $stop_id);
				if($grabbed){
					return $grabbed;
				}
			}
		}
	}

	protected function handleLoopStructure($row, $data=[]){
		$data = [
			'id' => $row->id,
			'label' => $row->name,
			'priviledge' => json_decode($row->priviledge_list, true),
			'role_owner' => $row->role_owner,
			'is_sa' => $row->is_sa,
		];
		if($row->children->count() > 0){
			foreach($row->children as $child){
				$data['children'][$child->id] = $this->handleLoopStructure($child, $data);
			}
		}
		return $data;
	}



	public function generateRoleList(){
		$this->extractStructureData($this->structured_role);
		return $this->role_list;
	}

	protected function extractStructureData($arr, $i=0, $upgrade_level=true){
		if(isset($arr['id']) && isset($arr['label'])){
			$this->role_list[] = [
				'id' => $arr['id'],
				'is_sa' => isset($arr['is_sa']) ? $arr['is_sa'] : 0,
				'label' => $arr['label'],
				'level' => $i,
				'role_owner' => isset($arr['role_owner']) ? $arr['role_owner'] : null,
			];
		}
		
		if($upgrade_level){
			$i++;
		}

		if(isset($arr['children'])){
			$n = 0;
			foreach($arr['children'] as $child){
				$n++;
				$up = true;
				$this->extractStructureData($child, $i, $up);
			}
		}
	}


	public function generateDropdownList(){
		$this->dropdown_list = [];
		foreach($this->role_list as $row){
			$this->dropdown_list[$row['id']] = str_repeat('*', $row['level']) . $row['label'];
		}
	}

	public function getLevel($role_id){
		$collect = collect($this->role_list);
		$get = $collect->where('id', $role_id);
		if($get->count() > 0){
			$grab = $get->first();
			return $grab['level'];
		}
	}

	public function generateArrayOnly(){
		$out = [];
		foreach($this->role_list as $list){
			$out[] = $list['id'];
		}
		$this->array_only = $out;
		return $out;
	}

}