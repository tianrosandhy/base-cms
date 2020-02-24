<?php
namespace Module\Main\Services;

use DataStructure;
use Storage;

class FormServices
{


    public function getDefaultValue($value_source, $id=0){
        $model = config('model.'.$value_source[0]);
        $model = app($model);
        $grab = $model->where($value_source[1], $id)->get();
        $out = $grab->pluck($value_source[2]);
        if(!empty($out)){
            return $out->toArray();
        }
        return [];
    }

    public function inputMultilang($input, $default_value='', $data=null){
        $attr = self::manageAttributes($input);
        $oldVals = old($input->field, $default_value);
        $oldVal = isset($oldVals[config('cms.lang.default')]) ? $oldVals[config('cms.lang.default')] : '';

        $out = '';

        if(in_array($input->input_type, ['select', 'select_multiple', 'image', 'image_multiple', 'file', 'file_multiple', 'date', 'radio', 'checkbox', 'cropper', 'view', 'daterange', 'gutenberg']) || !$input->translate){
            //untuk input2 yg ga butuh multi language, langsung tampilkan default
            return self::getOutput($input, $attr, $oldVal, '', false, $data);
        }
        else{
            //loop input per tipe data bahasa
            foreach(config('cms.lang.available') as $lang){
                $ov = isset($oldVals[$lang]) ? $oldVals[$lang] : '';

                $out .= '<div class="input-language" data-lang="'.$lang.'" '. ($lang != config('cms.lang.default') ? 'style="display:none"' : '') .'>';
                $out .= self::getOutput($input, $attr, $ov, $lang, false, $data);
                $out .= '</div>';
            }
        }

        return $out;
    }

    public function input($input, $default='', $data=null){
        //fallback single alias method
        return $this->inputSinglelang($input, $default, $data);
    }

    public function inputSinglelang($input, $default_value='', $data=null){
        $attr = self::manageAttributes($input);
        $oldVals = old($input->field, $default_value);
        $oldVal = isset($oldVals) ? $oldVals : '';

        return self::getOutput($input, $attr, $oldVal, '', true, $data);

    }



    
    protected function manageTranslateField($input, $output='', $oldVals, $lang=''){


        if(in_array($input->input_type, ['select', 'select_multiple', 'image', 'image_multiple', 'file', 'file_multiple', 'date', 'radio', 'checkbox'])){
            return $output;
        }
        else{
            $ret = '<div class="input-language" data-lang="'.config('cms.lang.default').'">'.$output.'</div>';
            foreach(available_lang() as $lang){

                $ret .= '<div class="input-language" data-lang="'.$lang.'" style="display:none;">'.$out.'</div>';
            }
            return $ret;
        }
    }


