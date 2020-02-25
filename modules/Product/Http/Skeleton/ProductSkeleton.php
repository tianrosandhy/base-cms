<?php
namespace Module\Product\Http\Skeleton;

use Module\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class ProductSkeleton extends DataTable
{
	public $route = 'product';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('product');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('title')
			->name('Title')
			->formColumn(12)
			->createValidation('required', true); //true = updatenya persis sama

		$this->structure[] = DataStructure::field('category[]')
			->name('Category')
			->formColumn(12)
			->inputType('select_multiple')
			->dataSource(DataSource::model('service_category')->options('title', [
				['is_active', '=', 1]
			]))
			->arraySource(function($data){
				if($data->categories){
					return $data->categories->pluck('id')->toArray();
				}
				return [];
			});
				

		$this->structure[] = DataStructure::field('description')
			->name('Description')
			->formColumn(12)
			->inputType('textarea');

		$this->structure[] = DataStructure::field('image')
			->name('Product Image')
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
		$category = '';
		foreach($row->categories as $cat){
			$category .= '<span class="badge badge-primary mb-1">'.$cat->title.'</span>';
		}

		return [
            'id' => $this->checkerFormat($row),
			'title' => $row->title,
			'category' => $category,
			'description' => descriptionMaker($row->description, 15),
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