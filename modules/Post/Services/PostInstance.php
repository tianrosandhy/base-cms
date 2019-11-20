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
		return isset(\Auth::user()->id) ? \Auth::user()->id : null;
	}

}