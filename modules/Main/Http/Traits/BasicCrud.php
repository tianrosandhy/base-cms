<?php
namespace Module\Main\Http\Traits;

use Module\Main\Contracts\WithRevision;

trait BasicCrud
{
	use RevisionManagement;

	public function prependIndex(){
		return '';
	}

	public function appendIndex(){
		return '';
	}

	public function appendIndexControlButton(){
		return '';
	}

	public function asAjax(){
		return $this->as_ajax;
	}

	//default index page
	public function index(){
		$datatable = $this->skeleton();
		$title = self::usedLang('index.title');
		$hint = $this->hint();
		$append_index = $this->appendIndex();
		$prepend_index = $this->prependIndex();
		$ctrl_button = $this->appendIndexControlButton();
		$as_ajax = $this->asAjax();
		return view(config('module-setting.'.$this->hint().'.view.index', 'main::master-table'), compact(
			'title',
			'hint',
			'datatable',
			'append_index',
			'prepend_index',
			'ctrl_button',
			'as_ajax'
		));
	}

	public function additionalField($data=null){
		//return html additional form data
		return '';
	}

	public function prependField($data=null){
		return '';
	}

	protected function modelTableListing(){
		$model = $this->repo->model;
        return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
	}

	protected function setMode($mode='view'){
		$this->mode = $mode;
	}

	protected function getUsedPlugin($skeleton){
		$collect = collect($skeleton);
		$used_plugin = [
			'gutenberg' => false,
			'dropzone' => false,
			'cropper' => false
		];

		if($collect->where('input_type', 'gutenberg')->count() > 0){
			$used_plugin['gutenberg'] = true;
		}
		if($collect->whereIn('input_type', ['image', 'image_multiple', 'file', 'file_multiple'])->count() > 0){
			$used_plugin['dropzone'] = true;
		}
		if($collect->where('input_type', 'cropper')->count() > 0){
			$used_plugin['cropper'] = true;
		}
		return $used_plugin;
	}

	public function create(){
		$title = self::usedLang('create.title');
		$forms = $this->skeleton();

		$used_plugin = $this->getUsedPlugin($forms->structure);

		$back = 'admin.'.$this->hint().'.index'; //back url
		$multi_language = isset($this->multi_language) ? $this->multi_language : false;
		$additional_field = $this->additionalField();

		$prepend_field = $this->prependField();
		$seo = '';
		if(method_exists($this, 'seoFields')){
			$seo = $this->seoFields();
		}

		//data = blank entity
		$data = app(config('model.'.$this->model));
		return view(config('module-setting.'.$this->hint().'.view.create', 'main::master-crud'), compact(
			'title',
			'forms',
			'back',
			'multi_language',
			'prepend_field',
			'additional_field',
			'seo',
			'data',
			'used_plugin'
		));
	}

	public function store(){
		$this->setMode('store');
		$this->skeleton()->formValidation($this->multi_language, 'create');
		$this->afterValidation('create');

		//multiple values / relational type input is not processed here
		$instance = $this->storeQuery();

		//multiple values / relational type can be freely managed here
		$this->afterCrud($instance);
		if(method_exists($this, 'storeSeo')){
			$this->storeSeo($instance);
		}

		if($this->multi_language){
			$this->storeLanguage($instance);
		}

		\CMS::log($instance, 'ADMIN STORE DATA');

		if(request()->ajax()){
			return [
				'type' => 'success',
				'message' => self::usedLang('store.success'),
				'force_draft' => (bool)$this->request->force_draft,
				'id' => $instance->id,
				'saveurl' => url()->route('admin.'.$this->hint.'.update', ['id' => $instance->id])
			];
		}
		return redirect()->route('admin.'. $this->hint() .'.index')->with('success', self::usedLang('store.success'));
	}

	//store process dipisah biar bisa dioverwrite
	public function storeQuery($is_active_field='is_active'){
		$inputData = self::getUsedField();

		//masing-masing input data difilter, apakah nama fieldnya exists atau tidak
		$listingColumn = $this->modelTableListing();
		foreach($inputData as $fld => $value){
			if(!in_array($fld, $listingColumn)){
				unset($inputData[$fld]);
			}
		}

		//kalau ada request force_draft, sekalipun pilih status aktif, maka status otomatis aktif
		if($this->request->force_draft){
			if(isset($inputData[$is_active_field])){
				$inputData[$is_active_field] = 0; //force to draft
			}
		}

		$instance = $this->repo->insert($inputData);
		return $instance;
	}

	public function edit($id){
		$title = self::usedLang('edit.title');
		$forms = $this->skeleton();
		$back = 'admin.'.$this->hint().'.index';
		$hint = $this->hint();

		$used_plugin = $this->getUsedPlugin($forms->structure);

		$data = $this->repo->show($id);
		if(empty($data)){
			return back()->withErrors([
				'error' => 'Data not found'
			]);
		}

		$multi_language = isset($this->multi_language) ? $this->multi_language : false;
		$prepend_field = $this->prependField($data);
		$additional_field = $this->additionalField($data);

		$seo = '';
		if(method_exists($this, 'seoFields')){
			$seo = $this->seoFields($data);
		}


		return view(config('module-setting.'.$this->hint().'.view.edit', 'main::master-crud'), compact(
			'title',
			'forms',
			'back',
			'data',
			'multi_language',
			'prepend_field',
			'additional_field',
			'seo',
			'used_plugin',
			'hint'
		));
	}

