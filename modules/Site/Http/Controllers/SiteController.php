<?php
namespace Module\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use SiteInstance;

class SiteController extends Controller
{
	public function __construct(){

	}

	public function index(){
		return view('site::index');
	}
}