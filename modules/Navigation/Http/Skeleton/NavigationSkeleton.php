<?php
namespace Module\Navigation\Http\Skeleton;

use Module\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class NavigationSkeleton extends DataTable
{
	public $route = 'navigation';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('navigation');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('group_name')
			->name('Group Name')
			->formColumn(12)
			->createValidation('required', true); //true = updatenya persis sama

		$this->structure[] = DataStructure::field('description')
			->name('Description')
			->formColumn(12)
			->inputType('richtext');

		$this->structure[] = DataStructure::field('max_level')
			->name('Max Level')
			->formColumn(12)
			->inputType('number')
			->inputAttribute([
				'min' => 0,
				'max' => 2,
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
			'group_name' => $row->group_name,
			'description' => descriptionMaker($row->description),
			'max_level' => $row->max_level,
			'is_active' => $this->switcher($row, 'is_active', 'admin.'.$this->route.'.switch'),
			'action' => self::manageButton($row) . self::editButton($row) . self::deleteButton($row)
		];
	}

	protected function manageButton($row){
		if(has_access('admin.'.$this->route.'.manage')){
			return $this->actionButton(
				'Manage Menu', 
				url()->route('admin.'.$this->route.'.manage', ['id' => $row->id]), 
				[
					'class' => ['btn', 'btn-sm', 'btn-secondary manage-btn'],
					'data-id' => $row->id
				]
			);
		}
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