<?php

namespace TaskFlow\Core;

class Csrf
{
    public static function generate(string $formKey): string
    {
        if (!isset($_SESSION['_csrf_token']) || !is_array($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = [];
        }

        if (empty($_SESSION['_csrf_token'][$formKey])) {
            $_SESSION['_csrf_token'][$formKey] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'][$formKey];
    }

    public static function verify(string $formKey, ?string $token): bool
    {
        if (
            !isset($_SESSION['_csrf_token']) ||
            !is_array($_SESSION['_csrf_token']) ||
            empty($_SESSION['_csrf_token'][$formKey]) ||
            empty($token)
        ) {
            return false;
        }

        $isValid = hash_equals($_SESSION['_csrf_token'][$formKey], $token);

        // Rotate token after verification
        unset($_SESSION['_csrf_token'][$formKey]);

        return $isValid;
    }
}
