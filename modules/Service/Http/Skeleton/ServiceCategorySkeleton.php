<?php
namespace Module\Service\Http\Skeleton;

use Core\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class ServiceCategorySkeleton extends DataTable
{
	public $route = 'service_category';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('service_category');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('title')
			->name('Title')
			->formColumn(12)
			->createValidation('required', true); //true = updatenya persis sama

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
			->name('Image')
			->formColumn(12)
			->inputType('image')
			->searchable(false)
			->orderable(false);

		$this->structure[] = DataStructure::switcher('show_on_homepage', 'Show on Homepage', 6);
		$this->structure[] = DataStructure::switcher('is_active', 'Is Active', 6);
		
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
			'excerpt' => $row->excerpt,
			'image' => $row->imageThumbnail('image', 'thumb', 50),
			'show_on_homepage' => has_access('admin.'.$this->route.'.switch') ? $this->switcher($row, 'show_on_homepage', 'admin.'.$this->route.'.switch') : ( $row->show_on_homepage ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Draft</span>'),
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
?>
