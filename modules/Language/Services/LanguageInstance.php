<?php
namespace Module\Language\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Language\Exceptions\LanguageException;

class LanguageInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('language');
	}

  public function available($all=false){
    $data = app(config('model.language'));
    if(!$all){
      $data = $data->where('is_default_language', 0);
    }
    $data = $data->orderBy('is_default_language', 'DESC')->orderBy('title', 'ASC')->get();

    if($data->count() == 0){
      $this->insert('en', 'English', true);
      return $this->available($all);
    }

    $out = [];
    foreach($data as $row){
      $out[$row->code] = $this->reformInstance($row);
    }

    return $out;
  }

  public function default(){
    $data = app(config('model.language'))->where('is_default_language', 1)->first();
    if(empty($data)){
      $this->insert('en', 'English', true);
      return $this->default();
    }
    return $this->reformInstance($data);
  }

  public function isActive(){
    $data = $this->model->count();
    if($data == 0){
      $this->insert('en', 'English', true);
      return false;
    }
    if($data > 1){
      return true;
    }
    return false;
  }


  public function all($order_by='id', $order_dir='ASC', $ignore_is_active=false, $is_active_field='is_active'){
    $grab = $this->model->all();
    if($grab->count() == 0){
      //create new default language instance
      $this->insert('en', 'English', true);
    }
    return $this->reformLanguage();
  }

  //amannya, hanya boleh dipanggil in case data bahasa kosong
  public function insert($code='en', $title='English', $default_language=false){
    $data = $this->model;
    $data->code = $code;
    $data->title = $title;
    $data->is_default_language = $default_language;
    $data->save();

    if($default_language){
      //hapus semua default language yg lain jika ada
      $this->model->where('id', '<>', $data->id)->update([
        'is_default_language' => 0
      ]);
    }

    return $data;
  }

  public function reformLanguage(){
    $data = $this->model->orderBy('is_default_language', 'DESC')->get();
    $out = [];
    foreach($data as $row){
      $out[$row->code] = $this->reformInstance($row);
    }
    $this->languages = $out;
    return $out;
  }

  public function reformInstance($instance){
    return [
      'id' => $instance->id,
      'code' => $instance->code,
      'title' => $instance->title,
      'image' => $instance->image ? $instance->getThumbnailUrl('image', 'small') : asset('admin_theme/img/flag/'.$instance->code.'.png'),
      'is_default_language' => (bool)$instance->is_default_language
    ];
  }

}