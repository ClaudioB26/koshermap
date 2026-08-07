<?php

namespace App\Http\Controllers;

use App\Models\Certifier;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function places(Request $request)
    {
        $places = $request->user()->places()->with('city.country', 'certifier')->latest()->get();

        return view('account.places', compact('places'));
    }

    public function certifier(Request $request)
    {
        $certifier = Certifier::where('owner_id', $request->user()->id)->first();

        return view('account.certifier', compact('certifier'));
    }
}
