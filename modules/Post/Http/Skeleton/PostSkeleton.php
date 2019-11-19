<?php
namespace Module\Post\Http\Skeleton;

use Module\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class PostSkeleton extends DataTable
{
	public $route = 'post';

	//MANAGE STRUKTUR DATA KOLOM DAN FORM
	public function __construct(){
		$this->request = request();
		//default fields
		$this->setModel('post');

		$this->structure[] = DataStructure::checker();

		$this->structure[] = DataStructure::field('title')
			->name('Title')
			->formColumn(12)
			->createValidation('required', true); //true = updatenya persis sama

		$this->structure[] = DataStructure::field('slug')
			->name('Slug')
			->formColumn(12)
			->inputType('slug')
			->slugTarget('title')
			->createValidation('required|unique:posts,slug,[id]', true);

		$this->structure[] = DataStructure::field('category[]')
			->name('Category')
			->formColumn(6)
			->inputType('select_multiple')
			->dataSource(DataSource::model('post_category')->options('name', [
				['is_active', '=', 1]
			]))
			->arraySource(function($data){
				if(isset($data->category)){
					return $data->category->pluck('id');
				}
				return [];
			});

		$this->structure[] = DataStructure::field('tags')
			->name('Tags')
			->formColumn(6)
			->inputType('tags');

		$this->structure[] = DataStructure::field('excerpt')
			->name('Excerpt')
			->formColumn(12)
			->inputType('textarea')
			->hideTable();

		$this->structure[] = DataStructure::field('description')
			->name('Description')
			->formColumn(12)
			->inputType('richtext')
			->hideTable();

		$this->structure[] = DataStructure::field('image')
			->name('Featured Image')
			->formColumn(12)
			->inputType('image')
			->searchable(false)
			->orderable(false)
			->setImageDirPath(config('module-setting.post.upload_path'));

		$this->structure[] = DataStructure::switcher('is_active', 'Is Active', 12);
		
	}

	//manage custom filtering if required
	public function additionalSearchFilter($context){
		$cat = $this->grabColumn('category');
		if($cat){
			$context = $context->whereHas('category', function($qry) use($cat){
				$qry->where('post_categories.id', $cat);
			});
		}
		return $context;
	}

	
	//MANAGE OUTPUT DATATABLE FORMAT PER BARIS
	public function rowFormat($row, $as_excel=false){
		$category = '';
		if($row->category->count() > 0){
			foreach($row->category as $cat){
				$category .= '<span class="badge badge-primary mb-1">'.$cat->name.'</span> ';
			}
		}

		return [
            'id' => $this->checkerFormat($row),
			'title' => $row->title,
			'slug' => $row->slug,
			'category' => $category,
			'tags' => $row->tags,
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