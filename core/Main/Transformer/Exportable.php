<?php
namespace Core\Main\Transformer;

use Core\Main\Http\Repository\CrudRepository;
use Maatwebsite\Excel\Facades\Excel;
use Core\Main\Excel\MainExport;

trait Exportable
{

	public function export(){
		if(!config('module-setting.'.$this->hint.'.export_excel')){
			return redirect()->route('admin.'.$this->hint.'.index')->withErrors([
				'error' => 'This module cannot be exported'
			]);
		}
		$this->exportConfig();
		$data = $this->repo->filter($this->exportCondition);
		$skeleton = $this->skeleton();

		$custom_field = isset($this->custom_field) ? $this->custom_field : [];

		$viewData = view('main::master-export', compact(
			'data',
			'skeleton',
			'custom_field'
		));

		return Excel::download(new MainExport($viewData), $this->hint.'-export-'.date('YmdHis').'.xlsx');
	}


	//action for generate blank excel export file
	public function exportExample(){
		if(!config('module-setting.'.$this->hint.'.export_excel')){
			return redirect()->route('admin.'.$this->hint.'.index')->withErrors([
				'error' => 'This module cannot be exported'
			]);
		}
		$this->exportConfig();
		$skeleton = $this->skeleton();

		$custom_field = isset($this->custom_field) ? $this->custom_field : [];

		$viewData = view('main::master-export', compact(
			'skeleton',
			'custom_field'
		));

		return Excel::download(new MainExport($viewData), $this->hint.'-export-example.xlsx');
	}


	public function exportCondition($config=[]){
		$this->exportCondition = $config;
	}

	public function hintUsed($alias=''){
		if(strlen($alias) > 0){
			$this->hintused = $alias;
		}
		else{
			$this->hintused = $this->model;
		}
		return $this->hintused;
	}

	public function exportOrderBy($field='id', $arah='DESC'){
		$this->exportOrder = $field;
		$this->exportArah = $arah;
	}

	//utk field diluar yg muncul di datatable
	public function setCustomField($input=[]){
		$this->custom_field = $input;
	}


	//default use
	public function exportConfig(){
		$this->hintUsed();
		$this->exportCondition();
		$this->exportOrderBy();
		$this->setCustomField();
	}


}