<?php
namespace Module\Contact\Http\Skeleton;

use Core\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class ContactSkeleton extends DataTable
{
	public $route = 'contact';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('contact');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('created_at')
			->name('Sent At')
			->formColumn(12)
			->inputType('datetime');

		$this->structure[] = DataStructure::field('full_name')
			->name('Full Name')
			->formColumn(12)
			->createValidation('required', true);

		$this->structure[] = DataStructure::field('email')
			->name('Email')
			->inputType('email')
			->formColumn(12);

		$this->structure[] = DataStructure::field('phone')
			->name('Phone')
			->formColumn(12);

		$this->structure[] = DataStructure::field('message')
			->name('Message')
			->formColumn(12)
			->inputType('textarea');

		$this->structure[] = DataStructure::switcher('is_spam', 'Is Spam', 6);
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
            'created_at' => date('d M Y H:i:s', strtotime($row->created_at)),
			'full_name' => strip_tags($row->full_name),
			'email' => strip_tags($row->email),
			'phone' => strip_tags($row->phone),
			'message' => descriptionMaker($row->message, 15),
			'is_spam' => has_access('admin.'.$this->route.'.switch') ? $this->switcher($row, 'is_spam', 'admin.'.$this->route.'.switch') : ( $row->is_spam ? '<span class="badge badge-danger">SPAM</span>' : '<span class="badge badge-success">Not Spam</span>'),
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