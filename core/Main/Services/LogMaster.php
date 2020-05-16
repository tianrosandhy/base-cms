<?php
namespace Core\Main\Services;

use Core\Main\Models\LogMaster as Model;

class LogMaster
{

	public function __construct($exception=null){
		if($exception){
			$this->exception = $exception;
		}
	}

	public function store(){
		$type = $this->friendlyErrorType();
		$file = method_exists($this->exception, 'getFile') ? $this->exception->getFile() : null;
		$line = method_exists($this->exception, 'getLine') ? $this->exception->getLine() : null;
		$filepath = $file . ( $line ? ':'.$line : null );
		$description = $this->exception->getMessage();

		$check = Model::where('file_path', $filepath)
			->where('type', $type)
			->where('description', $description)
			->where('is_reported', 0)
			->count();

		//only record if the error is not in record and not reported
		if($check == 0){
			$model = new Model;
			$model->url = url()->current();
			$model->type = $type;
			$model->description = $description;
			$model->file_path = $filepath;
			$model->backtrace = $this->getExceptionTrace();
			$model->is_reported = 0;
			$model->save();
		}
	}

	protected function getExceptionTrace(){
		if(method_exists($this->exception, 'getTrace')){
			return json_encode($this->exception->getTrace());
		}
		return null;
	}

	protected function friendlyErrorType(){
		if(!method_exists($this->exception, 'getSeverity')){
			if(method_exists($this->exception, 'getStatusCode')){
				return $this->exception->getStatusCode();
			}
			if(method_exists($this->exception, 'getCode')){
				return $this->exception->getCode();
			}
			return 'UNDEFINED';
		}

		$severity = $this->exception->getSeverity();
	    switch($severity)
	    {
	        case E_ERROR: // 1 //
	            return 'E_ERROR';
	        case E_WARNING: // 2 //
	            return 'E_WARNING';
	        case E_PARSE: // 4 //
	            return 'E_PARSE';
	        case E_NOTICE: // 8 //
	            return 'E_NOTICE';
	        case E_CORE_ERROR: // 16 //
	            return 'E_CORE_ERROR';
	        case E_CORE_WARNING: // 32 //
	            return 'E_CORE_WARNING';
	        case E_COMPILE_ERROR: // 64 //
	            return 'E_COMPILE_ERROR';
	        case E_COMPILE_WARNING: // 128 //
	            return 'E_COMPILE_WARNING';
	        case E_USER_ERROR: // 256 //
	            return 'E_USER_ERROR';
	        case E_USER_WARNING: // 512 //
	            return 'E_USER_WARNING';
	        case E_USER_NOTICE: // 1024 //
	            return 'E_USER_NOTICE';
	        case E_STRICT: // 2048 //
	            return 'E_STRICT';
	        case E_RECOVERABLE_ERROR: // 4096 //
	            return 'E_RECOVERABLE_ERROR';
	        case E_DEPRECATED: // 8192 //
	            return 'E_DEPRECATED';
	        case E_USER_DEPRECATED: // 16384 //
	            return 'E_USER_DEPRECATED';
	    }
	    return null;
	}
}