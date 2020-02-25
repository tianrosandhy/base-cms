<?php
namespace Module\Main\Transformer;

use Storage;
use MediaInstance;

trait Resizeable
{

	//SINGLE IMAGE
	public function getThumbnail($field, $thumb=''){
		$image_data = $this->{$field};
		if(strlen($image_data) == 0){
			return false;
		}

		$decode = $this->decodeSingleImage($image_data);
		if(strlen($thumb) == 0 && isset($decode['thumb'])){
			$thumb = $decode['thumb'];
		}
		else if(strlen($thumb) == 0){
			$thumb = 'origin';
		}

		$image_ = $this->getDecodedImage($image_data);
		return thumbnail($image_, $thumb);
	}


	//MULTIPLE IMAGE
	public function getThumbnails($field, $thumb=''){
		$images_data = $this->{$field};

		if(strlen($images_data) == 0){
			return false;
		}

		if(strlen($thumb) == 0){
			$thumb = 'origin';
		}

		$parse = json_decode($images_data, true);
		$out = [];
		foreach($parse as $gambar){
			$image_ = $this->getDecodedImage($gambar);
			$out[] = thumbnail($image_, $thumb);
		}

		return $out;
	}

	protected function decodeSingleImage($json){
		return json_decode($json, true);
	}

	protected function getDecodedImage($json){
		$decoded = json_decode($json, true);
		$image_data = null;
		if(isset($decoded['id']) && isset($decoded['thumb'])){
			$image_data_object = app(config('model.media'))->find($decoded['id']);
			if(!empty($image_data_object)){
				$image_data = $image_data_object->path;
			}
		}

		return $image_data;
	}




	public function getThumbnailsSetUrl($field, $thumb='', $fallback=false){
		$list = json_decode( $this->{$field}, true);
		$grab = [];
		if(!$list){
			return '';
		}

		foreach($list as $limg){
			$parse = json_decode( $limg, true );
			if(isset($parse['id'])){
				$grab[] = $parse['id'];
			}
		}

		if(empty($grab)){
			return '';
		}

		$data = app(config('model.media'))->whereIn('id', $grab)->get();
		if(empty($data)){
			return '';
		}

		$paths = $data->pluck('path')->toArray();
		$out = [];
		foreach($paths as $url){
			$set = allImageSet($url);
			if(isset($set[$thumb.'-webp'])){
				$temp['webp'] = storage_url($set[$thumb.'-webp']);
			}
			if(isset($set[$thumb])){
				$temp['origin'] = storage_url($set[$thumb]);
			}
			else{
				$temp['origin'] = false;
			}
			$out[] = $temp;
		}
		return $out;
	}

	public function getThumbnailsUrl($field, $thumb='', $fallback=true){
		$data = $this->getThumbnails($field, $thumb);
		$out = [];
		if(!$data){
			$out[$thumb] = MediaInstance::imageNotFoundUrl();
			return $out;
		}
		foreach($data as $key => $row){
			if(Storage::exists($row)){
				$out[$key] = storage_url($row);
			}
			else{
				if($fallback){
					$out[$key] = MediaInstance::imageNotFoundUrl();
				}
				else{
					$out[$key] = false;
				}
			}
		}
		return $out;
	}



	public function getThumbnailUrl($field, $thumb='', $fallback=true){
		$thumb = $this->getThumbnail($field, $thumb);
		if(Storage::exists($thumb)){
			$url = storage_url($thumb);
			return str_replace("\\", '/', $url);
		}
		else{
			if($fallback){
				return MediaInstance::imageNotFoundUrl();
			}
		}
		return false;
	}

	public function getThumbnailSetUrl($field_name, $thumb='', $fallback=true){
		$grab = thumbnailSet($this->{$field_name}, $thumb, $fallback);
		$out = [];
		//ada webp dan normal
		foreach($grab as $lbl => $img){
			$out[$lbl] = storage_url($img);
		}
		return $out;
	}

