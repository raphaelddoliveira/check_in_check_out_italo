<?php

namespace App\Core;

class CsrfProtection
{
    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        Session::set('_csrf_token', $token);
        return $token;
    }

    public static function getToken(): string
    {
        $token = Session::get('_csrf_token');
        if (!$token) {
            $token = self::generateToken();
        }
        return $token;
    }

    public static function getTokenField(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="_csrf_token" value="' . $token . '">';
    }

    public static function validate(Request $request): bool
    {
        $submittedToken = $request->post('_csrf_token', '');
        $sessionToken = Session::get('_csrf_token', '');

        if (empty($submittedToken) || empty($sessionToken)) {
            return false;
        }

        return hash_equals($sessionToken, $submittedToken);
    }
}
