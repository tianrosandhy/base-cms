<?php
function redate($date_format, $date_data){
	$timezone = config('app.timezone');

	//generate date as GMT+0
	$date = new DateTime(date($date_format, $date_data), new DateTimeZone('UTC'));
	$date->setTimezone(new DateTimeZone($timezone));
	return $date->format($date_format);
}

function datethis($date_object, $format='d M Y'){
  return redate($format, strtotime($date_object));
}

function nonce_generate($age=86400){
	$time = time() + $age;
	return encrypt($time);
}

function nonce_validate($nonce=''){
	$test = decrypt($nonce);
	if($test){
		if($test > time()){
			return true;
		}
	}
	return false;
}

function fe_asset($path){
	$separator = '/';
	if(substr($path, 0, 1) == '/'){
		$separator = '';
	}
	return url(config('cms.front.assets') . $separator . $path);
}

function fe_asset_mix($path){
	$separator = '/';
	if(substr($path, 0, 1) == '/'){
		$separator = '';
	}

	return asset(mix(config('cms.front.assets') . $separator . $path));
}

function admin_url($url=''){
	$prefix = admin_prefix();
	if(strlen($prefix) <= 1){
		return url($url);
	}
	return url($prefix . '/'. $url);
}

function storage_url($path=''){
  return Storage::url($path);
//	return url(config('cms.storage_path', 'storage')) . (strlen($path) > 0 ? '/' . $path : '');
}

function admin_prefix($path=''){
	return config('cms.admin.prefix', 'p4n3lb04rd') . (strlen($path) > 0 ? '/' . $path : '');
}

function admin_asset($path){
	return asset(config('cms.admin.assets') . '/'. $path);
}

function admin_guard_name(){
	return config('cms.admin.auth_guard_name', 'web');
}

function admin_guard(){
	return Auth::guard(admin_guard_name());
}

function is_admin_login(){
	return admin_guard()->check();
}

function clean_input($string=''){
	return preg_replace('/[^A-Za-z0-9\- ]/', '', $string);
}

function admin_data($field=''){
	if(admin_guard()->check()){
		$user_data = admin_guard()->user()->toArray();
		if(strlen($field) == 0)
			return $user_data;
		else{
			if(isset($user_data[$field])){
				return $user_data[$field];
			}
		}
	}
	return false;
}

function is_sa(){
	$sa = isset(admin_guard()->user()->roles->is_sa) ? admin_guard()->user()->roles->is_sa : false;
	return (bool)$sa;
}

function has_access($route_name=''){
	if(!is_admin_login()){
		return false;
	}

	//auth bawaan laravel ga ada relasi ke roles, jadi harus dibuat sendiri
	$user = admin_guard()->user();
	$role = $user->role_id;
	$roles = new \Module\Main\Models\Role();
	$roles_data = $roles->find($role);
	if(empty($roles_data)){
		return false;
	}

	if($roles_data->is_sa){
		//super admin always have access in every page
		return true;
	}

	$roles_data = isset($roles_data->priviledge_list) ? $roles_data->priviledge_list : '';
	$priviledge = json_decode($roles_data);
	$priviledge = $priviledge ? $priviledge : [];

	return in_array($route_name, $priviledge);
}

function all_priviledges(){
	$data = config('permission');
	$out = [];
	foreach($data as $group => $items){
		foreach($items as $lists){
			$out = array_merge($out, $lists);
		}
	}
	return $out;
}

function setting($hint, $default=''){
	$hint = strtolower($hint);
	$exp = explode('.', $hint);
	if(isset($exp[0])){
		$group = $exp[0];
	}
	else{
		return $default;
	}
	if(isset($exp[1])){
		$param = $exp[1];
	}
	else{
		return $default;
	}

  $cek = app(config('model.setting_structure'))->where('group', $group)->where('param', $param)->first();
	if(isset($cek->default_value)){
		if(strlen($cek->default_value) == 0){
			return $default;
		}
		else{
      //check setting type. if type=image, then output the image path
      if($cek->type == 'image'){
        return $cek->getThumbnail('default_value');
      }
      else{
        return $cek->default_value;
      }
		}
	}
	return $default;
}

