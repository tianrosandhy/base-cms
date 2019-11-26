<?php
namespace Module\Media\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MediaInstance;

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

	public function load(){
		$shortlink = $this->request->shortlink;
		$data = MediaInstance::content($shortlink);
		$links = MediaInstance::linkStructure($shortlink);
		return view('media::file-manager', compact(
			'data',
			'shortlink',
			'links'
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