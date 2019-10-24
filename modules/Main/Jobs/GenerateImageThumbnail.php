<?php
namespace Module\Main\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Image;
use Storage;
use Intervention\Image\Constraint;

class GenerateImageThumbnail implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable;

	public 
		$image_path,
		$extension,
		$filename;

	public function __construct($path){
		$this->image_path = $path;
		$this->extension = getExtension($path);
		$this->filename = str_replace('.'.$this->extension, '', $this->image_path);
	}


	public function handle(){
		if(!file_exists($this->access_path($this->image_path))){
			die('Image source "'.$this->access_path($this->image_path).'" is not readable');
		}
		$image = Image::make($this->access_path($this->image_path))->orientate();

		//generate webp normal
		if(config('image.enable_webp')){
			$image = $image->encode('webp', config('image.quality'));
			//save file asli
			$this->saveFile($this->filename.'.webp', (string)$image);
			//echo 'Saved target : "'.$this->access_path($this->filename.'.webp').'" ';
		}

        //save thumbnail
        $this->generateThumbnail();

	}


	protected function generateThumbnail(){
		$thumbs = config('image.thumbs');
		
		//generate resized thumbnail
		foreach($thumbs as $name => $size){
	        $image = Image::make($this->access_path($this->image_path))->orientate()->resize(
	            $size,
	            null,
	            function (Constraint $constraint) {
	                $constraint->aspectRatio();
	            }
	        )->encode($this->extension, config('image.quality'));
	        $this->saveFile($this->filename.'-'.$name.'.'.$this->extension, (string)$image);
			//echo 'Saved target : "'.$this->access_path($this->filename.'-'.$name.'.'.$this->extension).'" ';

	        if(config('image.enable_webp')){
	        	$image = Image::make($this->access_path($this->image_path))->orientate()->resize(
	            	$size,
		            null,
		            function (Constraint $constraint) {
		                $constraint->aspectRatio();
		            }
		        )->encode('webp', config('image.quality'));
		        $this->saveFile($this->filename.'-'.$name.'.webp', (string)$image);
				//echo 'Saved target : "'.$this->access_path($this->filename.'-'.$name.'.webp').'" ';
	        }
		}

		//generate cropped thumbnail
		$image = Image::make($this->access_path($this->image_path))->orientate()->fit(config('image.crop'))->encode($this->extension, config('image.quality'));
		$this->saveFile($this->filename.'-cropped.'.$this->extension, (string)$image);
        //echo 'Saved target : "'.$this->access_path($this->filename.'-cropped.'.$this->extension).'" ';

		if(config('image.enable_webp')){
			$image = Image::make($this->access_path($this->image_path))->orientate()->fit(config('image.crop'))->encode('webp', config('image.quality'));
			$this->saveFile($this->filename.'-cropped.webp', (string)$image);
	        //echo 'Saved target : "'.$this->access_path($this->filename.'-cropped.webp').'" ';
		}
    }


	protected function saveFile($path, $data){
		try{
			$file = fopen($this->access_path($path), 'w');
			fwrite($file, $data);
			fclose($file);
		}catch(\Exception $e){
			die('Current user : ' . $cu .' - is not have a permission');
		}
	}


    public function access_path($path){
    	return public_path('storage/' . $path);
    }

}