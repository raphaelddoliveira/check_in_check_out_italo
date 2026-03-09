<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private Dompdf $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $this->dompdf = new Dompdf($options);
    }

    public function generateCheckInPdf(array $formData, array $opportunityInfo, array $fotos = []): string
    {
        $items = FormDataMapper::getCheckInItems();
        $html = $this->renderTemplate('pdf/checkin_pdf', [
            'formData'    => $formData,
            'opportunity' => $opportunityInfo,
            'items'       => $items,
            'fotos'       => $fotos,
            'submittedAt' => date('d/m/Y H:i:s'),
        ]);

        return $this->generatePdf($html);
    }

    public function generateCheckOutPdf(array $formData, array $opportunityInfo, array $fotos = []): string
    {
        $items = FormDataMapper::getCheckOutItems();
        $html = $this->renderTemplate('pdf/checkout_pdf', [
            'formData'    => $formData,
            'opportunity' => $opportunityInfo,
            'items'       => $items,
            'fotos'       => $fotos,
            'submittedAt' => date('d/m/Y H:i:s'),
        ]);

        return $this->generatePdf($html);
    }

    private function generatePdf(string $html): string
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        return $this->dompdf->output();
    }

    private function renderTemplate(string $templateName, array $data): string
    {
        extract($data);

        ob_start();
        $templatePath = dirname(__DIR__, 2) . '/templates/' . $templateName . '.php';

        if (!file_exists($templatePath)) {
            ob_end_clean();
            throw new \RuntimeException("Template PDF nao encontrado: {$templateName}");
        }

        require $templatePath;
        return ob_get_clean();
    }
}
