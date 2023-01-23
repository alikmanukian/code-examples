<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Users/Index', [
            'data' => UserResource::collection(
                User::query()
                    ->paginate(20)
                    ->onEachSide(1)
                    ->withQueryString()
            )
        ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('users');
    }
}
