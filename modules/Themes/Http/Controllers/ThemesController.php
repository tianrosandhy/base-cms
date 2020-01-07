<?php
namespace Module\Themes\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Main\Http\Middleware\AdminAuth;
use Auth;
use Illuminate\Validation\ValidationException;

use Illuminate\Contracts\Foundation\Application;
use Module\Themes\Manager\ThemeManager;

class ThemesController extends Controller
{
	protected $request;
	protected $themeManager;

	public function __construct(
		Application $app,
		Request $request
	) {
		$this->request = $request;
		$this->middleware('auth');
		$this->middleware(AdminAuth::class);
		$this->app = $app;
		$this->themeManager = new ThemeManager($this->app, config('appearances.themes.paths')[0]);
	}
	
	public function index(){
		dd($this->themeManager->allPublicThemes());
		echo "index";die();
	}

}