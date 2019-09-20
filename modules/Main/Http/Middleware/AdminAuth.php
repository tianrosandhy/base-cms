<?php

namespace Module\Main\Http\Middleware;

use Closure;
use Auth;
use Redirect;


class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if($request->segment(1) == "login" || $request->segment(2) == "login")
            return $next($request);

        if($user){
            $role = $user->roles;
            if(!empty($role)){
                $roles_bawahan = [];
                foreach($role->children as $child){
                    $roles_bawahan[] = $child->id;
                    if($child->children){
                        foreach($child->children as $subchild){
                            $roles_bawahan[] = $subchild->id;
                            if($subchild->children){
                                foreach($subchild->children as $lastchild){
                                    $roles_bawahan[] = $lastchild;
                                }
                            }
                        }
                    }
                }

                if($role->is_sa){
                    //semuanya accessible
                    $accessible_role = app(config('model.role'))->pluck('id')->toArray();
                }
                else{
                    $accessible_role = array_merge([$role->id], $roles_bawahan);
                }

                //add request attribute
                $request->attributes->add([
                    'user' => $user,
                    'role' => $role,
                    'current_role' => $role->id,
                    'is_sa' => $role->is_sa,
                    'base_permission' => json_decode($role->priviledge_list, true),
                    'subordinate_role' => $roles_bawahan,
                    'accessible_role' => $accessible_role,
                ]);

                return $next($request);
            }
        }

        return Redirect()->intended($request->segment(1)."/login");
    }
}
