<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;
use Auth;

class AutoLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $lastActivity = session('last_activity', 0);
        $sessionLifetime = config('session.lifetime') * 60;

        if (Auth::check() && time() - $lastActivity > $sessionLifetime) {

           
            $userId = Auth::user()->id; // Store the user ID before logout
            $AuthUserSessionToken = Auth::user()->session_token;
             
            // Update the updated_at column in the log table using raw SQL query
            DB::table('log')
                ->where('user_id', $userId)
                ->where('session_token',$AuthUserSessionToken) 
                ->update([
                    'action' => 'logout',
                    'updated_at' => now()
                ]);

            Auth::logout();

            // Clear all session data
            session()->flush();

            
            // Redirect to login page
             return redirect(route('login'))->with('message', 'Your session has expired. Please log in again.');
        }

        // Update last activity time
        session(['last_activity' => time()]);

        return $next($request);
    }

}
