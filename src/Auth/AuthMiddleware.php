<?php

namespace App\Auth;

use App\Core\Request;
use App\Core\Response;

class AuthMiddleware
{
    private TokenService $tokenService;

    public function __construct()
    {
        $this->tokenService = new TokenService();
    }

    /**
     * Validate token and return access data.
     * Shows expired page if token is invalid/used/expired.
     */
    public function handle(Request $request): array
    {
        $token = $request->get('token');

        if (!$token) {
            Response::view('forms/expired', [
                'pageTitle' => 'Link Invalido - Spazio Italia',
            ]);
            exit;
        }

        $tokenData = $this->tokenService->validateToken($token);

        if (!$tokenData) {
            Response::view('forms/expired', [
                'pageTitle' => 'Link Invalido - Spazio Italia',
            ]);
            exit;
        }

        return [
            'opportunity_id' => $tokenData['opportunity_id'],
            'token'          => $token,
            'form_type'      => $tokenData['form_type'],
        ];
    }

    public function markTokenUsed(string $token): void
    {
        $this->tokenService->markUsed($token);
    }
}