	public function imageSetThumbnail($field_name, $thumb, $height=80, $fancybox_class='gallery'){
		if(!config('image.enable_webp')){
			return $this->imageThumbnail($field_name, $thumb, $height, $fancybox_class);
		}

		$list = $this->getThumbnailSetUrl($field_name, $thumb, false);
		$origin = $this->getThumbnailSetUrl($field_name, 'origin', false);
		if(count($list) == 2){
			return '
			<a href="'.(isset($origin['webp']) ? $origin['webp'] : $list['webp']).'" data-fancybox="'.$fancybox_class.'">
				<picture>
					<source srcset="'.$list['webp'].'" type="image/webp">
					<source srcset="'.$list['normal'].'">
					<img src="'.$list['normal'].'" style="height:'.$height.'px; max-width:100%;">
				</picture>
			</a>';
		}
		else if(count($list) == 1){
			$used = array_values($list)[0];
			return '
			<a href="'.(isset($origin['webp']) ? $origin['webp'] : $used).'" data-fancybox="'.$fancybox_class.'">
				<img src="'.$used.'" style="height:'.$height.'px; max-width:100%;">
			</a>';
		}
	}


	public function imageThumbnail($field_name, $thumb='', $height=80, $fancybox_class='gallery'){
		$thumbnail = $this->getThumbnailUrl($field_name, $thumb);
		return '<a href="'.$this->getThumbnailUrl($field_name, 'large').'" data-fancybox="'.$fancybox_class.'"><img src="'.$thumbnail.'" style="height:'.$height.'px; max-width:100%;"></a>';
	}

	public function imageSetThumbnails($field_name, $thumb, $height=80, $fancybox_class='gallery'){
		if(!config('image.enable_webp')){
			return $this->imageThumbnails($field_name, $thumb, $height, $fancybox_class);
		}

		$urls = $this->getThumbnailsSetUrl($field_name, $thumb, false);
		$out = '';
		foreach($urls as $url){
			$ahref = getOriginThumbnail($url['origin'], $thumb);
			$ahref_url = thumbnailSet($ahref, 'origin');

			if(count($url) == 2){
				$out .= '
				<a href="'.(isset($ahref_url['webp']) ? $ahref_url['webp'] : (isset($ahref_url['normal']) ? $ahref_url['normal'] : '#')).'" data-fancybox="'.$fancybox_class.'">
					<picture>
						<source srcset="'.$url['webp'].'" type="image/webp">
						<source srcset="'.$url['origin'].'">
						<img src="'.$url['origin'].'" style="height:'.$height.'px; max-width:100%;">
					</picture>
				</a>';
			}
			else if(count($url) == 1){
				$used = array_values($url)[0];
				$out .= '
				<a href="'.(isset($ahref_url['webp']) ? $ahref_url['webp'] : (isset($ahref_url['normal']) ? $ahref_url['normal'] : '#')).'" data-fancybox="'.$fancybox_class.'">
					<img src="'.$used.'" style="height:'.$height.'px; max-width:100%;">
				</a>';
			}
		}

		return $out;
	}

	public function imageThumbnails($field_name, $thumb='', $height=80){
		$thumbnails = $this->getThumbnailsUrl($field_name, $thumb);
		$out = '';
		foreach($thumbnails as $path){
			$out .= '<img src="'.$path.'" style="height:'.$height.'px"> ';
		}
		return $out;
	}


	public function getRawThumbnail($field_name, $thumb='origin'){
		$lists = thumbnail($this->{$field_name});
		if(isset($lists[$thumb])){
			return $lists[$thumb];
		}
		return $lists['origin'];
	}

	public function getRawThumbnailUrl($field_name, $thumb='origin'){
		$raw = $this->getRawThumbnail($field_name, $thumb);
		if($raw){
			return storage_url($raw);
		}
		else{
			return MediaInstance::imageNotFoundUrl();
		}
	}

}