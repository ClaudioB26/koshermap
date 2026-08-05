<?php

namespace App\Http\Controllers;

use App\Models\Certifier;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'message'           => 'required|string|max:2000',
            'accepted_privacy'  => 'accepted',
        ]);

        ContactMessage::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'message'          => $request->message,
            'accepted_privacy' => true,
        ]);

        return back()->with('contact_sent', true);
    }

    public function certifierContact(Request $request, string $slug)
    {
        $certifier = Certifier::where('slug', $slug)->firstOrFail();
        $intent = $request->query('intent') === 'certify' ? 'certify' : 'general';

        return view('catalog.certifiers.contacto', compact('certifier', 'intent'));
    }
}
