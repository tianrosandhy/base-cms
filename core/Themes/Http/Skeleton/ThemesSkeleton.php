<?php
namespace Core\Themes\Http\Skeleton;

use Core\Main\DataTable\DataTable;
use DataStructure;
use DataSource;

class ThemesSkeleton extends DataTable
{
  public $route = 'themes';

  //MANAGE STRUKTUR DATA KOLOM DAN FORM
  public function __construct(){
    $this->request = request();
    //default fields
    $this->setModel('themes');

    $this->structure[] = DataStructure::field('theme')
      ->name('Theme Name')
      ->formColumn(12)
      ->orderable(false)
      ->searchable(false);

    $this->structure[] = DataStructure::field('status')
      ->name('Status')
      ->orderable(false)
      ->searchable(false);

  }

  
  //MANAGE OUTPUT DATATABLE FORMAT PER BARIS
  public function rowFormat($row, $as_excel=false){
    $theme_dir = $row->getPath();
    $theme_url = str_replace(public_path(), url('/'), $theme_dir);
    $ss_path = '/views/screenshot.jpg';

    if(is_file($theme_dir.$ss_path)){
      $screenshot_url = $theme_url.$ss_path;
    }
    else{
      $screenshot_url = \MediaInstance::imageNotFoundUrl();
    }

    return [
      'theme' => '
        <div style="float:left; margin-right:1em;">
          <img src="'.$screenshot_url.'" style="max-width:100px;">
        </div>
        <div style="float:left">
          <strong>'.ucwords($row->tname).'</strong><br><em>'.$row->getDescription().'</em>
        </div>
      ',
      'status' => '<input type="checkbox" data-init-plugin="switchery" data-size="small" name="themes-active" value="'.($row->active ? 'enable' : 'disable').'" data-theme="'.$row->tname.'" '.($row->active ? 'checked' : '').'>',
    ];
  }

}