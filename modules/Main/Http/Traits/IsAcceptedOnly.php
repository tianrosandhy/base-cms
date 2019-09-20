<?php
namespace Module\Main\Http\Traits;

trait IsAcceptedOnly
{
	public function additionalAcceptField($data=null){
		//accept cond : is_sa, or have accept permission
		$accept = false;
		if($this->request->get('is_sa')){
			$accept = true;
		}
		if(has_access('admin.'.$this->hint.'.accept')){
			$accept = true;
		}

		if($accept){
			return view('main::inc.show-accept-form', compact(
				'data'
			));
		}
	}

	public function afterCrudAccept($instance=null){
		if($this->request->IsAccepted){
			$instance->IsAccepted = $this->request->IsAccepted;
			$instance->save();
		}
	}
}