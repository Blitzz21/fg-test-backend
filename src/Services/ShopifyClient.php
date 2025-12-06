<?php
// src/Services/ShopifyClient.php
namespace App\Services;

class ShopifyClient
{
    private string $domain;
    private string $token;
    private string $apiVersion;

    public function __construct(array $config)
    {
        $this->domain     = $config['shopify']['domain'];
        $this->token      = $config['shopify']['storefrontToken'];
        $this->apiVersion = $config['shopify']['apiVersion'];
    }

    public function query(string $query, array $variables = []): array
    {
        $url = "https://{$this->domain}/api/{$this->apiVersion}/graphql.json";

        $payload = json_encode([
            'query'     => $query,
            'variables' => $variables,
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                "X-Shopify-Storefront-Access-Token: {$this->token}",
            ],
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new \RuntimeException('Shopify request failed: ' . curl_error($ch));
        }

        $data = json_decode($response, true);
        curl_close($ch);

        if (isset($data['errors'])) {
            throw new \RuntimeException('Shopify GraphQL errors: ' . json_encode($data['errors']));
        }

        return $data['data'] ?? [];
    }
}
