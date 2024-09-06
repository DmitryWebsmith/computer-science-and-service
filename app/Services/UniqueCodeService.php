<?php

namespace App\Services;

class UniqueCodeService
{
    /**
     * @return string
     * @throws \Random\RandomException
     */
    public function getUniqueCode(): string
    {
        $length = 13;
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return strtoupper(substr(bin2hex($bytes), 0, $length));
    }
}
