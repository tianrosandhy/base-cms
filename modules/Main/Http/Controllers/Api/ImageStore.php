<?php
namespace Module\Main\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Module\Main\Http\Controllers\AdminBaseController;
use Validator;
use Storage;
use Module\Main\Http\Repository\ImageRepository;
use Illuminate\Http\Request;

class ImageStore extends Controller
{
	public function __construct(Request $req){
		$this->request = $req;
	}

	public function cropper(){
		$image = $this->request->image;
		if(empty($image)){
			return [
				'type' => 'error',
				'message' => 'Invalid file'
			];
		}

		//test file
		$test = (new ImageRepository)->testFile($image);
		if(!$test){
			return [
				'type' => 'error',
				'message' => 'Invalid file. Please upload image type only'
			];
		}

		//hajar
		$path = '';
        if($this->request->path){
            $path = $this->request->path;
            //Garis miring ga diizinkan
            $path = str_replace('/', '', $path);
            $path = str_replace('\\', '', $path);
        }
		$file = (new ImageRepository)->handleUpload($image, $path);
		$final = str_replace('\\', '/', $file);
		return [
			'type' => 'success',
			'path' => $final,
			'url' => storage_url($final),
			'div_target' => $this->request->target
		];
	}


}
