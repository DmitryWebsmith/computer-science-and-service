<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Mail;

final class MailService
{
    public function send(array $data, $htmlTemplate, $textTemplate = null)
    {
        $template = $htmlTemplate ? [$htmlTemplate, $textTemplate] : ['text' => $textTemplate];
        try {
            Mail::send($template, $data, function ($message) use ($data) {
                if (is_array($data['to'])) {
                    foreach ($data['to'] as $key => $value) {
                        $message->to($value)->subject($data['subject']);
                    }
                } else {
                    $message->to($data['to'])->subject($data['subject']);
                }
            });
        } catch (\Exception $e) {
            logger("MailService | send", [$e]);
        }

    }

    public function sendEmail($to, $subject, $message)
    {
        $data = [
            'to' => [$to],
            'subject' => $subject,
            'content' => $message,
        ];

        $this->send($data, 'emails.sendmail');
    }
}
