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
		$fileHash = str_replace('.' . $this->request->file->extension(), '', $this->request->file->hashName());
		$fileName = $fileHash . '.' . $this->request->file('file')->getClientOriginalExtension();

		$path = $this->request->file->storeAs('files', $fileName);

		$data = [
			'path' => storage_url(str_replace('\\', '/', $path)),
			'filename' => $fileName,
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
