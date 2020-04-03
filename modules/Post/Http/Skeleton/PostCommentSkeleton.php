<?php
namespace Module\Post\Http\Skeleton;

use Core\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class PostCommentSkeleton extends DataTable
{
	public $route = 'post_comment';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('post_comment');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('name')
			->name('Name')
			->formColumn(12)
			->createValidation('required', true); //true = updatenya persis sama

		$this->structure[] = DataStructure::field('email')
			->name('Email')
			->formColumn(12)
			->inputType('email');

		$this->structure[] = DataStructure::field('phone')
			->name('Phone')
			->formColumn(12)
			->inputType('tel');

		$this->structure[] = DataStructure::field('message')
			->name('Message')
			->formColumn(12)
			->inputType('textarea');

		$this->structure[] = DataStructure::field('created_at')
			->name('Comment Date')
			->formColumn(12)
			->inputType('date');

		$this->structure[] = DataStructure::field('is_spam')
			->name('Is SPAM')
			->formColumn(12)
			->inputType('select')
			->dataSource([
				0 => 'No',
				1 => 'SPAM'
			]);

		$this->structure[] = DataStructure::field('reply_to')
			->name('Reply To')
			->formColumn(12)
			->inputType('select')
			->searchable(false)
			->dataSource(DataSource::model('post_comment')->options('name', [
				['is_active', '=', 1],
				['is_admin_reply', '=', 0]
			]));

		$this->structure[] = DataStructure::switcher('is_active', 'Is Active', 12);
		
	}

	//manage custom filtering if required
	public function additionalSearchFilter($context){
		return $context->where('is_admin_reply', null);
	}

	
	//MANAGE OUTPUT DATATABLE FORMAT PER BARIS
	public function rowFormat($row, $as_excel=false){
		return [
            'id' => $this->checkerFormat($row),
			'name' => $row->name,
			'email' => $row->email,
			'phone' => $row->phone,
			'message' => descriptionMaker($row->message),
			'reply_to' => (isset($row->parent->id) ? $row->parent->name.' ('.$row->parent->id.')' : '-'),
			'created_at' => date('d M Y H:i:s', strtotime($row->created_at)),
			'is_spam' => $row->is_spam ? '<span class="badge badge-danger">SPAM</span>' : '<span class="badge badge-success">No</span>',
			'is_active' => $this->switcher($row, 'is_active', 'admin.'.$this->route.'.switch'),
			'action' => self::detailButton($row) . self::deleteButton($row)
		];
	}

	protected function detailButton($row){
		if(has_access('admin.post.detail')){
			return $this->actionButton(
				'Detail', 
				url()->route('admin.post.detail', ['id' => $row->post_id]), 
				[
					'class' => ['btn', 'btn-sm', 'btn-secondary detail-btn'],
					'data-id' => $row->post_id
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