function copy_directory($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                copy_directory($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}


function rename_recursive($start_dir, $changeTo='Blank', $debug = true) {
    $str = "";
    $files = array();
    if (is_dir($start_dir)) {
        $fh = opendir($start_dir);
        while (($file = readdir($fh)) !== false) {
            // skip hidden files and dirs and recursing if necessary
            if (strpos($file, '.')=== 0) continue;

            $filepath = $start_dir . '/' . $file;
            if ( is_dir($filepath) ) {
                $newname = change_name($filepath, $changeTo);
                $str.= "From $filepath\nTo $newname\n";
                rename($filepath, $newname);
                rename_recursive($newname);
            } else {
                $newname = change_name($filepath, $changeTo);
                $str.= "From $filepath\nTo $newname\n";
                rename($filepath, $newname);
            }
        }
        closedir($fh);
    }
    if ($debug) {
        echo $str;
    }
}

function change_name( $filename , $changeTo) {
    $filename_raw = $filename;
    $special_chars = array("?", "[", "]", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}");
    $filename = str_replace($special_chars, '', $filename);
    $filename = preg_replace('/[\s-]+/', '-', $filename);
    $filename = trim($filename, '.-_');
    $filename = str_replace('Blank', $changeTo, $filename);
    return $filename;
}

function is_maintenance(){
	if(Storage::exists('SITE.MAINTENANCE')){
		return true;
	}
	return false;
}

function allImageSet($url){
	$files = [];
	$list = ['origin'];
	$list = array_merge($list, array_keys(config('image.thumbs')));
	foreach($list as $ls){
		$ts = thumbnailSet($url, $ls, false);
		if(isset($ts['normal'])){
			$files[$ls] = $ts['normal'];
		}
		if(isset($ts['webp'])){
			$files[$ls.'-webp'] = $ts['webp'];
		}
	}
	return $files;
}

function getOriginThumbnail($thumb_url, $mode='thumb'){
	$real_url = str_replace('-'.$mode, '', $thumb_url);
	//pengecekan dari thumb -> origin sebenernya banyak. kapan2 boleh ditambah kalo error

	return $real_url;
}

function thumbnailSet($url, $get='', $fallback=true){
	$extension = getExtension($url);
	$filename = str_replace('.'.$extension, '', $url);

	if($get == 'origin'){
		$grabbed_file = $filename.'.'.$extension;
		$grabbed_webp = $filename.'.webp';
	}
	else{
		$grabbed_file = $filename.'-'.$get.'.'.$extension;
		$grabbed_webp = $filename.'-'.$get.'.webp';
	}

	$out = [];
	if(ImageService::urlExists($grabbed_webp)){
		$out['webp'] = $grabbed_webp;
	}
	else{
		if(ImageService::pathExists($grabbed_webp)){
			$out['webp'] = $grabbed_webp;
		}
	}

	if(ImageService::urlExists($grabbed_file)){
		$out['normal'] = $grabbed_file;
	}
	else{
		if(ImageService::pathExists($grabbed_file)){
			$out['normal'] = $grabbed_file;
		}
		else{
			if($fallback){
				$out['normal'] = $url;
			}
		}
	}

	return $out;
}

function thumbnail($url, $get='', $fallback=true){
	$thumbs = config('image.thumbs');

	$pch = explode(".", $url);
	$extension = $pch[(count($pch)-1)];
	$filename = str_replace('.'.$extension, '', $url);

	if(ImageService::urlExists($url)){
		$out['origin'] = $url;
	}
	else{
		if(ImageService::pathExists($url)){
			$out['origin'] = $url;
		}
		else{
			$out['origin'] = false;
		}
	}

	foreach($thumbs as $alias => $size){
		$hmm = $filename . '-'.$alias.'.'.$extension;
		if(ImageService::urlExists($hmm)){
			$out[$alias] = $hmm;
		}
		else{
			if(ImageService::pathExists($hmm)){
				$out[$alias] = $hmm;
			}
			else{
				if($fallback){
					$out[$alias] = $out['origin'];
				}
				else{
					$out[$alias] = false;
				}
			}
		}
	}


	if(isset($out[$get])){
		return $out[$get];
	}
	return $out;
}


function slugify($input, $delimiter='-'){
	$string = strtolower(str_replace(' ', $delimiter, $input));
	if(strpos($string, '&') !== false){
		$string = str_replace('&', 'and', $string);
	}
	return $string;
}

function prettify($slug, $delimiter='-'){
	return str_replace($delimiter, ' ', $slug);
}


//reformat khusus utk google email
function mail_reformat($email){
	$exp = explode('@', $email);
	if(count($exp) <> 2){
		return false;
	}

	$name = $exp[0];
	$host = $exp[1];

	if(in_array($host, ['gmail.com'])){
		$name = str_replace(".", "", $name);

		$n = strpos($name, "+");
		if($n){
			$name = substr($name, 0, $n);
		}
	}
	$email = $name."@".$host;
	return $email;
}



function file_upload_max_size($mb=0) {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $post_max_size = parse_size(ini_get('post_max_size'));
    if ($post_max_size > 0) {
      $max_size = $post_max_size;
    }

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }

  if($mb > 0){
  	return min($max_size, ($mb * 1024 * 1024));
  }
  return $max_size;
}

