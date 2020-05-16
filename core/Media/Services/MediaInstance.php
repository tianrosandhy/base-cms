<?php
namespace Core\Media\Services;

use Core\Main\Http\Repository\CrudRepository;
use Core\Media\Exceptions\MediaException;
use Core\Media\Models\Media;
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

	public function assets(){
		return view('media::include-media')->render();
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

	public function grabInstance($single_json_data){
		$decode = json_decode($single_json_data, true);
		if(isset($decode['id'])){
			return Media::find($decode['id']);
		}
		return null;
	}

	public function readJson($single_json_data, $fallback=true){
		$decode = json_decode($single_json_data, true);
		if(isset($decode['id'])){
			$media = Media::find($decode['id']);
			if(!empty($media)){
				return $media->getRawThumbnailUrl('path', $decode['thumb']);
			}
		}

		if($fallback){
			return MediaInstance::imageNotFoundUrl();
		}
		return false;
	}

	public function grabJson($single_json_data, $thumb=null, $fallback=true){
		$decode = json_decode($single_json_data, true);
		if(isset($decode['id'])){
			$media = Media::find($decode['id']);
			if(!$thumb){
				$thumb = $decode['thumb'];
			}
			if(!empty($media)){
				return $media->getRawThumbnailUrl('path', $thumb);				
			}
		}

		if($fallback){
			return MediaInstance::imageNotFoundUrl();
		}
		return false;
	}

	public function readJsonPath($single_json_data){
		$decode = json_decode($single_json_data, true);
		if(isset($decode['id'])){
			$media = Media::find($decode['id']);
			if(!empty($media)){
				return $media->getRawThumbnail('path', $decode['thumb']);
			}
		}
		return false;
	}


	public function imageNotFoundUrl(){
		return admin_asset('img/broken-image.jpg');
	}


	public function content($page=1, $filter=[], $per_page=50){
		//available filters : filename, extension, date
		$media = new Media;
		$no_filter = true;
		if(isset($filter['filename'])){
			if(strlen($filter['filename']) > 0){
				$media = $media->where('filename', 'like', '%'.$filter['filename'].'%');
				$no_filter = false;
			}
		}
		if(isset($filter['extension'])){
			if(strlen($filter['extension']) > 0){
				$no_filter = false;
				if($filter['extension'] == 'another'){
					$media = $media->whereNotIn('mimetypes', ['image/jpeg', 'image/png', 'image/webp']);
				}
				else{
					$media = $media->where('mimetypes', $filter['extension']);
				}
			}
		}
		if(isset($filter['period'])){
			if(strtotime($filter['period']) > 0){
				$no_filter = false;
				$df = date('Y/m/', strtotime($filter['period']));
				$media = $media->where('path', 'like', '%'.$df.'%');
			}
		}

		return $media->orderBy('id', 'DESC')->paginate($per_page, ['*'], 'page', $page);
	}


	public function remove($id){
		$data = Media::find($id);
		if($data){
			//remove process
			$delete_list = [$data->path];
			foreach(config('image.thumbs') as $thumbname => $width){
				$basedir = str_replace($data->filename, '', $data->path);
				$delete_list[] = $basedir . $data->basename.'-'.$thumbname.'.'.$data->extension;
			}
			Storage::delete($delete_list);
		}

		//hapus file databasenya juga
		$data->delete();
		return true;
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