<?php
namespace Module\Main\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Module\Main\Http\Controllers\AdminBaseController;
use Validator;
use Storage;

class FileStore extends AdminBaseController
{

	public $store_target = 'files';

	public function index(){
		$validate = self::validateInput();

		//kalo sudah oke, proses
		$file = $this->request->file('file');
		$filename = $file->getClientOriginalName();
		$extension = $file->getClientOriginalExtension();
		$nameonly = str_replace('.'.$extension, '', $filename);

		//check if file already exists
		$check_exists = Storage::exists($this->store_target.'/'.$nameonly.'.'.$extension);
		if($check_exists){
			$stored_name = $nameonly.'-'.substr(sha1(rand(1, 10000)), 0, 10).'.'.$extension;
		}
		else{
			$stored_name = $nameonly.'.'.$extension;
		}

		$path = $this->request->file->storeAs($this->store_target, $stored_name);

		$data = [
			'url' => Storage::url(str_replace('\\', '/', $path)),
			'path' => str_replace('\\', '/', $path),
			'filename' => $stored_name,
		];

		return $data;

	}

	protected function validateInput(){
		$validate = Validator::make($this->request->all(), [
			'file' => 'file|max:'.(file_upload_max_size(config('cms.max_filesize.file')) / 1024)
		], [
			'file.file' => 'Please upload the file',
			'file.max' => 'File document is too large',
		])->validate();
	}



	public function removeFile(){
		$validate = Validator::make($this->request->all(), [
			'data' => 'required'
		])->validate();

		//format data : 
		/*
		[
			url -> http://lalala.test/storage/files/sadasdas.file
			path -> files/sdasas.file
			filename -> sdsads.file
		]
		*/

		$data = json_decode($this->request->data, true);
		if(isset($data['path'])){
			Storage::delete($data['path']);
		}

		return [
			'type' => 'success', 
			'message' => 'File '.(isset($data['filename']) ? $data['filename'] : null).' has been deleted'
		];
	}


}
