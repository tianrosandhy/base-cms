<?php
namespace Module\Main\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Module\Main\Http\Controllers\AdminBaseController;
use Validator;
use Storage;
use Module\Main\Http\Repository\ImageRepository;

class FileStore extends AdminBaseController
{

	public function index(){
		$validate = self::validateInput();

		//kalo sudah oke, proses
		$file = $this->request->file('file');
		$filename = $file->getClientOriginalName();
		$extension = $file->getClientOriginalExtension();
		$nameonly = str_replace('.'.$extension, '', $filename);

		//check if file already exists
		$check_exists = Storage::exists('files/'.$nameonly.'.'.$extension);
		if($check_exists){
			$stored_name = $nameonly.'-'.substr(sha1(rand(1, 10000)), 0, 10).'.'.$extension;
		}
		else{
			$stored_name = $nameonly.'.'.$extension;
		}

		$path = $this->request->file->storeAs('files', $stored_name);

		$data = [
			'path' => storage_url(str_replace('\\', '/', $path)),
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



	public function removeImages(){
		$validate = Validator::make($this->request->all(), [
			'data' => 'required'
		])->validate();

		//format data : 
		/*
		[
			path -> http://cms-maxsol.test/storage/August2018/c2b7d9c4345ad160a0db9ff0313c2481bbb53662.jpg
			filename -> lalala.pdf
		]
		*/

		$data = json_decode($this->request->data, true);
		if(isset($data['path'])){
			//remove image dan file punya action yg ga jauh berbeda
			$repo = new ImageRepository();
			$repo->removeImage($data['path']);
		}

		return [
			'type' => 'success', 
			'message' => 'File '.$data['filename'].' has been deleted'
		];
	}


}
