<?php
namespace Module\Main\Transformer;

use Module\Main\Http\Repository\CrudRepository;

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
		$this->sortStructuredRole();
		$this->role_list = [];
		$this->generateRoleList();
		$this->generateDropdownList();
		$this->generateArrayOnly();
	}

	public function sortStructuredRole(){
		if(isset($this->structured_role['children'])){
			ksort($this->structured_role['children']);
			foreach($this->structured_role['children'] as $id => $row){
				if(isset($this->structured_role['children'][$id]['children'])){
					ksort($this->structured_role['children'][$id]['children']);
					foreach($this->structured_role['children'][$id]['children'] as $subid => $subrow){
						if(isset($this->structured_role['children'][$id]['children'][$subid]['children'])){
							ksort($this->structured_role['children'][$id]['children'][$subid]['children']);
						}
					}
				}
			}
		}
	}

	public function structuredRole($current_role=null){
		$base_role = (new CrudRepository('role'))->filter([
			['role_owner', '(null)']
		]);

		$out = [];
		foreach($base_role as $row){
			$out[$row->id] = [
				'id' => $row->id,
				'label' => $row->name,
				'priviledge' => json_decode($row->priviledge_list, true),
				'risk_limit' => $row->risk_limit,
				'role_owner' => null,
				'is_sa' => $row->is_sa,
			];
			if($row->children->count() > 0){
				foreach($row->children as $child){
					$out[$row->id]['children'][$child->id] = [
						'id' => $child->id,
						'label' => $child->name,
						'priviledge' => json_decode($child->priviledge_list, true),
						'risk_limit' => $child->risk_limit,
						'role_owner' => $child->role_owner,
					];
					if($child->children->count() > 0){
						foreach($child->children as $subchild){
							$out[$row->id]['children'][$child->id]['children'][$subchild->id] = [
								'id' => $subchild->id,
								'label' => $subchild->name,
								'priviledge' => json_decode($subchild->priviledge_list, true),
								'risk_limit' => $subchild->risk_limit,
								'role_owner' => $subchild->role_owner,
							];
							if($subchild->children->count() > 0){
								foreach($subchild->children as $lastchild){
									$out[$row->id]['children'][$child->id]['children'][$subchild->id]['children'][$lastchild->id] = [
										'id' => $lastchild->id,
										'label' => $lastchild->name,
										'priviledge' => json_decode($lastchild->priviledge_list, true),
										'risk_limit' => $lastchild->risk_limit,
										'role_owner' => $lastchild->role_owner,
									];
								}

								if(isset($out[$row->id]['children'][$child->id]['children'][$subchild->id]['children'])){
									$out[$row->id]['children'][$child->id]['children'][$subchild->id]['children'] = \Arr::sort($out[$row->id]['children'][$child->id]['children'][$subchild->id]['children'], function($value){
										return $value['label'];
									});
								}

							}

							if(isset($out[$row->id]['children'][$child->id]['children'])){
								$out[$row->id]['children'][$child->id]['children'] = \Arr::sort($out[$row->id]['children'][$child->id]['children'], function($value){
									return $value['label'];
								});
							}
						}
					}

					if(isset($out[$row->id]['children'])){
						$out[$row->id]['children'] = \Arr::sort($out[$row->id]['children'], function($value){
							return $value['label'];
						});
					}
				}
			}
		}

		if(!isset($current_role->is_sa)){
			return [
				'label' => 'All Priviledges',
				'children' => $out
			];
		}
		if(!$current_role->is_sa){
			//ambil hanya berdasarkan id yg ditemukan
			$temporary_out = [];
			foreach($out as $first){
				if($first['id'] == $current_role->id){
					return $out[$current_role->id];
				}
				if(isset($first['children'])){
					foreach($first['children'] as $second){
						if($second['id'] == $current_role->id){
							return $out[$first['id']]['children'][$current_role->id];
						}
						if(isset($second['children'])){
							foreach($second['children'] as $third){
								if($third['id'] == $current_role->id){
									return $out[$first['id']]['children'][$second['id']]['children'][$current_role->id];
								}
								if(isset($third['children'])){
									foreach($third['children'] as $fourth){
										if($fourth['id'] == $current_role->id){
											return $out[$first['id']]['children'][$second['id']]['children'][$third['id']]['children'][$current_role->id];
										}
									}
								}
							}
						}
					}
				}
			}
		}
		else{
			return [
				'id' => $current_role->id,
				'label' => $current_role->name,
				'priviledge' => json_decode($current_role->priviledge, true),
				'children' => $out
			];
		}

	}


	public function generateRoleList(){
		if(isset($this->structured_role['children'])){
			foreach($this->structured_role['children'] as $child){
				$this->extractStructureData($child);
			}
		}
		return $this->role_list;
	}

	protected function extractStructureData($arr, $i=0, $upgrade_level=true){
		if(isset($arr['id']) && isset($arr['label'])){
			$this->role_list[] = [
				'id' => $arr['id'],
				'is_sa' => isset($arr['is_sa']) ? $arr['is_sa'] : 0,
				'label' => $arr['label'],
				'risk_limit' => $arr['risk_limit'],
				'level' => $i,
				'role_owner' => $arr['role_owner'],
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
			$this->dropdown_risk_limit_list[$row['id']] = str_repeat('*', $row['level']) . $row['label'] .' - '.number_format($row['risk_limit'], 0, '.', ',');
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