    protected function getOutput($input, $attr, $oldVal, $lang='', $as_single=false, $data=null){
        if(strlen($lang) == 0){
            $lang = config('cms.lang.default');
        }

        if(in_array($input->input_type, ['text', 'email', 'number', 'color'])){
            $out = '<input type="'.$input->input_type.'" '.$attr.' value="'.$oldVal.'">';
        }
        else{
            if($input->input_type == 'view' || $input->input_type == 'daterange'){
                $out = view($input->view_source, [
                    'input' => $input,
                    'attr' => $attr,
                    'oldVal' => $oldVal,
                    'lang' => $lang,
                    'as_single' => $as_single,
                    'data' => $data
                ])->render();
            }
            if($input->input_type == 'slug'){
                $out = '<input type="text" '.$attr.' data-slug="'.$input->slug_target.'" value="'.$oldVal.'">';
            }
            if($input->input_type == "tags"){
                $out = '<input type="text" '.$attr.' data-role="tagsinput" value="'.$oldVal.'">';
            }
            if($input->input_type == 'tel'){
                $out = '<input type="'.$input->input_type.'" '.$attr.' data-mask="00000000000000" value="'.$oldVal.'">';
            }
            if($input->input_type == 'password'){
                $out = '<input type="'.$input->input_type.'" '.$attr.'>';
            }
            if($input->input_type == 'textarea'){
                $out = '<textarea data-textarea '.$attr.'>'.$oldVal.'</textarea><span class="feedback"></span>';
            }
            if($input->input_type == 'richtext'){
                $out = '<textarea '.$attr.' data-tinymce>'.$oldVal.'</textarea>';
            }
            if($input->input_type == 'gutenberg'){
                $out = '<textarea '.$attr.' hidden data-gutenberg>'.$oldVal.'</textarea>';
            }

            if($input->input_type == 'select'){
                $out = '<select '.$attr.'>';
                //ambil nilai dari data source
                $out .= '<option value=""></option>';
                $source = isset($input->data_source->output) ? $input->data_source->output : $input->data_source;
                if(isset($input->array_source)){
                    $source = call_user_func($input->array_source, $data);
                }

                foreach($source as $idd => $vall){
                    $out .= '<option value="'.$idd.'" '. ($oldVal == $idd ? 'selected' : '') .'>'.$vall.'</option>';
                }
                $out .='</select>';
            }
            if($input->input_type == 'select_multiple'){
                $out = '<select '.$attr.'>';
                //ambil nilai dari data source
                if(is_string($oldVal)){
                    //coba terjemahin ke json dulu
                    if(json_decode($oldVal)){
                        $oldVal = json_decode($oldVal);
                    }
                    else{
                        $oldVal = [];
                    }
                }

                if($oldVal instanceof \Illuminate\Database\Eloquent\Collection || $oldVal instanceof \Illuminate\Support\Collection){
                    $oldVal = $oldVal->toArray();
                }

                $source = isset($input->data_source->output) ? $input->data_source->output : $input->data_source;
                foreach($source as $idd => $vall){
                    $out .= '<option value="'.$idd.'" '. (in_array($idd, $oldVal) ? 'selected' : '') .'>'.$vall.'</option>';
                }
                $out .='</select>';
            }
            if($input->input_type == 'cropper'){
                $cfg = [
                    'value' => $oldVal,
                    'name' => $input->input_attribute['name'],
                    'horizontal' => true,
                    'x_ratio' => $input->cropper_ratio[0],
                    'y_ratio' => $input->cropper_ratio[1]
                ];

                if($input->imagedir_path){
                    $cfg['path'] = $input->imagedir_path;
                }
                $out = view('main::inc.cropper', $cfg);
            }
            if($input->input_type == 'image'){
                $cfg = [
                    'horizontal' => true
                ];
                if($input->imagedir_path){
                    $cfg['path'] = $input->imagedir_path;
                }
                $out = '<div>'.\MediaInstance::input($input->field, $oldVal, $cfg).'</div>';
            }
            if($input->input_type == 'image_multiple'){
                $cfg = [];
                if($input->imagedir_path){
                    $cfg['path'] = $input->imagedir_path;
                }
                $out = '<div>'.\MediaInstance::inputMultiple($input->field, $oldVal, $cfg).'</div>';
            }
            if(in_array($input->input_type, ['checkbox', 'radio'])){
                //buang attribute class form-control
                $attr = str_replace('form-control', '', $attr);

                $out = '<div class="box">';
                foreach($input->data_source as $idd=> $vall){
                    $out .= '<label class="radio-inline"><input value="'.$idd.'" type="'.$input->input_type.'" '.$attr.' '.($idd == $oldVal ? 'checked' : '').'> '.$vall.'</label> ';
                }
                $out .= '</div>';
            }
            if($input->input_type == 'file'){
                $out = view('main::inc.file-dropzone', [
                    'value' => $oldVal,
                    'name' => $input->input_attribute['name'],
                    'horizontal' => true
                ]);
            }

            if($input->input_type == 'file_multiple'){
                $out = view('main::inc.file-dropzone-multiple', [
                    'value' => $oldVal,
                    'name' => $input->input_attribute['name'],
                    'horizontal' => true
                ]);
            }

            if($input->input_type == 'date'){
                $out = '<input type="text" data-mask="0000-00-00" value="'.(strlen($oldVal) > 0 ? date('Y-m-d', strtotime($oldVal)) : '') .'" '.$attr.' data-datepicker>';
            }

            if($input->input_type == 'time'){
                $out = '<input type="text" data-mask="00:00" value="'.(strlen($oldVal) > 0 ? date('H:i', strtotime($oldVal)) : '') .'" '.$attr.' data-timepicker>';
            }

            if($input->input_type == 'datetime'){
                $out = '<input type="text" data-mask="0000-00-00 00:00:00" value="'.(strlen($oldVal) > 0 ? date('Y-m-d', strtotime($oldVal)) : '') .'" '.$attr.' data-datepicker>';
            }
        }

        if(!$as_single){
            $out = str_replace('['.config('cms.lang.default').']', '['.$lang.']', $out);
            $out = str_replace('-'.config('cms.lang.default').'"', '-'.$lang.'"', $out);
        }
        else{
            $out = str_replace('['.config('cms.lang.default').']', '', $out);
            $out = str_replace('-'.config('cms.lang.default').'"', '"', $out);
        }

        return $out;        
    }





    public function manageAttributes($input){
        $attr = '';
        if($input->input_type == 'select_multiple'){
            //tambah class select2 kalo select multiple
            $input->input_attribute['class'] = array_merge($input->input_attribute['class'], ['select2']); 
            $input->input_attribute['multiple'] = 'multiple';
        }
        if(strpos($input->create_validation, 'required') !== false){
//            $input->input_attribute['required'] = 'required';
        }
        if($input->input_type == 'textarea'){
            if(!isset($input->input_attribute['maxlength'])){
                $input->input_attribute['maxlength'] = 300;
            }
        }
        if(in_array($input->input_type, ['checkbox', 'radio'])){
            //buang id supaya ga error js duplicate id
            if(isset($input->input_attribute['id'])){
                $input->input_attribute['data-id'] = $input->input_attribute['id'];
                unset($input->input_attribute['id']);
            }
        }

        $attr = self::combineAttribute($input->input_attribute);
        return $attr;
    }

    public function combineAttribute($attribute){
        $attr = '';
        if($attribute){
            foreach($attribute as $atname => $val){
                if(is_array($val)){
                    $val = implode(' ', $val);
                }
                $attr .= $atname.'="'.$val.'" ';
            }
        }
        return $attr;
    }


}