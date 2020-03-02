<?php
namespace Module\Language\Http\Skeleton;

use Module\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class LanguageSkeleton extends DataTable
{
	public $route = 'language';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('language');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('code')
			->name('Language')
			->formColumn(12)
			->createValidation('required|unique:languages,code,[id]', true)
			->inputType('select')
			->inputAttribute([
				'class' => ['select2']
			])
			->dataSource(config('module-setting.language.lists'));

		$this->structure[] = DataStructure::field('title')
			->name('Language Name')
			->hideForm();

		$this->structure[] = DataStructure::field('image')
			->name('Image Logo')
			->formColumn(12)
			->inputType('image')
			->searchable(false)
			->orderable(false);

		$this->structure[] = DataStructure::switcher('is_default_language', 'Is Default Language', 12, [
			0 => 'No',
			1 => 'Yes	'
		]);
		
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
			'code' => strtoupper($row->code),
			'title' => $row->title,
			'image' => $row->image ? $row->imageThumbnail('image', 'thumb', 50) : '<img src="'.asset('admin_theme/img/flag/'.$row->code.'.png').'" style="height:50px;" title="Default Icon">',
			'is_default_language' => $row->is_default_language ? '<span class="badge badge-success">YES</span>' : '<span class="badge badge-danger">No</span>',
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
		if($row->is_default_language){
			return;
		}
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