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

	public function index(){
		$validate = self::validateInput();
		$validate->validate();

		//kalo sudah oke, proses
		$repo = new ImageRepository();

		//kalo ada parameter path, handle upload ke path folder ybs
        $path = '';
        if($this->request->path){
            $path = $this->request->path;
            //Garis miring ga diizinkan
            $path = str_replace('/', '', $path);
            $path = str_replace('\\', '', $path);
        }
        $handle = $repo->handleUpload($this->request->file, $path);
		
		return str_replace('\\', '/', $handle);
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

	public function tinyMce(){
		$validator = self::validateInput();
		if($validator->fails()){
			return [$validator->errors()->first()];
		}

		$data = $this->index();
		//tinymce needs "location" json field
		return [
			'location' => storage_url($data)
		];
	}

	protected function validateInput(){
		$validate = Validator::make($this->request->all(), [
			'file' => 'file|mimetypes:image/*|mimes:jpg,jpeg,png|max:'.(file_upload_max_size(config('cms.max_filesize.image')) / 1024)
		], [
			'file.file' => 'Please upload the image',
			'file.mimetypes' => 'Please upload valid image only',
			'file.mimes' => 'Please upload image file only',
			'file.max' => 'Sorry, image size is too large'
		]);
		
		return $validate;
	}



	public function removeImages(){
		$validate = Validator::make($this->request->all(), [
			'filename' => 'required'
		])->validate();

		//format url : 
		// http://cms-maxsol.test/storage/August2018/c2b7d9c4345ad160a0db9ff0313c2481bbb53662.jpg

		$repo = new ImageRepository();
		$repo->removeImage($this->request->filename);

		return [
			'type' => 'success', 
			'message' => 'Image file has been deleted'
		];
	}


}
