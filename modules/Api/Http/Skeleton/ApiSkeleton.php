<?php
namespace Module\Api\Http\Skeleton;

use Module\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class ApiSkeleton extends DataTable
{
	public $route = 'api';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('api');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('api_name')
			->name('API Name')
			->formColumn(12)
			->createValidation('required|unique:apis,api_name,[id]', true);

		$this->structure[] = DataStructure::field('public')
			->name('Public Key')
			->formColumn(12)
			->inputType('text')
			->inputAttribute([
				'placeholder' => 'If keep blank, public key will be automatically generated'
			]);

		$this->structure[] = DataStructure::field('secret')
			->name('Secret Key')
			->formColumn(12)
			->inputType('text')
			->inputAttribute([
				'placeholder' => 'If keep blank, secret key will be automatically generated'
			]);

		$this->structure[] = DataStructure::switcher('is_active', 'Is Active', 12);
		
	}

	//manage custom filtering if required
	public function additionalSearchFilter($context){
		//$grab = $this->grabColumn('name');
		return $context;
	}

	
	//MANAGE OUTPUT DATATABLE FORMAT PER BARIS
	public function rowFormat($row, $as_excel=false){
		return [
			'id' => $this->checkerFormat($row),
			'api_name' => $row->api_name,
			'public' => '<input type="text" onclick="this.setSelectionRange(0, this.value.length)" class="form-control" value="'.$row->public.'">',
			'secret' => '<input type="text" onclick="this.setSelectionRange(0, this.value.length)" class="form-control" value="'.$row->secret.'">',
			'is_active' => has_access('admin.'.$this->route.'.switch') ? $this->switcher($row, 'is_active', 'admin.'.$this->route.'.switch') : ( $row->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Draft</span>'),
			'action' => self::editButton($row) . self::deleteButton($row)
		];
	}

	protected function editButton($row){
		if(has_access('admin.'.$this->route.'.update')){
			return $this->actionButton(
				'Edit', 
				url()->route('admin.'.$this->route.'.edit', ['id' => $row->id]), 
				[
					'class' => ['btn', 'btn-sm', 'btn-info edit-btn'],
					'data-id' => $row->id
				]
			);
		}
	}

	protected function deleteButton($row){
		if(has_access('admin.'.$this->route.'.delete')){
			return $this->actionButton(
				'Delete', 
				url()->route('admin.'.$this->route.'.delete', ['id' => $row->id]), 
				[
					'class' => ['btn', 'btn-sm', 'btn-danger delete-button'],
					'data-id' => $row->id
				]
			);
		}
	}



}