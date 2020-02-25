<?php
namespace Module\Main\Http\Repository;

use Image;
use Storage;
use Intervention\Image\Constraint;
use Module\Main\Jobs\GenerateImageThumbnail;

class ImageRepository
{

	public function handleMultipleUpload($files){
		$out = [];
		foreach($files as $file){
			$out[] = $this->handleUpload($file);
		}

		return $out;
	}

	public function handleRawUpload($file, $path='', $ext='jpg'){
		$filename = sha1(uniqid().time().rand(1,999999)); //kalo mw nyimpen by hash

        //bawa ke folder default dulu baru ke folder bulan utk normal request
        $path = strlen($path) == 0 ? 'default'.DIRECTORY_SEPARATOR.date('FY').DIRECTORY_SEPARATOR : $path.DIRECTORY_SEPARATOR;

        //kalau filename udh exists di storage, mending append hash di belakangnya
        if(Storage::exists($path.$filename.'.'.$ext)){
        	$filename = $filename . '-'.substr(sha1(rand(1,10000)), 5, 10);
        }

		$finalpath = $path.$filename.'.'.$ext;

		//save file asli
        Storage::put($finalpath, (string)$file);

        return $finalpath;
	}

	public function testFile($file){
		try{
			Image::make($file);
			return true;
		}catch(\Exception $e){
			return false;
		}
	}

	public function handleUpload($file, $path='', $filetype='jpg'){
		$image = Image::make($file)->orientate();

		$filename = sha1(uniqid().time().rand(1,999999)); //kalo mw nyimpen by hash


        if(is_string($file)){
        	if(strlen($file) < 255){
	        	//kalo by url path, method getClientOriginalExtension ga bisa dipake
	        	$pch = explode('/', $file);
	        	$filename = $pch[count($pch)-1];
	        	$ext = getExtension($filename);
        	}
        	else{
        		//set file langsung ke default extension. 
        		$ext = $filetype;
        	}
        }
        else{
	        $filename = $file->getClientOriginalName(); //kalo mw nyimpen by filename
	        $ext = $file->getClientOriginalExtension();
        }
        $filename = str_replace('.'.$ext, '', $filename);
        $filename = str_replace(' ', '-', $filename);

        //bawa ke folder default dulu baru ke folder bulan utk normal request
        $path = strlen($path) == 0 ? 'default'.DIRECTORY_SEPARATOR.date('FY').DIRECTORY_SEPARATOR : $path.DIRECTORY_SEPARATOR;

        //kalau filename udh exists di storage, mending append hash di belakangnya
        if(Storage::exists($path.$filename.'.'.$ext)){
        	$filename = $filename . '-'.substr(sha1(rand(1,10000)), 5, 10);
        }

		$finalpath = $path.$filename.'.'.$ext;

		$image = $image->encode($ext, config('image.quality'));
		//save file asli
        Storage::put($finalpath, (string)$image);

        //pastikan path file sudah benar, supaya background job bisa berjalan
        GenerateImageThumbnail::dispatch($finalpath);

        return $finalpath;
	}

	public function listName($path){
		$thumbs = config('image.thumbs');

		$pchPath = explode(".", $path);
		$extension = $pchPath[(count($pchPath)-1)];
		$filepath = str_replace('.'.$extension, '', $path);

		$out = [];
		foreach($thumbs as $name => $size){
			$out[$name] = $filepath . '-'.$name.'.'.$extension;
			if(config('image.enable_webp')){
				$out[$name.'-webp'] = $filepath . '-'.$name.'.webp';
			}
		}

		return $out;
	}

	public function getName($path, $type=''){
		$list = $this->listName($path);
		if(isset($list[$type])){
			return $list[$type];
		}
		return false;
	}


	protected function generateThumbnail($file, $finalpath, $filetype="jpg"){
		if(is_string($file)){
			if(strlen($file) < 255){			
				$extension = getExtension($file);
			}
			else{
				$extension = $filetype;
			}
		}
		else{
			$extension = $file->getClientOriginalExtension();
		}
		$thumbs = config('image.thumbs');
		
		//generate resized thumbnail
		foreach($thumbs as $name => $size){
	        $image = Image::make($file)->orientate()->resize(
	            $size,
	            null,
	            function (Constraint $constraint) {
	                $constraint->aspectRatio();
	            }
	        )->encode($extension, config('image.quality'));
	        Storage::put(
	            $finalpath.'-'.$name.'.'.$extension,
	            (string) $image
	        );
	        if(config('image.enable_webp')){
	        	$image = Image::make($file)->resize(
	            	$size,
		            null,
		            function (Constraint $constraint) {
		                $constraint->aspectRatio();
		            }
		        )->encode('webp', config('image.quality'));
		        Storage::put(
		            $finalpath.'-'.$name.'.webp',
		            (string) $image
		        );
	        }
		}
    }




    public function removeImage($filename, $pathAlready=false){
    	if(!$pathAlready){
	    	$filename = self::publicToPath($filename);
    	}

		//unlink the filename if exist
		self::unlinks($filename);

		//unlink the thumbnails too
		$thumbs = self::listName($filename);
		foreach($thumbs as $file){
			self::unlinks($file);
		}
    }

    protected function unlinks($path){
    	$ext = getExtension($path);
    	$webp_path= str_replace('.'.$ext, '.webp', $path);

		if(Storage::exists($path)){
			Storage::delete($path);
		}
		if(Storage::exists($webp_path)){
			Storage::delete($webp_path);
		}
    }


    public function publicToPath($public_url=''){
    	if(strpos($public_url, 'http://') !== false || strpos($public_url, 'https://') !== false){
	    	$explode = explode('/storage/', $public_url);
			if(count($explode) == 2){
				$filename = $explode[1];
			}
			else{
				$explode = explode('/', $public_url);
				$filename = $explode[(count($explode)-1)];
			}

			return $filename;
    	}
    	else{
    		//if no "http" or "https" detected, then it must be a path already
    		return $public_url;
    	}
    }

}