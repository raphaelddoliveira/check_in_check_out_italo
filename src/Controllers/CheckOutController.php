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

        $formAction = '/checkout?token=' . urlencode($access['token']);

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
            Response::redirect('/checkout?token=' . urlencode($access['token']));
            return;
        }

        $postData = $request->all();

        try {
            $opportunity = $this->opportunityService->getOpportunityDetails($oppId);

            // Build acceptance text and save to EspoCRM
            $aceiteText = FormDataMapper::buildAceiteText('checkout', $postData, $opportunity);
            $this->opportunityService->saveAceite($oppId, 'cAceiteCheckOut', $aceiteText);

            // Generate and attach PDF
            $pdfService = new PdfService();
            $pdfContent = $pdfService->generateCheckOutPdf($postData, $opportunity);

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
            Response::redirect('/checkout?token=' . urlencode($access['token']));
        }
    }
}