<?php
namespace Core\Main\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Controllers\AdminBaseController;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogController extends AdminBaseController
{

    //default index page
    public function index(){
        $title = 'Log';
        $hint = $this->hint();

        $active_log = $this->request->active_log;
        $available_log = $this->getAvailableFileLog();
        $log_size = $this->getLogSize();

        $stored_log_count = model('log_master')->where('is_reported', 0)->count();
        $reported_stored_log_count = model('log_master')->where('is_reported', 1)->count();
        $stored_log = model('log_master')->where('is_reported', 0)->orderBy('created_at', 'DESC')->take(30)->get([
            'id', 'url', 'type', 'description', 'file_path', 'is_reported', 'created_at'
        ]);

        return view('main::module.log', compact(
            'title',
            'hint',
            'available_log',
            'active_log',
            'log_size',
            'stored_log',
            'stored_log_count',
            'reported_stored_log_count'
        ));
    }

    public function detail($id){
        $data = model('log_master')->find($id);
        if(empty($data)){
            abort(404);
        }

        $title = 'Log Detail';
        return view('main::module.log-detail', compact(
            'data',
            'title'
        ));
    }

    public function markAsReported(){
        $changed = model('log_master')->where('is_reported', 0)->update([
            'is_reported' => 1
        ]);

        if($changed){
            $msg = $changed .' error report has been marked as reported';
        }
        else{
            $msg = 'All error report has been marked';
        }

        return redirect()->route('admin.log.index')->with('success', $msg);
    }



    public function getLogSize(){
        $this->request->active_log;
        $ava = $this->getAvailableFileLog();
        if(in_array($this->request->active_log, $ava)){
            $filepath = $this->logPath($this->request->active_log);
            return filesize_formatted($filepath);
        }
        return false;
    }

    public function getFileLog($filename=''){
        $available = $this->getAvailableFileLog();
        if(strlen($filename) > 0){
            if(in_array($filename, $available)){
                $logpath = $this->logPath($filename);
                if(is_file($logpath)){
                    return file_get_contents($logpath);
                }
            }
        }
        return false;
    }


    public function getAvailableFileLog(){
        $path = $this->logPath();
        if(is_dir($path)){
            $list = scandir($path);
            //buang . , .. , .gitignore , laravel.log
            $list = array_values(array_diff($list, [
                '.',
                '..',
                '.gitignore'
            ]));

            if($list){
                return $list;
            }
        }
        return [];
    }

    protected function logPath($path=''){
        return storage_path('logs') . (strlen($path) > 0 ? '/'.$path : '');
    }


    public function export(){
        $active_log = $this->request->active_log;
        $file_log = $this->logPath($active_log);
        if(strlen($file_log) > 0){
            return response()->download($file_log);
        }
    }

	public function languageData(){
		return [
			'index.title' => 'Log Data',
			'create.title' => 'Add New Log',
			'edit.title' => 'Edit Log Data',

			'store.success' => 'Log data has been saved',
			'update.success' => 'Log data has been updated',
			'delete.success' => 'Log data has been deleted',
		];
	}

	public function delete($id=0){
		if(!$this->repo->exists($id)){
			abort(404);
		}

		$data = $this->repo->show($id);

		$this->repo->delete($id);
		$this->removeLanguage($data);

		return [
			'type' => 'success',
			'message' => 'Log has been deleted'
		];
	}


}