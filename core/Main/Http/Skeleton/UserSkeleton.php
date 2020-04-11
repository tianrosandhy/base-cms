<?php
namespace Core\Main\Http\Skeleton;

use Core\Main\DataTable\DataTable;
use DataStructure;
use DataSource;
use Core\Main\Transformer\RoleStructure;

class UserSkeleton extends DataTable
{

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('user');

		$this->structure[] = DataStructure::field('name')
			->name('Full Name')
			->formColumn(6)
			->createValidation('required', true); //true = updatenya persis sama
		
		$this->structure[] = DataStructure::field('email')
			->name('Email')
			->formColumn(6)
			->inputType('email')
			->createValidation('required|email|unique:users,email')
			->updateValidation('required|email|unique:users,email,[id]');
		
		$this->structure[] = DataStructure::field('password')
			->name('Password')
			->formColumn(6)
			->inputType('password')
			->createValidation('required|min:6')
			->hideTable();
			
		$this->structure[] = DataStructure::field('password_confirmation')
			->name('Password Repeat')
			->formColumn(6)
			->inputType('password')
			->hideTable();
			
		$this->structure[] = DataStructure::field('image')
			->searchable(false)
			->sortable(false)
			->name('Avatar')
			->inputType('image')
			->formColumn(12);

		$this->structure[] = DataStructure::field('role_id')
			->name('Priviledges')
			->formColumn(12)
			->inputType('select')
			->createValidation('required')
			->dataSource(
				DataSource::customHandler(function(){
					$current_role = request()->get('role');
					$structure = (new RoleStructure($current_role));
					if(request()->get('is_sa')){
						$out[$current_role->id] = $current_role->name;
						return $out + $structure->dropdown_list;
					}
					return $structure->dropdown_list;
				})
			);

		$this->structure[] = DataStructure::field('is_active')
			->name('User Status')
			->formColumn(12)
			->inputType('select')
			->dataSource([
				0 => 'Pending',
				1 => 'Active',
				9 => 'Blocked'
			]);
				
	}

	//manage custom filtering if required
	public function additionalSearchFilter($context){
		if(!request()->get('is_sa')){
			$structure = (new RoleStructure(request()->get('role')));
			$lists = array_merge([request()->get('role')->id], $structure->array_only);
			$context = $context->whereIn('role_id', $lists);
			//$grab = $this->grabColumn('name');
		}
		return $context;
	}

	
	//MANAGE OUTPUT DATATABLE FORMAT PER BARIS
	public function rowFormat($row, $as_excel=false){
		$is_sa = isset($row->roles->is_sa) ? $row->roles->is_sa : false;

		return [
			'name' => $row->name,
			'email' => $row->email,
			'image' => $row->imageThumbnail('image', 'thumb', 50),
			'role_id' => isset($row->roles->name) ? $row->roles->name : '<small style="color:#d00;"><em>no priviledge</em></small>',
			'is_active' => '<span class="badge '.($row->is_active == 0 ? 'badge-warning' : ($row->is_active == 1 ? 'badge-success' : 'badge-danger')).'">'.( $row->is_active == 0 ? 'Pending' : ($row->is_active == 1 ? 'Active' : 'Blocked') ).'</span>',
			'action' => self::editButton($row) . ($is_sa ? '' : self::deleteButton($row))
		];
	}

	protected function editButton($row){
		$editable = true;
		if(isset($row->roles->id) && isset(admin_guard()->user()->roles->id)){
			//akun SA ga boleh diedit oleh akun non SA
			if($row->roles->is_sa && !admin_guard()->user()->roles->is_sa){
				$editable = false;
			}
		}


		if(has_access('admin.user.update') && $editable){
			return $this->actionButton(
				'Edit', 
				url()->route('admin.user.edit', ['id' => $row->id]), 
				[
					'class' => ['btn', 'btn-sm', 'btn-info edit-btn'],
					'data-id' => $row->id
				]
			);
		}
	}

	protected function deleteButton($row){
		if(has_access('admin.user.delete')){
			return $this->actionButton(
				'Delete', 
				url()->route('admin.user.delete', ['id' => $row->id]), 
				[
					'class' => ['btn', 'btn-sm', 'btn-danger delete-button'],
					'data-id' => $row->id
				]
			);
		}
	}



}