<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Services\MailService;
use App\Services\UniqueCodeService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PasswordResetLinkController extends Controller
{
    /** @var MailService */
    public MailService $mailService;

    function __construct(MailService $mailService) {
        $this->mailService = $mailService;
    }

    public function sendPasswordRestoreEmail(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->method() === 'GET') {
            return redirect()->route('home');
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::query()
            ->where("email", $request->input("email"))
            ->first();

        if ($user === null) {
            return new JsonResponse([
                "message" => "Указанного адреса нет в базе.",
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $passwordResetToken = PasswordResetToken::query()
            ->where('email', $request->input("email"));

        if ($passwordResetToken->exists()) {
            $passwordResetToken->delete();
        }

        $token = App(UniqueCodeService::class)->getUniqueCode();

        PasswordResetToken::query()
            ->insert([
                'email' => $request->input('email'),
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);

        $restorePasswordLink = "<a href='".route('password.reset', $token)."' target='_blank'>".route('password.reset', $token)."</a>";

        $subject = "Восстановление пароля";
        $message = "Здравствуйте! <br><br>";
        $message .= "Ссылка для восстановления пароля ".$restorePasswordLink."<br><br>";
        $message .= "Всего доброго.";

        $this->mailService->sendEmail($request->input('email'), $subject, $message);

        return new JsonResponse([
            "message" => "По указанному адресу отправлено письмо с ссылкой восстановления пароля.",
        ], ResponseAlias::HTTP_OK);
    }
}
