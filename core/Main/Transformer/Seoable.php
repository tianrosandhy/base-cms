<?php
namespace Core\Main\Transformer;

trait Seoable
{
	public function seoTitle(){
		return $this->title;
	}

	public function seoDescription(){
		if($this->excerpt){
			return descriptionMaker($this->excerpt, 15);
		}
	}

	public function seoKeyword(){
		return $this->tags;
	}

	public function seoImage(){
		return $this->image;
	}

	public function seoBase(){
		return $this->seo;
	}

	public function seoViewSource(){
		return 'main::inc.seo';
	}


	public function buildSeoTags($additional=[]){
		$config = $this->generateSeoQuery();
		foreach($additional as $key => $value){
			if(isset($config[$key])){
				if(empty($config[$key])){
					$config[$key] = $value;
				}
			}
			else{
				$config[$key] = $value;
			}
		}
		return view($this->seoViewSource(), compact(
			'config'
		))->render();
	}


	protected function generateSeoQuery(){
		$base = json_decode($this->seoBase(), true);
		if(!$base){
			$base = $this->generateSeoComponents();
		}

		//if the base is empty, then get components from seo methods 
		foreach($base as $key => $data){
			if(empty($data)){
				$call_method = 'seo' . ucfirst($key);
				$setValue = setting('seo.'.$key);
				if(method_exists($this, $call_method)){
					$setValue = $this->$call_method();
				}
				$base[$key] = $setValue;
			}
		}
		return $base;
	}

	protected function generateSeoComponents($data=null){
		return [
			'title' => isset($data['title']) ? $data['title'] : null,
			'keyword' => isset($data['keyword']) ? $data['keyword'] : null,
			'description' => isset($data['description']) ? $data['description'] : null,
			'image' => isset($data['image']) ? $data['image'] : null,
		];
	}
}