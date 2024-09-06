<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RegisteredUserController extends Controller
{
    /** @var MailService */
    public MailService $mailService;

    function __construct(MailService $mailService) {
        $this->mailService = $mailService;
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->method() === 'GET') {
            return redirect()->route('home');
        }

        $request->validate([
            'organization_name' => ['string', 'max:255', 'nullable'],
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::query()
            ->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $subject = "Успешная регистрация";
        $message = "Здравствуйте! <br><br>";
        $message .= "Ваш аккаунт успешно зарегистрирован. Спасибо за использование нашего сервиса. <br><br>";
        $message .= "Всего доброго.";

        $this->mailService->sendEmail($request->input('email'), $subject, $message);

        Auth::login($user);

        return new JsonResponse([], ResponseAlias::HTTP_OK);
    }
}
