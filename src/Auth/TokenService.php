<?php

namespace App\Auth;

use App\Core\Database;

class TokenService
{
    public function generateToken(string $opportunityId, string $formType): string
    {
        $token = bin2hex(random_bytes(32));
        $expiryHours = config('token.expiry_hours', 72);
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expiryHours} hours"));

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            'INSERT INTO tokens (token, opportunity_id, form_type, expires_at) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$token, $opportunityId, $formType, $expiresAt]);

        return $token;
    }

    public function validateToken(string $token): ?array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            'SELECT * FROM tokens WHERE token = ? LIMIT 1'
        );
        $stmt->execute([$token]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        if ($row['used']) {
            return null;
        }

        if (strtotime($row['expires_at']) < time()) {
            return null;
        }

        return $row;
    }

    public function markUsed(string $token): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('UPDATE tokens SET used = 1 WHERE token = ?');
        $stmt->execute([$token]);
    }

    public function getOpportunityIdByToken(string $token): ?string
    {
        $data = $this->validateToken($token);
        return $data['opportunity_id'] ?? null;
    }

    public function getFormTypeByToken(string $token): ?string
    {
        $data = $this->validateToken($token);
        return $data['form_type'] ?? null;
    }
}
