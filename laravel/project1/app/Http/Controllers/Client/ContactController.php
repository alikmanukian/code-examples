<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactsRequest;
use App\Mail\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return view('web.contacts');
    }

    public function store(StoreContactsRequest $request)
    {
        $data = $request->validated();

        Mail::to(config('app.support_email'))
            ->send(new ContactForm($data));



        return back()->with('message-sent', true);
    }
}
