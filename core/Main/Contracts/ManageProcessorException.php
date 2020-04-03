<?php
namespace Core\Main\Contracts;

abstract class ManageProcessorException
{
	//register the exception name here
	abstract protected function setException();

	abstract public function validateRequest();

	protected function throwException($msg='', $data=[]){
		$base = [
			'message' => $msg,
		];
		$errs = $base + $data;
		$exception_class = $this->setException();
		throw new $exception_class(json_encode($errs));
	}
}