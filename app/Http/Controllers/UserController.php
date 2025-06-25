<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return api_success('Get user profile successully', new UserResource($request->user()));
    }
}
