<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function places(Request $request)
    {
        $places = $request->user()->places()->with('city.country', 'certifier')->latest()->get();

        return view('account.places', compact('places'));
    }
}
