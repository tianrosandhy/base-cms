<?php
namespace Module\Main\Transformer;

use Storage;

trait Resizeable
{
	//MULTIPLE IMAGE
	public function getThumbnails($field, $thumb=''){
		$images_data = $this->{$field};
		if(strlen($images_data) == 0){
			return false;
		}

		if(strlen($thumb) == 0){
			$thumb = 'origin';
		}

		//exploder = |
		$pecah = explode("|", $images_data);
		$out = [];
		foreach($pecah as $gambar){
			$out[] = thumbnail($gambar, $thumb);
		}

		return $out;
	}

	public function getThumbnailsUrl($field, $thumb='', $fallback=true){
		$data = $this->getThumbnails($field, $thumb);
		$out = [];
		if(!$data){
			$out[$thumb] = admin_asset('img/broken-image.jpg');
			return $out;
		}
		foreach($data as $key => $row){
			if(Storage::exists($row)){
				$out[$key] = storage_url($row);
			}
			else{
				if($fallback){
					$out[$key] = admin_asset('img/broken-image.jpg');
				}
				else{
					$out[$key] = false;
				}
			}
		}
		return $out;
	}



	//SINGLE IMAGE
	public function getThumbnail($field, $thumb=''){
		$image_data = $this->{$field};
		if(strlen($image_data) == 0){
			return false;
		}

		if(strlen($thumb) == 0){
			$thumb = 'origin';
		}

		return thumbnail($image_data, $thumb);
	}

	public function getThumbnailUrl($field, $thumb='', $fallback=true){
		$thumb = $this->getThumbnail($field, $thumb);
		if(Storage::exists($thumb)){
			$url = storage_url($thumb);
			return str_replace("\\", '/', $url);
		}
		else{
			if($fallback){
				return admin_asset('img/broken-image.jpg');
			}
		}
		return false;
	}


	public function imageThumbnail($field_name, $thumb='', $height=80){
		$thumbnail = $this->getThumbnailUrl($field_name, $thumb);
		return '<img src="'.$thumbnail.'" style="height:'.$height.'px">';
	}

	public function imageThumbnails($field_name, $thumb='', $height=80){
		$thumbnails = $this->getThumbnailsUrl($field_name, $thumb);
		$out = '';
		foreach($thumbnails as $path){
			$out .= '<img src="'.$path.'" style="height:'.$height.'px"> ';
		}
		return $out;
	}
}