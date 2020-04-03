<?php
namespace Core\Main\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class MainExport implements FromView
{
	public function __construct(View $view){
		$this->viewData = $view;
	}


    public function view(): View
    {
    	return $this->viewData;
    }
}