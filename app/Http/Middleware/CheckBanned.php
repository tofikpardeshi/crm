<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\User;

class CheckBanned
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

           
              $today = Carbon::now()->format('Y-m-d'); 
              
                if(auth()->check() && Auth::user()->roles_id != 1){ 
                        $empLastWorkingDate = \DB::table('employees')->where('user_id',Auth::user()->id)->first();  
                          if(auth()->user()->login_status == 0 ){
                          
                             Auth::logout(); 
                             $request->session()->invalidate();
       
                             $request->session()->regenerateToken();
       
                             return redirect()->route('/')->with('error', 'Please Contact Admin.');

                             return $next($request);
                          }

                        // if($today > $empLastWorkingDate->leaving_date ){
                            
                        //    Auth::logout(); 
                        //    $request->session()->invalidate();
       
                        //    $request->session()->regenerateToken();
       
                        //    return redirect()->route('/')->with('error', 'Your last working day is expired, please contact Admin.');

                        //    return $next($request);
                        // }
                        // elseif(auth()->user()->login_status == 0)
                        // {
                        //    Auth::logout();
       
                        //    $request->session()->invalidate();
       
                        //    $request->session()->regenerateToken();
       
                        //    return redirect()->route('/')->with('error', 'Your Account is suspended, please contact Admin.');

                        //    return $next($request);
                        // }
                    } 

                return $next($request);
            } 

            
         
}

