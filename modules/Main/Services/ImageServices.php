<?php
namespace Module\Main\Services;
use Illuminate\Contracts\Foundation\Application;
use Storage;

class ImageServices
{

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->max_size = 1; //Batas show 1 MB
    }

    public function urlExists($url){
    	$path = $this->publicToPath($url);
    	$pathExists = Storage::exists($path);
    	if($pathExists && ($url == storage_url($path))){ //mencegah inputan dari URL lain
    		return true;
    	}
    	return false;
    }

    public function pathExists($path){
        if(Storage::exists($path)){
            return true;
        }
        return false;
    }


    public function getThumbnailInstance($path, $strict=false){
        if(Storage::exists($path)){
            $thumbs = config('image.thumbs');

            $finalpath = $path;
            foreach($thumbs as $tname => $size){
                $finalpath = str_replace('-'.$tname, '', $finalpath);
            }

            //dari finalpath tsb, grab thumbnail lists
            $tlist = thumbnail($finalpath);
            if(isset($tlist['thumb'])){
                $sz = filesize(\Storage::path($tlist['thumb']));
                if($sz > ($this->max_size * 1024 * 1024) && $strict){
                    return false;
                }
                else{
                    return $tlist['thumb'];
                }
            }
        }
    
        //kalau ga strict, return path asal        
        if(!$strict){
            return $path;
        }
        return false;
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


    public function testFile($file){
        try{
            Image::make($file);
            return true;
        }catch(\Exception $e){
            return false;
        }
    }
    
}