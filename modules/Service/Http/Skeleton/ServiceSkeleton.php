<?php
namespace Module\Service\Http\Skeleton;

use Core\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class ServiceSkeleton extends DataTable
{
	public $route = 'service';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('service');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('title')
			->name('Title')
			->formColumn(12)
			->createValidation('required', true); //true = updatenya persis sama

		$this->structure[] = DataStructure::field('service_category_id')
			->name('Category')
			->formColumn(12)
			->inputType('select')
			->dataSource(DataSource::model('service_category')->options('title', [
				['is_active', '=', 1]
			]));

		$this->structure[] = DataStructure::field('excerpt')
			->name('Excerpt')
			->formColumn(12)
			->inputType('textarea');

		$this->structure[] = DataStructure::field('description')
			->name('Description')
			->formColumn(12)
			->hideTable()
			->inputType('richtext');

		$this->structure[] = DataStructure::field('image')
			->name('Featured Image')
			->formColumn(12)
			->inputType('image')
			->searchable(false)
			->orderable(false);

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
			'title' => $row->title,
			'service_category_id' => isset($row->category->title) ? $row->category->title : '-',
			'excerpt' => $row->excerpt ? $row->excerpt : descriptionMaker($row->description, 15),
			'image' => $row->imageThumbnail('image', 'thumb', 50),
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