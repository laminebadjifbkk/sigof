<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function profile()
    {
        return response()->json(auth()->user());
    }

    public function list()
    {
        return response()->json(User::all());
    }
}
