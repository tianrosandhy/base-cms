<?php
namespace Module\Page\Http\Skeleton;

use Core\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class PageSkeleton extends DataTable
{
	public $route = 'page';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('page');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('title')
			->name('Title')
			->formColumn(12)
			->createValidation('required', true); //true = updatenya persis sama

		$this->structure[] = DataStructure::slug('page_slug', 'Page Slug');

		$this->structure[] = DataStructure::field('description')
			->name('Description')
			->formColumn(12)
			->inputType('richtext');

		$this->structure[] = DataStructure::field('image')
			->name('Image')
			->formColumn(12)
			->inputType('image')
			->setImageDirPath(config('module-setting.page.upload_path'))
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
			'page_slug' => $row->slug(),
			'description' => descriptionMaker($row->description, 15),
			'image' => $row->imageThumbnail('image', 'thumb', 70),
			'is_active' => $this->switcher($row, 'is_active', 'admin.'.$this->route.'.switch'),
			'action' => self::detailButton($row) . self::editButton($row) . self::deleteButton($row)
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

	protected function detailButton($row){
		if(has_access('admin.'.$this->route.'.detail')){
			return $this->actionButton(
				'Detail', 
				url()->route('admin.'.$this->route.'.detail', ['id' => $row->id]), 
				[
					'class' => ['btn', 'btn-sm', 'btn-secondary detail-btn'],
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