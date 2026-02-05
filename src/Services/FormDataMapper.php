<?php

namespace App\Services;

class FormDataMapper
{
    /**
     * Build the acceptance text (saved to cAceiteCheckIn / cAceiteCheckOut).
     */
    public static function buildAceiteText(string $formType, array $postData, array $opportunity): string
    {
        $type = $formType === 'checkin' ? 'Check-in' : 'Check-out';
        $data = $postData['data'] ?? date('Y-m-d');
        $horario = $postData['horario'] ?? date('H:i');

        return "Aceito - {$type}\n"
             . "Data: {$data}\n"
             . "Horario: {$horario}\n"
             . "Registrado em: " . date('d/m/Y H:i:s');
    }

    public static function getCheckInItems(): array
    {
        return [
            'limpeza' => [
                'label'   => 'Limpeza geral',
                'options' => ['OK', 'Precisa de atenção'],
            ],
            'iluminacao' => [
                'label'   => 'Iluminação e som',
                'options' => ['OK', 'Problema'],
            ],
            'equipamentos' => [
                'label'   => 'Equipamentos audiovisuais',
                'options' => ['OK', 'Problema'],
            ],
            'mobiliario' => [
                'label'   => 'Mobiliário (cadeiras, mesas, púlpito etc.)',
                'options' => ['OK', 'Faltando'],
            ],
            'climatizacao' => [
                'label'   => 'Climatização',
                'options' => ['OK', 'Problema'],
            ],
            'banheiros' => [
                'label'   => 'Banheiros',
                'options' => ['OK', 'Precisa de limpeza'],
            ],
            'seguranca' => [
                'label'   => 'Segurança / Acesso',
                'options' => ['OK', 'Problema'],
            ],
        ];
    }

    public static function getCheckOutItems(): array
    {
        return [
            'limpeza' => [
                'label'   => 'Limpeza geral',
                'options' => ['OK', 'Danificado', 'Sujo'],
            ],
            'equipamentos' => [
                'label'   => 'Equipamentos audiovisuais',
                'options' => ['OK', 'Danificado'],
            ],
            'mobiliario' => [
                'label'   => 'Mobiliário',
                'options' => ['OK', 'Danificado', 'Faltando'],
            ],
            'climatizacao' => [
                'label'   => 'Climatização',
                'options' => ['OK', 'Problema'],
            ],
            'banheiros' => [
                'label'   => 'Banheiros',
                'options' => ['OK', 'Sujos'],
            ],
            'seguranca' => [
                'label'   => 'Segurança / Acesso',
                'options' => ['OK', 'Problema'],
            ],
        ];
    }
}