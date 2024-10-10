<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Models\ServerPayment;

class RedirectIfNotCashier
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = 'cashier')
	{
	    if (!Auth::guard($guard)->check()) {
	        return redirect('cashier/login');
	    }

        // $server_payment = ServerPayment::where('month_pay', null)->get();

        // if(sizeof($server_payment) > 0 && \Auth::user()->email != 'super_admin')
        // {
    	// 	\Auth::logout();
	    //     return redirect('pay');
        // }


	    return $next($request);
	}
}