<?php
namespace Module\Main\Http\Traits;

trait RevisionManagement
{
	public function generateRevision($old_instance){
		//in case mau menyimpan data revisi dalam struktur yang lebih rumit, old_instance bisa diolah dan ditambah2kan dalam format array dulu.
		$revision_data = $old_instance->toArray();
		$this->storeRevision($revision_data);
	}

	

	public function getCurrentRevision($instance_id){
		$table_name = $this->repo->model->getTable();
		$grab = app(config('model.revision'))->where([
			'table' => $table_name,
			'primary_key' => $instance_id
		])->get();

		$out = [];
		if($grab->count() > 0){
			foreach($grab as $row){
				$out[$row->revision_no] = $this->reformatRevision(json_decode($row->revision_data, true));
			}
		}

		return $out;
	}

	public function getRevisionNo($instance_id, $revision_id){
		$lists = $this->getCurrentRevision($instance_id);
		if(isset($lists[$revision_id])){
			return $lists[$revision_id];
		}
		return false;
	}

	public function storeRevision($array_data, $id=null, $pk_field='id'){
		$array_data = (array)$array_data;
		if(empty($id) && isset($array_data[$pk_field])){
			$id = $array_data[$pk_field];
		}

		$table_name = $this->repo->model->getTable();
		$current = $this->getCurrentRevision($id);
		$revision_no = count($current) + 1;

		$rev = app(config('model.revision'));
		$rev->table = $table_name;
		$rev->primary_key = $id;
		$rev->revision_no = $revision_no;
		$rev->revision_data = json_encode($array_data);
		$rev->save();

		return $rev;
	}


	public function removeOldRevision($instance_id, $rev_no){
		$table_name = $this->repo->model->getTable();
		app(config('model.revision'))->where([
			'table' => $table_name,
			'primary_key' => $instance_id,
			'revision_no' => $rev_no
		])->delete();
	}


	public function restoreRevision($id, $revision_no=0){
		$grab = $this->getRevisionNo($id, $revision_no);
		$old_instance = $this->repo->show($id);
		if(!empty($grab) && !empty($old_instance)){
			//set old instance jadi revisi
			$this->generateRevision($old_instance);
			//restore current revision jadi active updated instance
			$this->activateRevision($old_instance, $grab);
			//karena data revisi sudah diaktifkan, revisi nomor lama boleh dihapus
			$this->removeOldRevision($id, $revision_no);
		}
		return redirect()->back()->with('success', 'Old data restored successfully');
	}


}