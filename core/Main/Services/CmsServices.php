<?php
namespace Core\Main\Services;
use Illuminate\Contracts\Foundation\Application;
use DataStructure;
use Storage;
use Core\Main\Models\Log;

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
USER_ID => '.(isset(admin_guard()->user()->id) ? admin_guard()->user()->id : '-').'
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
    

}