<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Auth\TokenService;

class ApiController
{
    public function home(Request $request): void
    {
        Response::view('forms/expired', [
            'pageTitle' => 'Spazio Italia',
        ]);
    }

    /**
     * API endpoint for N8N to generate form tokens.
     * Protected by API key in X-Api-Key header.
     *
     * POST /api/generate-token
     * Body: { "opportunity_id": "...", "form_type": "checkin"|"checkout" }
     * Header: X-Api-Key: <same API key from .env>
     */
    public function generateToken(Request $request): void
    {
        header('Content-Type: application/json');

        // Validate API key
        $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
        if (empty($apiKey) || $apiKey !== config('espocrm.api_key')) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        // Parse JSON body
        $input = json_decode(file_get_contents('php://input'), true);
        $opportunityId = $input['opportunity_id'] ?? '';
        $formType = $input['form_type'] ?? '';

        if (empty($opportunityId) || !in_array($formType, ['checkin', 'checkout'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing opportunity_id or invalid form_type (checkin/checkout)']);
            exit;
        }

        $tokenService = new TokenService();
        $token = $tokenService->generateToken($opportunityId, $formType);

        $appUrl = rtrim(config('app.url', ''), '/');
        $link = "{$appUrl}/{$formType}?token={$token}";

        echo json_encode([
            'success' => true,
            'token'   => $token,
            'link'    => $link,
            'expires_in_hours' => config('token.expiry_hours', 72),
        ]);
        exit;
    }
}