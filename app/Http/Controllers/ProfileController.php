<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProfileController extends Controller
{
    public function edit(): Response
    {
        $user = User::query()->find(Auth::id());

        $operatorData = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        return Inertia::render('Dashboard/Profile/Edit', ['operatorData' => $operatorData]);
    }

    public function updateData(ProfileUpdateRequest $request): JsonResponse
    {
        User::query()
            ->where('id', Auth::id())
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

        return new JsonResponse([], ResponseAlias::HTTP_OK);
    }
}
