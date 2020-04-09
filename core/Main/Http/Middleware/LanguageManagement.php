<?php
namespace Core\Main\Http\Middleware;

use Closure;
use LanguageInstance;

class LanguageManagement
{
    public function handle($request, Closure $next)
    {   
        $lang = $request->lang;
        if(!empty($lang)){
            //set current session language to $lang
            $this->setLang($lang);
            return redirect($request->url());
        }
        return $next($request);
    }

    public function setLang($lang){
        $lang = strtolower($lang);
        $available = LanguageInstance::available(true);
        if(!isset($available[$lang])){
            $lang = def_lang();
        }

        session([
            'lang' => $lang
        ]);
    }
}