	public function update($id=0){
		//force_draft ga berlaku di update data, supaya datanya ga langsung berubah

		$this->setMode('update');
		$this->skeleton()->formValidation($this->multi_language, 'update', $id);
		$show = $this->repo->show($id);
		if(empty($show)){
			abort(404);
		}
		$this->afterValidation('update', $show);


		//gausa force draft kalo udah aktif
		if($this->request->force_draft){
			exit();
		}


		//ga perlu dijalankan dalam draft mode
		if(!$this->request->force_draft){
			//create revision before run the update
			if($this instanceof WithRevision){
				$this->generateRevision($show);
			}
		}


		//multiple values / relational type input is not processed here
		$instance = $this->updateQuery($id);
		//store SEO data
		if(method_exists($this, 'storeSeo')){
			$this->storeSeo($instance);
		}
		//multiple values / relational type can be freely managed here
		$this->afterCrud($instance);

		if($this->multi_language){
			$this->storeLanguage($instance);
		}

		\CMS::log($instance, 'ADMIN UPDATE DATA');

		if(request()->ajax()){
			return [
				'type' => 'success',
				'message' => self::usedLang('update.success'),
				'force_draft' => (bool)$this->request->force_draft,
				'id' => intval($id),
				'saveurl' => url()->route('admin.'.$this->hint.'.update', ['id' => $id])
			];
		}
		return redirect()->route('admin.'. $this->hint() .'.index')->with('success', self::usedLang('update.success'));
	}

	//update process dipisah biar bisa dioverwrite
	public function updateQuery($id, $is_active_field='is_active'){
		$inputData = self::getUsedField();

		//masing-masing input data difilter, apakah nama fieldnya exists atau tidak
		$listingColumn = $this->modelTableListing();
		foreach($inputData as $fld => $value){
			if(!in_array($fld, $listingColumn)){
				unset($inputData[$fld]);
			}
		}

		if($this->request->force_draft && isset($inputData[$is_active_field])){
			//memastikan data draft tidak jadi active dulu sampai benar2 disave
			unset($inputData[$is_active_field]);
		}

		$instance = $this->repo->update($id, $inputData);
		return $instance;
	}



	//ini method utk hard delete
	//soft delete menyusul

	public function afterDelete($id=0){
		return true;
	}

	public function delete($id=0){
		if($id == 0 && $this->request->list_id && is_array($this->request->list_id)){
			//batch remove checker
			$datas = [];
			foreach($this->request->list_id as $single_id){
				if($this->repo->exists($single_id)){
					$datas[] = $this->repo->show($single_id);
				}
			}
			if(empty($datas)){
				abort(404);
			}

			//delete loop process 
			$deleted_ids = [];
			foreach($datas as $row){
				$pk = $row->getKeyName();
				foreach($this->image_field() as $fld){
					$this->removeImage($row, $fld);
					$this->afterDelete($row->{$pk});
				}
				\CMS::log($row, 'ADMIN DELETE DATA');
				$deleted_ids[] = $row->{$pk};
				if($this->multi_language){
					$this->removeLanguage($row);
				}
			}
			if(count($deleted_ids) > 0){
				$this->repo->delete($deleted_ids);
			}

		}
		else{
			if(!$this->repo->exists($id)){
				abort(404);
			}

			$data = $this->repo->show($id);
			\CMS::log($data, 'ADMIN DELETE DATA');

			//remove image based on image fields 
			foreach($this->image_field() as $fld){
				$this->removeImage($data, $fld);
				$this->afterDelete($id);
			}
			$this->repo->delete($id);

			if($this->multi_language){
				$this->removeLanguage($data);
			}

		}

		return [
			'type' => 'success',
			'message' => self::usedLang('delete.success')
		];

	}







	protected function image_field(){
		return ['image'];
	}

	protected function usedLang($param=''){
		if(!isset($this->language[$param])){
			return false;
		}

		$langdata = $this->language[$param];

		if(is_array($langdata)){
			if(isset($langdata[current_lang()])){
				return $langdata[current_lang()];
			}
			if(isset($langdata[def_lang()])){
				return $langdata[def_lang()];
			}

			return false;
		}
		return $langdata;
	}

	protected function getUsedField(){
		$active_fields = $this->skeleton()->getActiveFormFields();
		$inputData = [];
		foreach($active_fields as $fields){
			if(isset($this->request->{$fields})){
				if($this->multi_language){
					$inputData[$fields] = get_lang($this->request->{$fields});
				}
				else{
					$inputData[$fields] = $this->request->{$fields};
				}
			}
		}

		return $inputData;
	}

}