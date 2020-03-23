<?php
if(!function_exists('themeoption')){
    function themelength($to_response, $required=null){
        if(is_array($to_response)){
            if(isset($to_response[def_lang()])){
                return 1;
            }

            if(isset($required)){
                return isset($to_response[$required]) ? count($to_response[$required]) : null;
            }
            else{
                return isset(array_values($to_response)[0]) ? count(array_values($to_response)[0]) : null;
            }
        }
        return null;
    }

    function themeoption($key, $theme_context=null){
        if(empty($theme_context)){
            $response = ThemesInstance::grab($key, current_lang());
            if(is_string($response)){
                $try_decode = json_decode($response, true);
                if(isset($try_decode['id']) && isset($try_decode['thumb'])){
                    $response = MediaInstance::readJson($response, false);
                }
                else{
                    return $response;
                }
            }

            if($response){
                if(isset($response[def_lang()])){
                    $response = elang($response);
                }
                return $response;
            }
        }
        else{
            $response = $theme_context;
            $split_key = explode('.', $key);
            $output = $response;
            foreach($split_key as $index){
                if(isset($output[$index])){
                    $output = $output[$index];
                }
                else{
                    $output = null;
                }
            }
            if(isset($output[def_lang()])){
                return elang($output);
            }
            return $output;
        }

        return null;
    }
}

if(!function_exists('theme_asset')){
    function theme_asset($path, $asset_folder='assets'){
        $active = ThemesInstance::getActiveTheme();
        $theme_path = $active->getPath();
        $base_public = public_path('');
        $theme_final_path = str_replace($base_public, '', $theme_path);

        $theme_base_path = $theme_final_path.DIRECTORY_SEPARATOR.$asset_folder.DIRECTORY_SEPARATOR;
        $theme_base_path = str_replace('\\', '/', $theme_base_path);
        return url($theme_base_path.$path);
    }
}

