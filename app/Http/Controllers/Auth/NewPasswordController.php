<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        $passwordResetToken = PasswordResetToken::query()
            ->where('token', $request->route('token'));

        if (!$passwordResetToken->exists()) {
            Abort(404);
        }

        return Inertia::render('Auth/ResetPassword', [
            'token' => $request->route('token'),
            'email' => $passwordResetToken->first()->email,
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $passwordResetToken = PasswordResetToken::query()
            ->where('token', $request->input('token'));

        if (!$passwordResetToken->exists()) {
            return new JsonResponse([
                "message" => "Указанного токена нет в базе.",
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $user = User::query()
            ->where('email', $passwordResetToken->first()->email)
            ->first();

        if ($user === null) {
            return new JsonResponse([
                "message" => "Указанного пользователя нет в базе.",
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        $passwordResetToken->delete();

        event(new PasswordReset($user));

        return new JsonResponse([
            "message" => "Пароль успешно изменен.",
        ], ResponseAlias::HTTP_OK);
    }
}
