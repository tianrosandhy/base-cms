<?php
namespace Module\Main\Services;
use Illuminate\Contracts\Foundation\Application;
use Module\Main\Http\Repository\ImageRepository;
use Storage;

class ImageServices extends ImageRepository
{

    public function __construct(Application $app)
    {
        $this->app = $app;
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

    
}