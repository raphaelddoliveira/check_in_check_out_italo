<?php

namespace App\Models;

use App\Core\Database;

class FormSubmission
{
    public static function create(array $data): int
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            'INSERT INTO form_submissions (opportunity_id, form_type, submitted_by, ip_address, pdf_filename)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['opportunity_id'],
            $data['form_type'],
            $data['submitted_by'] ?? null,
            $data['ip_address'] ?? null,
            $data['pdf_filename'] ?? null,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function findByOpportunity(string $opportunityId): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            'SELECT * FROM form_submissions WHERE opportunity_id = ? ORDER BY submitted_at DESC'
        );
        $stmt->execute([$opportunityId]);

        return $stmt->fetchAll();
    }

    public static function findAll(int $limit = 100): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            'SELECT * FROM form_submissions ORDER BY submitted_at DESC LIMIT ?'
        );
        $stmt->execute([$limit]);

        return $stmt->fetchAll();
    }
}
