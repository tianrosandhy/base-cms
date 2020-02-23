<?php
namespace Module\Media\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Media\Exceptions\MediaException;
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

	protected function setCurrentPath($path){
		$this->path = $path;
		return $this;
	}

	public function content($path=''){
		$this->setCurrentPath($path);
		$this->getListByPath();
		$this->generateContentStructure();
		return $this->structured;
	}


	public function linkStructure($shortlink=''){
		$out[] = [
			'name' => '..',
			'target' => ''
		];

		if(strlen($shortlink) > 0){
			$pch = explode(DIRECTORY_SEPARATOR, $shortlink);
			$target = '';
			foreach($pch as $path){
				$target .= $path;
				$out[] = [
					'name' => $path,
					'target' => $target
				];
				$target .= DIRECTORY_SEPARATOR;
			}
		}
		return $out;
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





	protected function getListByPath($path=null){
		if(empty($path)){
			$path = $this->path;
		}

		$target_scan = $this->base_dir . (strlen($path) > 0 ? DIRECTORY_SEPARATOR . $path : '');

		$lists = [];
		if(file_exists($target_scan)){
			$lists = scandir($target_scan);
			//hapus . dan ..
			if(isset($lists[0]) && isset($lists[1])){
				unset($lists[0]);
				unset($lists[1]);
			}
			$lists = array_values($lists);
		}
		$this->lists = $lists;
		return $lists;
	}

	protected function generateContentStructure(){
		$allowed_extension = config('module-setting.media.allowed_extension');
		$out = [];
		foreach($this->lists as $file){
			$pch = explode('.', $file);
			if(count($pch) > 1){
				//file
				$extension = $pch[count($pch)-1];
				if(in_array(strtolower($extension), $allowed_extension)){
					$shortlink = $this->generateShortlink($file);

					$out[] = [
						'type' => 'file',
						'name' => $file,
						'shortlink' => $shortlink,
						'extension' => $extension,
						'filesize' => $this->getFileSize($shortlink)
					];
				}
			}
			else{
				//direktori
				$list = $this->getListByPath($file);
				$out[] = [
					'type' => 'directory',
					'name' => $file,
					'count' => count($list),
					'shortlink' => $this->generateShortlink($file)
				];
			}
		}

		$this->structured = $out;
		return $out;
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

	protected function generateShortlink($filename=''){
		if(strlen($this->path) > 0){
			return $this->path . DIRECTORY_SEPARATOR . $filename;
		}
		else{
			return $filename;
		}
	}
}