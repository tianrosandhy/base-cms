<?php
namespace Module\Media\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MediaInstance;
use Module\Media\Exceptions\MediaException;

class MediaController extends Controller
{
	public $request;

	public function __construct(Request $request){
		$this->request = $request;
	}

	public function index(){
		$title = 'Media Library';
		$data = MediaInstance::content();

		return view('media::index', compact(
			'title',
			'data'
		));
	}

	public function upload(){
		try {
			$uploader = MediaInstance::upload($this->request->file);
		} catch (MediaException $e) {
			return response()->json([
				'type' => 'error',
				'message' => $e->getMessage()
			], 403);
		}

		return response()->json([
			'type' => 'success',
			'message' => $uploader->id
		]);
	}



	public function load(){
		$data = MediaInstance::content($this->request->page, $this->request->filter);
		$request = $this->request->all();
		return view('media::file-manager', compact(
			'data',
			'request'
		));
	}


	public function delete(){
		if(is_array($this->request->data)){
			foreach($this->request->data as $file){
				MediaInstance::remove($file);
			}
		}

		return [
			'type' => 'success',
			'message' => 'Files deleted successfully'
		];
	}

}