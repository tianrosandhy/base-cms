<?php
namespace Module\Main\Http\Skeleton;

use Module\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

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
			->createValidation('required', true)
			->dataSource(
				DataSource::model('role')->options('name', [
					['id', '>', 0]
				])
			);
	}



	
	//MANAGE OUTPUT DATATABLE FORMAT PER BARIS
	public function rowFormat($row, $as_excel=false){
		return [
			'name' => $row->name,
			'email' => $row->email,
			'image' => '<img src="'.$row->getThumbnailUrl('image', 'thumb').'" style="height:50px">',
			'role_id' => isset($row->roles->name) ? $row->roles->name : '<small style="color:#d00;"><em>no priviledge</em></small>',
			'action' => self::editButton($row) . self::deleteButton($row)
		];
	}

	protected function editButton($row){
		if(has_access('admin.user.update')){
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