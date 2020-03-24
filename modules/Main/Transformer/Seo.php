<?php
namespace Module\Main\Transformer;

use LanguageInstance;

trait Seo
{
	public function seoFields($data=null){
		if(empty($data)){
			$data = $this->repo->model;
		}
		return view('main::inc.additional-seo', compact(
			'data'
		));
	}

	public function storeSeo($instance, $seo_field='seo'){
		$table = $instance->getTable();
		$listing = $instance->getConnection()->getSchemaBuilder()->getColumnListing($table);
		if(!in_array($seo_field, $listing)){
			//ga perlu save SEO kalau kolomnya not exists
			return;
		}

		//manage SEO json data
		$seo = [];
		foreach(available_lang(true) as $dl => $dparam){
			$seo[$dl]['title'] = isset($this->request->seo_title[$dl]) ? $this->request->seo_title[$dl] : '';
			$seo[$dl]['keyword'] = isset($this->request->seo_keyword[$dl]) ? $this->request->seo_keyword[$dl] : '';
			$seo[$dl]['description'] = isset($this->request->seo_description[$dl]) ? $this->request->seo_description[$dl] : '';
			$seo[$dl]['image'] = isset($this->request->seo_image) ? $this->request->seo_image : '';
		}

		//store SEO default language data
		if(isset($seo[def_lang()])){
			$instance->seo = json_encode($seo[def_lang()]);
			$instance->save();
		}

		//store translatable SEO language data if translateable is enabled
		if(LanguageInstance::isActive() && method_exists($instance, 'outputTranslate')){
			foreach(available_lang() as $lang => $langdata){
				if(isset($seo[$lang])){
					$this->insertLanguage($lang, $table, 'seo', $instance->id, json_encode($seo[$lang]));
				}
			}
		}

	}

	public function generateRawSeoTags($config=[]){
		//must include
		$required = ['title', 'keyword', 'description', 'image'];
		foreach($required as $key){
			if(!isset($config[$key])){
				$config[$key] = setting('seo.'.$key);
			}
		}
		return view('main::inc.seo', compact(
			'config'
		))->render();
	}

	public function generateSeoTags($instance, $config=[]){
		if(method_exists($instance, 'buildSeoTags')){
			return $instance->buildSeoTags($config);
		}
		else{
			return $this->generateRawSeoTags($config);
		}
	}

}
