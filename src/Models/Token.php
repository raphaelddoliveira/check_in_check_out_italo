<?php

namespace App\Models;

use App\Core\Database;

class Token
{
    public static function create(array $data): int
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            'INSERT INTO tokens (token, opportunity_id, form_type, expires_at) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['token'],
            $data['opportunity_id'],
            $data['form_type'],
            $data['expires_at'],
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function findByToken(string $token): ?array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM tokens WHERE token = ? LIMIT 1');
        $stmt->execute([$token]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public static function markUsed(string $token): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('UPDATE tokens SET used = 1 WHERE token = ?');
        $stmt->execute([$token]);
    }

    public static function findByOpportunity(string $opportunityId): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            'SELECT * FROM tokens WHERE opportunity_id = ? ORDER BY created_at DESC'
        );
        $stmt->execute([$opportunityId]);

        return $stmt->fetchAll();
    }
}
