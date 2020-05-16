<?php
function admin_guard_name(){
  return config('cms.admin.auth_guard_name', 'web');
}

function admin_guard(){
  return Auth::guard(admin_guard_name());
}

function is_admin_login(){
  return admin_guard()->check();
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
  $current_user = admin_guard()->user();
  $current_roles = $current_user->role_id;
  $role = app('role')->where('id', $current_roles)->first();
  $sa = $role->is_sa ?? false;
  return (bool)$sa;
}

function has_access($route_name=''){
  if(!is_admin_login()){
    return false;
  }

  //auth bawaan laravel ga ada relasi ke roles, jadi harus dibuat sendiri
  $user = admin_guard()->user();
  $role = $user->role_id;
  $roles_data = app('role')->where('id', $role)->first();
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