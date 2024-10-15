<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LockScreen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //   $max = 0.1*60;

        //  if(!session()->has('last_request') || $max > (time()-session('last_request')))
        //  {
        //      session()->put('last_request',time());  
        //  }
        
        //  if($max < (time() - session('last_request')))
        //  { 
        //  session()->put('locked',1); 
        //  return  redirect()->route('lock-screen');
        //  }
        //  //dd(session('last_request'));
        //      return $next($request); 
            
         if ($request->session()->has('locked')) {
                 
             return redirect()->route('lock-screen');
                    
             } 
             return $next($request);
        
        }
    }

