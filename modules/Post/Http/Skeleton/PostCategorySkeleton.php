<?php
namespace Module\Post\Http\Skeleton;

use Module\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class PostCategorySkeleton extends DataTable
{
	public $route = 'post_category';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('post_category');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('name')
			->name('Category Name')
			->formColumn(12)
			->createValidation('required', true); //true = updatenya persis sama

		$this->structure[] = DataStructure::field('slug')
			->name('Slug')
			->formColumn(12)
			->inputType('slug')
			->slugTarget('name')
			->createValidation('required|unique:post_categories,slug,[id]', true);

		$this->structure[] = DataStructure::field('description')
			->name('Description')
			->formColumn(12)
			->inputType('richtext')
			->hideTable();

		$this->structure[] = DataStructure::field('image')
			->name('Featured Image')
			->formColumn(12)
			->inputType('image')
			->setImageDirPath(config('module-setting.post_category.upload_path'));

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
			'name' => $row->name,
			'slug' => $row->slug,
			'image' => $row->imageThumbnail('image', 'thumb', 75),
			'is_active' => $this->switcher($row, 'is_active', 'admin.'.$this->route.'.switch'),
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