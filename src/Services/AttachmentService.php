<?php

namespace App\Services;

class AttachmentService
{
    private EspoCrmClient $client;

    public function __construct()
    {
        $this->client = new EspoCrmClient();
    }

    /**
     * Attach a PDF to a specific file field on an Opportunity.
     * Fields: cContratoCheckIn or cContratoCheckOut
     */
    public function attachToField(string $opportunityId, string $field, string $pdfContent, string $filename): void
    {
        try {
            // Create attachment in EspoCRM
            $attachment = $this->client->post('Attachment', [
                'name'        => $filename,
                'type'        => 'application/pdf',
                'role'        => 'Attachment',
                'relatedType' => 'Opportunity',
                'relatedId'   => $opportunityId,
                'field'       => $field,
                'file'        => 'data:application/pdf;base64,' . base64_encode($pdfContent),
            ]);

            // Link the attachment to the field on the opportunity
            $fieldId = $field . 'Id';
            $fieldName = $field . 'Name';
            $this->client->updateOpportunity($opportunityId, [
                $fieldId   => $attachment->id,
                $fieldName => $filename,
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('Erro ao anexar PDF: ' . $e->getMessage());
        }
    }
}