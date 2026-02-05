<?php

namespace App\Services;

use Espo\ApiClient\Client;

class EspoCrmClient
{
    private Client $client;

    public function __construct()
    {
        $url = config('espocrm.url');
        $this->client = new Client($url);
        $this->client->setApiKey(config('espocrm.api_key'));

        $secretKey = config('espocrm.secret_key');
        if (!empty($secretKey)) {
            $this->client->setSecretKey($secretKey);
        }
    }

    public function get(string $endpoint, array $params = []): object
    {
        $response = $this->client->request('GET', $endpoint, $params ?: null);
        return $response->getParsedBody();
    }

    public function post(string $endpoint, array $data): object
    {
        $response = $this->client->request('POST', $endpoint, $data);
        return $response->getParsedBody();
    }

    public function put(string $endpoint, array $data): object
    {
        $response = $this->client->request('PUT', $endpoint, $data);
        return $response->getParsedBody();
    }

    public function getOpportunity(string $id): object
    {
        return $this->get("Opportunity/{$id}");
    }

    public function updateOpportunity(string $id, array $data): object
    {
        return $this->put("Opportunity/{$id}", $data);
    }

    public function listOpportunities(array $params = []): object
    {
        return $this->get('Opportunity', $params);
    }

    /**
     * Authenticate user via EspoCRM credentials (Basic Auth).
     * Returns user data on success, null on failure.
     */
    public function authenticateUser(string $username, string $password): ?object
    {
        try {
            $authClient = new Client(config('espocrm.url'));
            $authClient->setUsernameAndPassword($username, $password);

            $response = $authClient->request('GET', 'App/user');
            $result = $response->getParsedBody();

            if (isset($result->user)) {
                return $result->user;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
