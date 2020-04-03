<?php
namespace Core\Main\Http\Traits;


trait BasicCrud
{
	use BasicCrudExtension;


	public function asAjax(){
		return $this->as_ajax;
	}

	//default index page
	public function index(){
		$datatable = $this->skeleton();
		$title = $this->usedLang('index.title');
		$hint = $this->hint();
		$append_index = method_exists($this, 'appendIndex') ? $this->appendIndex() : null;
		$prepend_index = method_exists($this, 'prependIndex') ? $this->prependIndex() : null;
		$ctrl_button = method_exists($this, 'appendIndexControlButton') ?  $this->appendIndexControlButton() : null;
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



	public function create(){
		$title = $this->usedLang('create.title');
		$forms = $this->skeleton();

		$used_plugin = $this->getUsedPlugin($forms->structure);

		$back = 'admin.'.$this->hint().'.index'; //back url
		$multi_language = isset($this->multi_language) ? $this->multi_language : false;
		$additional_field = method_exists($this, 'additionalField') ? $this->additionalField() : null;
		$prepend_field = method_exists($this, 'prependField') ? $this->prependField() : null;
		$seo = method_exists($this, 'seoFields') ? $this->seoFields() : null;

		//data = blank entity
		$data = model($this->model);
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
		$instance = $this->saveProcess();

		//multiple values / relational type can be freely managed here
		$this->afterCrud($instance);
		if(method_exists($this, 'storeSeo')){
			$this->storeSeo($instance);
		}

		if($this->multi_language){
			$this->storeLanguage($instance);
		}

		\CMS::log($instance, 'ADMIN STORE DATA');
		if($this->request->save_only){
			$redirect = redirect()->route('admin.'.$this->hint().'.edit', ['id' => $instance->id]);
		}
		else{
			$redirect = redirect()->route('admin.'. $this->hint() .'.index');
		}
		return $redirect->with('success', $this->usedLang('store.success'));
	}





	public function edit($id=null){
		$title = $this->usedLang('edit.title');
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
		$prepend_field = method_exists($this, 'prependField') ? $this->prependField($data) : null;
		$additional_field = method_exists($this, 'additionalField') ? $this->additionalField($data) : null;
		$seo = method_exists($this, 'seoFields') ? $this->seoFields($data) : null;

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
		$this->setMode('update');
		$this->skeleton()->formValidation($this->multi_language, 'update', $id);
		$show = $this->repo->show($id);
		if(empty($show)){
			abort(404);
		}
		$this->afterValidation('update', $show);

		//multiple values / relational type input is not processed here
		$instance = $this->saveProcess($show);
		$this->storeSlug($instance);

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

		if($this->request->save_only){
			$redirect = redirect()->back();
		}
		else{
			$redirect = redirect()->route('admin.'. $this->hint() .'.index');
		}
		return $redirect->with('success', $this->usedLang('update.success'));
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
				$this->afterDelete($row->{$pk});
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

			$this->afterDelete($id);
			$this->repo->delete($id);

			if($this->multi_language){
				$this->removeLanguage($data);
			}

		}

		return [
			'type' => 'success',
			'message' => $this->usedLang('delete.success')
		];

	}





}