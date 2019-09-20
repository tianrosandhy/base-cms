<?php
namespace Module\Main\Services;
use Illuminate\Contracts\Foundation\Application;
use DataStructure;
use Storage;
use Module\Main\Models\Log;

class CmsServices
{

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function honeyForm($name, $time=15, $field_name='username'){
        $real_time = encrypt(time() + $time);
        return '<input type="hidden" name="'.$field_name.'" value=""><input type="hidden" name="timestamp" value="'.$real_time.'">';
    }

    public function log($data=[], $label='', $except=['password', '_token']){
        foreach($except as $prm){
            if(isset($data[$prm])){
                unset($data[$prm]);
            }
        }

            \Log::info('
URL => '.url()->current().'
LABEL => '.$label.'
USER_ID => '.(isset(\Auth::user()->id) ? \Auth::user()->id : '-').'
DATA => 
'.json_encode($data, JSON_PRETTY_PRINT).'

=====
');

    }

    public function navigation(){
        $navigation = config('cms.admin.menu');
        $navigation = collect($navigation);

        //sorting menu sesuai index sort
        $navigation = $navigation->sortBy('sort')->toArray();

        $out = [];

        //generate array data
        foreach($navigation as $group => $data){
            //pemilihan URL
            $url = self::manageUrl($data);
            if(!$url){
                //artinya ga punya akses
                continue;
            }

            $out[$group] = [
                'url' => $url['url'],
                'active' => $url['active'],
                'icon' => isset($data['icon']) ? $data['icon'] : ''
            ];

            if(isset($data['submenu'])){
                $out[$group]['submenu'] = [];
                foreach($data['submenu'] as $subgroup => $subdata){
                    $suburl = self::manageUrl($subdata);
                    if(!$suburl){
                        continue;
                    }
                    $out[$group]['submenu'][$subgroup] = [
                        'url' => $suburl['url'],
                        'active' => $suburl['active'],
                        'icon' => isset($subdata['icon']) ? $subdata['icon'] : ''
                    ];

                    if(isset($subdata['submenu'])){
                        $out[$group]['submenu'][$subgroup]['submenu'] = [];
                        foreach($subdata['submenu'] as $lastgroup => $lastdata){
                            $lasturl = self::manageUrl($lastdata);
                            if(!$lasturl){
                                continue;
                            }
                            $out[$group]['submenu'][$subgroup]['submenu'][$lastgroup] = [
                                'url' => $lasturl['url'],
                                'active' => $lasturl['active'],
                                'icon' => isset($lastdata['icon']) ? $lastdata['icon'] : ''
                            ];
                            
                        }
                    }

                    
                }
            }
        }


        //buang group yg submenunya kosong
        foreach($out as $group => $data){
            if(isset($data['submenu']) && $data['submenu'] == []){
                unset($out[$group]);
            }
        }

        return $out;
    }

    protected function manageUrl($data){
        $url = '#';
        $active = false;

        if(isset($data['url'])){
            if(strlen($data['url']) > 0){
                if($data['url'] <> '#'){
                    $url = admin_url($data['url']);
                }
                else{
                    $url = '#';
                }
            }
            else{
                $url = admin_url();
            }

            if($url == url()->current()){
                $active = true;
            }
        }
        if(isset($data['route'])){
            $current_route_name = \Request::route()->getName();

            if(\Route::has($data['route'])){
                //tapi kalo user ga punya akses ke route ini, skip aja
                if(has_access($data['route'])){
                    $url = url()->route($data['route']);
                }
                else{
                    return false;
                }
            }

            //buang nama route terakhir
            $trim = explode('.', $data['route']);
            unset($trim[count($trim)-1]);
            $trimmed = implode('.', $trim).'.';
            if(strpos($current_route_name, $trimmed) !== false){
                $active = true;
            }
        }

        return [
            'url' => $url,
            'active' => $active
        ];
    }
    

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

        if(in_array($input->input_type, ['select', 'select_multiple', 'image', 'image_multiple', 'file', 'file_multiple', 'date', 'radio', 'checkbox', 'cropper', 'view', 'daterange']) || !$input->translate){
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
            if($input->input_type == 'select'){
                $out = '<select '.$attr.'>';
                //ambil nilai dari data source
                $out .= '<option value=""></option>';
                $source = isset($input->data_source->output) ? $input->data_source->output : $input->data_source;
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
                    'value' => $oldVal,
                    'name' => $input->input_attribute['name'],
                    'horizontal' => true
                ];

                if($input->imagedir_path){
                    $cfg['path'] = $input->imagedir_path;
                }                
                $out = view('main::inc.dropzone', $cfg);
            }
            if($input->input_type == 'image_multiple'){
                $cfg = [
                    'value' => $oldVal,
                    'name' => $input->input_attribute['name']
                ];

                if($input->imagedir_path){
                    $cfg['path'] = $input->imagedir_path;
                }                
                $out = view('main::inc.dropzone-multiple', $cfg);
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