<?php
namespace Module\Media\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Media\Exceptions\MediaException;
use Module\Media\Models\Media;
use Storage;

class MediaInstance
{
	use Traits\Uploader;

	public 
		$base_dir, 
		$path, 
		$lists, 
		$structured;

	public function __construct(){
		$this->base_dir = public_path('storage');
	}

	public function input($name='image', $value=null, $attr=[]){
		return view('media::partials.input', compact(
			'name',
			'value',
			'attr'
		))->render();
	}

	public function inputMultiple($name='image[]', $value=[], $attr=[]){
		return view('media::partials.input-multiple', compact(
			'name',
			'value',
			'attr'
		))->render();
	}

	public function getImageById($id, $thumbname='origin', $force_thumbnail=true){
		$data = Media::find($id);
		if(!empty($data)){
			$grab = $thumbname;
			if($force_thumbnail){
				$grab = 'thumb';
			}
			return $data->getRawThumbnailUrl('path', $grab);
		}

		return $this->imageNotFoundUrl();
	}

	public function imageNotFoundUrl(){
		return admin_asset('img/broken-image.jpg');
	}


	public function content($page=1, $filter=[], $per_page=20){
		//available filters : filename, extension, date
		$media = new Media;
		if(isset($filter['filename'])){
			if(strlen($filter['filename']) > 0){
				$media = $media->where('filename', 'like', '%'.$filter['filename'].'%');
			}
		}
		if(isset($filter['extension'])){
			$media = $media->where('extension', 'jpeg');
		}
		if(isset($filter['date'])){
			$df = date('Y/m/', strtotime($filter['date']));
			$media = $media->where('path', 'like', '%'.$df.'%');
		}

		return $media->paginate($per_page, ['*'], 'page', $page);
	}


	public function remove($shortcode){
		$ret = false;
		if(Storage::exists($shortcode)){
			$deleted_name = $shortcode.'.deleted';
			Storage::move($shortcode, $deleted_name);
			$ret = true;
		}

		return $ret;
	}



	protected function getFileSize($shortlink=''){
		if(!Storage::exists($shortlink)){
			return false;
		}
		$filesize = filesize(Storage::path($shortlink));
		if($filesize > 1024){
			$filesize_string = $filesize / 1024;
			if($filesize_string > 1024){
				$filesize_string = $filesize_string / 1024;
				if($filesize_string > 1024){
					$filesize_string = number_format(($filesize_string/1024)) . ' GB';
				}
				else{
					$filesize_string = number_format($filesize_string) . ' MB';
				}
			}
			else{
				$filesize_string = number_format($filesize_string) . ' KB';
			}
		}
		else{
			$filesize_string = number_format($filesize) . ' Byte';
		}

		return $filesize_string;
	}

}