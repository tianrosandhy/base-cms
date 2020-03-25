<?php
namespace Module\Post\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Main\Exceptions\InstanceException;

class PostInstance extends BaseInstance
{
	public $data;

	public function __construct(){
		parent::__construct('post');
	}



	public function homePreview($limit=4){
		try{
			return $this->model->where('featured', 1)->orderBy('id', 'DESC')->take($limit)->get();
		}catch(\Exception $e){
			return $this->model->orderBy('id', 'DESC')->take($limit)->get();
		}
	}

	public function response($param=[]){
		$data = $this->model->where('is_active', 1);
		if(isset($param['keyword'])){
			if(strlen($param['keyword']) > 0){
				$keyword = str_replace(' ', '%', $param['keyword']);
				$data = $data->where('title', 'like', '%'.$keyword.'%');
			}
		}
		if(isset($param['category'])){
			$data = $data->whereHas('category', function($qry) use($param){
				$qry->where('post_categories.id', $param['category']);
			});
		}

		$per_page = isset($param['per_page']) ? intval($param['per_page']) : 15;
		$current_page = isset($param['page']) ? intval($param['page']) : 1;
		return $data->orderBy('id', 'DESC')->paginate($per_page, ['*'], 'page', $current_page);

	}

	public function categories(){
		return (new CrudRepository('post_category'))->filter([
			['is_active', '=', 1]
		]);
	}

	public function structure(){
		if($this->data){
			$out = $this->data->toArray();
			$out['category'] = [];
			$out['related'] = [];
			$out['likes'] = [];

			$out['image'] = $this->data->getThumbnailUrl('image', 'origin');
			$out['image_list'] = $this->data->listAllThumbnail('image');
			$out['seo'] = isset($this->data->seo) ? json_decode($this->data->seo, true) : [];

			$tags = explode(',', $this->data->tags);
			$out['tags'] = $tags;
			foreach($this->data->category as $cat){
				$out['category'][$cat->id] = [
					'id' => $cat->id,
					'name' => $cat->name,
					'slug' => $cat->slug(),
				];
			}
			
			foreach($this->data->likes as $li){
				$out['likes'][$li->id] = [
					'id' => $li->id,
					'ip' => $li->ip,
					'user_id' => $li->user_id,
				];
			}
			
			return $out;
		}
		else{
			throw new InstanceException(($this->getMessage('NO_DATA_DEFINED')));
		}
	}


	public function comment($data=[], $as_admin=false){
		if($this->data){
			if($as_admin){
				$data['name'] = 'Administrator';
				$data['email'] = setting('site.email', 'admin@localhost');
				$data['phone'] = setting('site.phone');
				$data['is_admin_reply'] = 1;
				$data['is_active'] = 1;
			}
			else{
				$data['is_admin_reply'] = null;
				$data['is_active'] = 0;
			}

			$com = app(config('model.post_comment'));
			$com->post_id = $this->data->id;
			foreach($data as $fld => $val){
				$com->{$fld} = $val;
			}
			try{
				$com->save();
			}catch(\Exception $e){
				throw new InstanceException($this->getMessage('SAVE_FAILED'));
			}

			return $com;
		}
		else{
			throw new InstanceException($this->getMessage('NO_DATA_DEFINED'));
		}
	}


	//custom handler for post only instance
	public function like(){
		if($this->data){
			$likes = $this->getLikeInstance($this->data->id);
			if(empty($likes)){
				$like = app(config('model.post_like'));
				$like->post_id = $this->data->id;
				$like->ip = request()->ip();
				$like->user_id = $this->getUserId();
				$like->save();
			}

			return $this;
		}
		else{
			throw new InstanceException($this->getMessage('NO_DATA_DEFINED'));
		}
	}

	public function unlike(){
		if($this->data){
			$likes = $this->getLikeInstance($this->data->id);
			if(!empty($likes)){
				$likes->delete();
			}
			return $this;
		}
		else{
			throw new InstanceException($this->getMessage('NO_DATA_DEFINED'));
		}
	}

	protected function getLikeInstance($post_id){
		$likes = app(config('model.post_like'))
			->where('post_id', $post_id)
			->where('ip', request()->ip());

		$user_id = $this->getUserId();
		if($user_id){
			$likes = $likes->orWhere('user_id', $user_id);
		}
		$likes = $likes->first();
		return $likes;
	}

	protected function getUserId(){
		return isset(admin_guard()->user()->id) ? admin_guard()->user()->id : null;
	}

}