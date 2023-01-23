<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequestRequest;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use Inertia\Inertia;

class RequestController extends Controller
{
    public function show()
    {
        return view('web.request');
    }

    public function store(StoreRequestRequest $request)
    {
        RequestModel::create($request->validated());
//            ->addMediaFromRequest('death_certificate')
//            ->toMediaCollection();

        // send email
        // upload to s3
        //
        return back()->with('request-sent', true);
    }
}
