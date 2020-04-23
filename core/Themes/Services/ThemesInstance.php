<?php
namespace Core\Themes\Services;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Services\BaseInstance;
use Core\Themes\Exceptions\ThemesException;
use Core\Themes\Manager\ThemeManager;
use Illuminate\Support\Str;
use MediaInstance;
use LanguageInstance;

class ThemesInstance extends BaseInstance
{
    public
        $stored,
        $active_theme,
        $compiled_config;

	public function __construct(){
		parent::__construct('themes');
        $this->theme_manager = new ThemeManager(app(), config('cms.themes.paths')[0]);
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

    public function hasDefaultValues(){
        return !empty($this->stored);
    }

    public function createDefaultValues(){
        if($this->hasDefaultValues()){
            //ndak perlu create default value kalau ternyata sudah ada data tema yg tersimpan
            return true;
        }
        //create default value instances by $this->active_theme->themeoption
        $out = [];
        if(isset($this->active_theme->themeoption)){
            $theme_option = $this->active_theme->themeoption;
            foreach($theme_option as $group => $data){
                foreach($data as $card => $subdata){
                    foreach($subdata as $field => $param){
                        $keyname = $group.'.'.$card.'.'.$field;
                        if(isset($param->default)){
                            $out[$keyname.'.0'] = $param->default;
                        }

                        if($param->type == 'array' && isset($param->loop)){
                            $ite = 0;
                            foreach($param->loop as $subfield => $subparam){
                                $subkeyname = $keyname.'.'.$subfield.'.0';
                                if(isset($subparam->default)){
                                    $out[$subkeyname] = $subparam->default;
                                }
                            }
                        }
                    }
                }
            }
        }

        if(!empty($out)){
            //store this out data as default theme option
            $savedata = [];
            foreach($out as $keyname => $value){
                $savedata[] = [
                    'theme' => $this->active_theme->getName(),
                    'key' => $keyname,
                    'value' => $value,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
            $this->model->insert($savedata);
        }
        return true;
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


    public function grabRaw($keyname=null){
        $keyname = str_replace('_', ' ', $keyname);

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
                $keystring = str_replace(' ', '_', $keystring);
                if(isset($compiled[$keystring])){
                    $compiled = $compiled[$keystring];
                }
                else{
                    return false;
                }
            }
        }

        //return utk single data
        if(is_array($compiled)){
            if(count($compiled) == 1){
                return array_values($compiled)[0];
            }
        }

        return $compiled;
    }


    public function grab($keyname=null, $lang=null){
        $keyname = str_replace('_', ' ', $keyname);

        if(empty($lang)){
            $lang = current_lang();
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
                $keystring = str_replace(' ', '_', $keystring);
                if(isset($compiled[$keystring])){
                    $compiled = $compiled[$keystring];
                }
                else{
                    return false;
                }
            }
        }

        //return utk single data
        if(is_array($compiled)){
            if(count($compiled) == 1){
                //manage bahasa
                $test_compiled = array_values($compiled);
                if(!is_array($test_compiled[0])){
                    return $test_compiled[0];
                }

                if(isset($test_compiled[0][def_lang()])){
                    //as language mode
                    if(isset($test_compiled[0][$lang])){
                        return $test_compiled[0][$lang];
                    }
                    else{
                        return $test_compiled[0][def_lang()];
                    }
                }
                else{
                    return $compiled;
                }
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
        if(LanguageInstance::isActive()){
            foreach(available_lang(true) as $lang => $langdata){
                //skipped undefined $stored[$lang] if not exists
                if(!isset($stored[$lang])){
                    continue;
                }
                $out = $this->compiledDataManipulation($stored[$lang]);
            }

        }
        else{
            $out = $this->compiledDataManipulation($stored);
        }

        $this->compiled_config = $out;
    }


    public function getStoredOptions(){
        $datas = (new CrudRepository('themes'))->filter([
            ['theme', '=', $this->active_theme->getName()]
        ]);

        $stored = [];
        foreach($datas as $row){
            if(LanguageInstance::isActive()){
                foreach(available_lang(true) as $lang => $langdata){
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

    // Need more testing
    private function compiledDataManipulation($data)
    {
        $out = [];

        foreach ($data as $key => $value) {
            $array = '';
            $split = explode('.', $key);
            $total = count($split);
            $lastIndex = $total - 1;

            unset($split[$lastIndex]);

            $string = implode('.', $split);
            $array = str_replace('.', '":{"', $string);
            $array = '{"'.$array.'":["'.$value.'"]';

            for ($i = 0; $i < $lastIndex; $i++) {
                $array .= '}';
            }

            $out = array_merge_recursive($out, json_decode($array, true));
        }

        return $out;
    }
}
