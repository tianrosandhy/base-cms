<?php
namespace Core\Main\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Middleware\AdminAuth;
use Auth;
use Illuminate\Validation\ValidationException;

class AdminBaseController extends Controller
{

	public 
		$request,
		$model,
		$hint,
		$module,
		$repo,
		$skeleton,
		$language = [],
		$multi_language = false,
		$as_ajax = false;

	public function __construct(Request $req){
		$this->request = $req;
		$this->middleware(AdminAuth::class);

		//register repository globally
		if(method_exists($this, 'repo')){
			$this->model = $this->repo();
			$this->useRepo($this->repo());
		}

		if(method_exists($this, 'languageData')){
			$this->language = $this->languageData();
		}
	}

	// alias management.
	// list alias : model (repo), route, config, module, translation module, translation name
	public function getRouteAlias(){
		if(method_exists($this, 'routeAlias')){
			return $this->routeAlias();
		}
		//$this->hint sbg base alias
		return $this->hint;
	}

	public function getConfigAlias(){
		if(method_exists($this, 'configAlias')){
			return $this->configAlias();
		}
		return $this->hint;
	}

	public function getTranslationModuleAlias(){
		if(method_exists($this, 'translationModuleAlias')){
			return $this->translationModuleAlias();
		}
		return $this->hint;
	}

	public function getTranslationNameAlias(){
		if(method_exists($this, 'translationNameAlias')){
			return $this->translationNameAlias();
		}
		return $this->hint;
	}

	public function allAlias(){
		return [
			'hint' => $this->hint(),
			'route' => $this->getRouteAlias(),
			'config' => $this->getConfigAlias(),
			'translation_module' => $this->getTranslationModuleAlias(),
			'translation_name' => $this->getTranslationNameAlias(),
		];
	}
	// list alias finish


	public function useRepo($alias=''){
		$relation = null;
		if(method_exists($this, 'repoRelation')){
			if(!empty($this->repoRelation())){
				$relation = $this->repoRelation();
			}
		}

		if(!empty($relation)){
			$this->repo = (new CrudRepository($this->repo()))->with($relation);
		}
		else{
			$this->repo = new CrudRepository($this->repo());
		}
	}

	public function languageData(){
		//grab data bahasa langsung dari config aja sekalian skrg
		return config('module-setting.'.$this->hint.'.lang_data');
	}


	public function hint($var=''){
		if(strlen($var) > 0){
			$this->hint = $var;
			return $var;
		}
		return $this->hint;
	}
	


	//utk request datatable
	public function table(){
		//harus ada kesempatan mengupdate skeleton data, karena ga semua data ready di __construct()
		return $this->skeleton()->table();
	}




	public function afterValidation($mode='create', $instance=null){
		//
	}


	public function storeLanguage($instance=[]){
		if(empty($instance)){
			return false;
		}

		//get table name from instance
		$table = $instance->getTable();
		$id = $instance->id;
		$post = $this->request->all();

		if(isset($post['_token'])){
			unset($post['_token']);
		}

		$n = 0;
		foreach(available_lang() as $lang => $langdata){
			foreach($post as $field => $item){
				if(isset($item[$lang])){
					$content = $item[$lang];
					if(strpos($field, 'seo_') === false){
						$n += self::insertLanguage($lang, $table, $field, $id, $content);
					}
				}
			}
		}

		return $n;
	}

	protected function insertLanguage($lang, $table, $field, $id, $content){
		if(empty($content)){
			return ; //kalau content kosong, gausa save aja sekalian.. buang2 waktu
		}
		$repo = (new CrudRepository('translator'));
		$cek = $repo->filterFirst([
			['table', '=', $table],
			['field', '=', $field],
			['lang', '=', $lang],
			['id_field', '=', $id]
		]);

		if(!empty($cek)){
			//update
			$cek->content = $content;
			$cek->save();
		}
		else{
			$repo->insert([
				'table' => $table,
				'field' => $field,
				'lang' => $lang,
				'id_field' => $id,
				'content' => $content
			]);
		}
		//buat increment aja. hehe
		return 1;
	}

	//ketika delete data, dan ingin remove language datanya juga
	public function removeLanguage($instance){
		$repo = (new CrudRepository('translator'));
		$table = $instance->getTable();
		$id = $instance->id;

		$data = $repo->filter([
			['table', '=', $table],
			['id_field', '=', $id]
		]);

		if($data->count() > 0){
			$ids = $data->pluck('id')->toArray();
			$repo->delete($ids); //remove all IDs
		}
	}

	public function removeImage($instance, $field='image'){
		//deprecated
	}

	public function currentUser(){
		return admin_guard()->user();
	}

	public function manageThrown($exception){
		$json = [];
        if(json_decode($exception->getMessage())){
            $msg = json_decode($exception->getMessage(), true);
            if($this->request->ajax()){
            	$first = isset($msg['message']) ? $msg['message'] : $msg;
            	return [
            		'type' => 'error',
            		'message' => $first
            	];
            }
	        $error = ValidationException::withMessages(isset($msg['message']) ? $msg['message'] : $msg);
	        throw $error;
        }
        else{
            $msg = $exception->getMessage();
            if($this->request->ajax()){
            	return [
            		'type' => 'error',
            		'message' => $msg
            	];
            }
            return redirect()->back()->with('error', $msg)->withInput();
        }
	}

}