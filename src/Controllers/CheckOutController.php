<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\CsrfProtection;
use App\Auth\AuthMiddleware;
use App\Services\OpportunityService;
use App\Services\FormDataMapper;
use App\Services\PdfService;
use App\Services\AttachmentService;
use App\Models\FormSubmission;

class CheckOutController
{
    private AuthMiddleware $authMiddleware;
    private OpportunityService $opportunityService;

    public function __construct()
    {
        $this->authMiddleware = new AuthMiddleware();
        $this->opportunityService = new OpportunityService();
    }

    private function processUploadedPhotos(): array
    {
        $fotos = [];
        foreach ($_FILES as $key => $file) {
            if (!str_ends_with($key, '_foto') || $file['error'] !== UPLOAD_ERR_OK) continue;
            if ($file['size'] > 5 * 1024 * 1024) continue;
            $mime = mime_content_type($file['tmp_name']);
            if (!str_starts_with($mime, 'image/')) continue;
            $fieldName = str_replace('_foto', '', $key);
            $fotos[$fieldName] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($file['tmp_name']));
        }
        return $fotos;
    }

    public function show(Request $request): void
    {
        $access = $this->authMiddleware->handle($request);
        $oppId = $access['opportunity_id'];

        try {
            $opportunity = $this->opportunityService->getOpportunityDetails($oppId);

            if ($opportunity['checkoutCompleted']) {
                Response::view('forms/success', [
                    'pageTitle'   => 'Check-out Ja Realizado - Spazio Italia',
                    'formType'    => 'Check-out',
                    'opportunity' => $opportunity,
                    'alreadyDone' => true,
                ]);
                return;
            }
        } catch (\Exception $e) {
            Response::view('forms/expired', [
                'pageTitle' => 'Erro - Spazio Italia',
            ]);
            return;
        }

        $basePath = config('app.base_path', '');
        $formAction = $basePath . '/checkout?token=' . urlencode($access['token']);

        Response::view('forms/checkout', [
            'pageTitle'   => 'Check-out - ' . $opportunity['name'],
            'opportunity' => $opportunity,
            'formAction'  => $formAction,
            'csrfField'   => CsrfProtection::getTokenField(),
        ]);
    }

    public function submit(Request $request): void
    {
        $access = $this->authMiddleware->handle($request);
        $oppId = $access['opportunity_id'];

        if (!CsrfProtection::validate($request)) {
            Session::flash('error', 'Token de seguranca invalido. Tente novamente.');
            Response::redirect(config('app.base_path', '') . '/checkout?token=' . urlencode($access['token']));
            return;
        }

        $postData = $request->all();
        $fotos = $this->processUploadedPhotos();

        try {
            $opportunity = $this->opportunityService->getOpportunityDetails($oppId);

            // Build acceptance text and save to EspoCRM
            $aceiteText = FormDataMapper::buildAceiteText('checkout', $postData, $opportunity);
            $this->opportunityService->saveAceite($oppId, 'cAceiteCheckOut', $aceiteText);

            // Generate and attach PDF
            $pdfService = new PdfService();
            $pdfContent = $pdfService->generateCheckOutPdf($postData, $opportunity, $fotos);

            $filename = 'checkout_' . date('Y-m-d_His') . '.pdf';
            $attachmentService = new AttachmentService();
            $attachmentService->attachToField($oppId, 'cContratoCheckOut', $pdfContent, $filename);

            // Mark token as used
            $this->authMiddleware->markTokenUsed($access['token']);

            // Log submission
            FormSubmission::create([
                'opportunity_id' => $oppId,
                'form_type'      => 'checkout',
                'submitted_by'   => 'Cliente (token)',
                'ip_address'     => $request->ip(),
                'pdf_filename'   => $filename,
            ]);

            Response::view('forms/success', [
                'pageTitle'   => 'Check-out Concluido - Spazio Italia',
                'formType'    => 'Check-out',
                'opportunity' => $opportunity,
                'alreadyDone' => false,
            ]);
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao processar check-out: ' . $e->getMessage());
            Response::redirect(config('app.base_path', '') . '/checkout?token=' . urlencode($access['token']));
        }
    }
}