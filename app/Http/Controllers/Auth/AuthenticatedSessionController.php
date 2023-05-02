<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {  
        $dateOfToday=Carbon::now()->format('F d, Y');
        $currentDate=Carbon::now();
        if($currentDate->month>=11 && $currentDate->day>=1){
            $academicYear=$currentDate->year.'/'.($currentDate->year+1);
            return view('login',['academicYear'=>$academicYear,'dateOfToday'=> $dateOfToday]);
        }
        $academicYear=($currentDate->year-1).'/'.($currentDate->year);
        return view('login',['academicYear'=>$academicYear,'dateOfToday'=> $dateOfToday]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