function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}


function tagToHtml($tags, $label_class='label-default'){
	$out = explode(',', $tags);
	$html = '';
	foreach($out as $item){
		$html .= '<span class="label '.$label_class.'">'.trim($item).'</span> ';
	}
	return $html;
}


function set_lang($lang='en'){
	$default = def_lang();
  $list = LanguageInstance::available();
  if(!array_key_exists($lang, $list)){
    $lang = $default;
  }
	session()->put('lang', $lang);
}

function current_lang(){
	$lang = session('lang');
	if(empty($lang)){
		//cek header Accept-Language kalau ada
		$headers = Request::header();
		if(isset($headers['accept-language'][0])){
			$lang = strtolower($headers['accept-language'][0]);
		}
		else{
			$lang = def_lang();
		}
	}
	return $lang;
}

function available_lang($all=false){
  return LanguageInstance::available($all);
}

function def_lang(){
  $default = LanguageInstance::default();
  return $default['code'];
}

function get_lang($request, $lang=''){
	if(strlen($lang) == 0){
		$lang = def_lang();
	}

	$default = isset($request[$lang]) ? $request[$lang] : $request[def_lang()];
	return $default;
}

function elang($arr=[], $strict=false){
	$curr = current_lang();
	if(isset($arr[$curr])){
		return $arr[$curr];
	}
	if(!$strict && isset($arr[def_lang()])){
		return $arr[def_lang()];
	}
	return false;
}

function filenameOnly($fullpath=''){
	$pch = explode('/', $fullpath);
	return $pch[(count($pch)-1)];
}

function checkSmtp(){
    try{
        $transport = new Swift_SmtpTransport(config('mail.host'), config('mail.port'), config('mail.encryption'));
        $transport->setUsername(config('mail.username'));
        $transport->setPassword(config('mail.password'));
        $mailer = new Swift_Mailer($transport);
        $mailer->getTransport()->start();
        return false;
    } catch (Swift_TransportException $e) {
        return $e->getMessage();
    } catch (Exception $e) {
      return $e->getMessage();
    }	
}

function getExtension($filename){
	$pch = explode('.', $filename);
	return $pch[(count($pch)-1)];
}

function api_response($type, $message=''){
	return response()->json([
		'type' => $type,
		'message' => $message
	]);
}

function ajax_response($type, $message=''){
	return api_response($type, $message);
}

function descriptionMaker($txt, $length=30){
	$txt = strip_tags($txt);
	$pch = explode(' ', $txt);
	$out = '';
	for($i=0; $i<$length; $i++){
		if(isset($pch[$i])){
			$out .= $pch[$i].' ';
		}
	}

	if(count($pch) > $length){
		$out .= '...';
	}

	return $out;
}

function hideEmail($email, $shown_char=3){
	$pch = explode('@', $email);
	if(count($pch) == 2){
		$n = strlen($pch[0]);
		$a = substr($pch[0], 0, $shown_char);
		$sisa = $n - $shown_char;
		if($sisa > 0){
			for($i=0; $i<$sisa; $i++){
				$a.= '*';
			}
		}

		$email = $a.'@'.$pch[1];
	}
	else{
		$email = $email;
	}

	return $email;
}

function generateAdminRoute($url_name, $controller, $route_name=null){
	$bs_url = $url_name;
	$bs_route = strlen($route_name) > 0 ? $route_name : $url_name;
	$bs_controller = $controller;
	include (base_path('modules/Main/Routes/base_route.php'));
}

function filesize_formatted($path)
{
    $size = filesize($path);
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

function random_color() {
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

function broken_image(){
	return asset('admin_theme/img/broken-image.jpg');
}

function array_to_html_prop($arr=[], $ignore_key=[]){
  if(empty($arr)){
    return '';
  }
  $out = '';
  foreach($arr as $key => $value){
    if(is_array($value) || is_object($value)){
      $value = json_encode($value);
    }

    if(in_array(strtolower($key), $ignore_key)){
      continue;
    }

    $out.= $key.'="'.$value.'" ';
  }

  return $out;
}


function themeoption($key, $fallback='', $lang=null){
    $response = ThemesInstance::grab($key, $lang);

    if(is_string($response)){
        $try_decode = json_decode($response, true);
        if(isset($try_decode['id']) && isset($try_decode['thumb'])){
            $response = MediaInstance::readJson($response, false);
        }
    }

    if($response){
        return $response;
    }
    return $fallback;
}

