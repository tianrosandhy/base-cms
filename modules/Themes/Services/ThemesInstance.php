<?php
namespace Module\Themes\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Themes\Exceptions\ThemesException;
use Module\Themes\Manager\ThemeManager;
use MediaInstance;
use Illuminate\Support\Str;

class ThemesInstance extends BaseInstance
{
    public 
        $stored,
        $active_theme,
        $compiled_config;

	public function __construct(){
		parent::__construct('themes');
        $this->theme_manager = new ThemeManager(app(), config('appearances.themes.paths')[0]);
        $this->getActiveTheme();
        $this->getStoredOptions();
        $this->compileStored();
	}

    public function __call($name, $args){
        $method = substr($name, 0, 3);
        if(in_array($method, ['get', 'set'])){
            $prop = substr($name, 3);
            $prop = Str::snake($prop);

            if(property_exists($this, $prop)){
                if($method == 'get'){
                    return $this->{$prop};
                }
                else if($method == 'set' && isset($arguments[0])){
                    $this->{$prop} = $arguments[0];
                    return $this;
                }
            }
        }
    }


    public function getActiveTheme(){
        $all_theme = collect($this->theme_manager->allPublicThemes());
        $active_theme = $all_theme->where('active', true)->first();
        $this->active_theme = $active_theme;
        return $active_theme;
    }

    public function allPublicThemes(){
        return $this->theme_manager->allPublicThemes();
    }

    public function grab($keyname=null, $lang=null){
        if(empty($lang)){
            $lang = def_lang();
        }
        $lang = strtolower($lang);

        $compiled = $this->compiled_config;
        if(strlen($keyname) == 0){
            //langsung return all compiled in case gaada parameter
            return $compiled;
        }
        $split = explode('.', $keyname);
        foreach($split as $keystring){
            if(isset($compiled[$keystring])){
                $compiled = $compiled[$keystring];
            }
            else{
                return false;
            }
        }

        //return utk single data
        if(is_array($compiled)){
            if(count($compiled) == 1){
                //manage bahasa
                $compiled = array_values($compiled);
                if(isset($compiled[0][def_lang()])){
                    //as language mode
                    if(isset($compiled[0][$lang])){
                        return $compiled[0][$lang];
                    }
                    else{
                        return $compiled[0][def_lang()];
                    }
                }

                return array_values($compiled)[0];
            }
        }

        return $compiled;
    }

    public function grabImage($keyname){
        $data = $this->grab($keyname);
        return MediaInstance::readJson($data);
    }


    public function compileStored(){
        //get stored values
        $stored = $this->stored;
        $out = [];
        if(config('cms.lang.active')){
            foreach(available_lang(true) as $lang){
                foreach($stored[$lang] as $key => $value){
                    $split = explode('.', $key);
                    //MAAF MASI MANUAL BANGET.. GA DAPET LOGIC LOOPNYA :(
                    if(count($split) == 6){
                        $out[$split[0]][$split[1]][$split[2]][$split[3]][$split[4]][$split[5]][$lang] = $value;
                    }
                    elseif(count($split) == 5){
                        $out[$split[0]][$split[1]][$split[2]][$split[3]][$split[4]][$lang] = $value;
                    }
                    elseif(count($split) == 4){
                        $out[$split[0]][$split[1]][$split[2]][$split[3]][$lang] = $value;
                    }
                    if(count($split) == 3){
                        $out[$split[0]][$split[1]][$split[2]][$lang] = $value;
                    }
                    if(count($split) == 2){
                        $out[$split[0]][$split[1]][$lang] = $value;
                    }
                    if(count($split) == 5){
                        $out[$split[0]][$split[1]][$split[2]][$split[3]][$split[4]][$lang] = $value;
                    }
                }
            }

        }
        else{
            foreach($stored as $key => $value){
                $split = explode('.', $key);
                //MAAF MASI MANUAL BANGET.. GA DAPET LOGIC LOOPNYA :(
                if(count($split) == 6){
                    $out[$split[0]][$split[1]][$split[2]][$split[3]][$split[4]][$split[5]] = $value;
                }
                elseif(count($split) == 5){
                    $out[$split[0]][$split[1]][$split[2]][$split[3]][$split[4]] = $value;
                }
                elseif(count($split) == 4){
                    $out[$split[0]][$split[1]][$split[2]][$split[3]] = $value;
                }
                if(count($split) == 3){
                    $out[$split[0]][$split[1]][$split[2]] = $value;
                }
                if(count($split) == 2){
                    $out[$split[0]][$split[1]] = $value;
                }
                if(count($split) == 5){
                    $out[$split[0]][$split[1]][$split[2]][$split[3]][$split[4]] = $value;
                }
            }

        }

        $this->compiled_config = $out;
    }


    public function getStoredOptions(){
        $datas = (new CrudRepository('themes'))->filter([
            ['theme', '=', $this->active_theme->getName()]
        ]);

        $stored = [];
        foreach($datas as $row){
            if(config('cms.lang.active')){
                foreach(available_lang(true) as $lang){
                    $stored[$lang][$row->key] = $row->outputTranslate('value', $lang);
                }
            }
            else{
                $stored[$row->key] = $row->value;
            }
        }
        $this->stored = $stored;
        return $this->stored;
    }

}