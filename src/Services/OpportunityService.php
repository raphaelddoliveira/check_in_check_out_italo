<?php

namespace App\Services;

class OpportunityService
{
    private EspoCrmClient $client;

    public function __construct()
    {
        $this->client = new EspoCrmClient();
    }

    public function getOpportunityDetails(string $id): array
    {
        try {
            $opp = $this->client->getOpportunity($id);

            return [
                'id'                => $opp->id ?? $id,
                'name'              => $opp->name ?? 'Sem nome',
                'accountName'       => $opp->accountName ?? '',
                'closeDate'         => $opp->closeDate ?? '',
                'stage'             => $opp->stage ?? '',
                'checkinCompleted'  => !empty($opp->cAceiteCheckIn ?? ''),
                'checkoutCompleted' => !empty($opp->cAceiteCheckOut ?? ''),
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException('Erro ao buscar oportunidade: ' . $e->getMessage());
        }
    }

    public function saveAceite(string $id, string $field, string $text): void
    {
        try {
            $this->client->updateOpportunity($id, [$field => $text]);
        } catch (\Exception $e) {
            throw new \RuntimeException('Erro ao salvar aceite: ' . $e->getMessage());
        }
    }
}