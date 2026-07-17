<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function home()
    {
        if (Auth::check()) {
            return redirect('/admin/ylp');
        }

        return view('pages.home');
    }
}
