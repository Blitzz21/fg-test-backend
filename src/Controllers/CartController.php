<?php
// src/Controllers/CartController.php

namespace App\Controllers;

use App\Core\Response;
use App\Services\ShopifyClient;

class CartController
{
    private ShopifyClient $shopify;

    public function __construct()
    {
        $config        = require __DIR__ . '/../Config/config.php';
        $this->shopify = new ShopifyClient($config);
    }

    /**
     * POST /api/cart/lines
     * Body JSON:
     * {
     *   "variantId": "...",
     *   "quantity": 12,
     *   "custom": {
     *     "productId": "...",
     *     "productTitle": "...",
     *     "color": "Cherry Red",
     *     "size": "L",
     *     "artworkFileName": "logo.png"
     *   }
     * }
     */
    public function addLine(): void
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            Response::json(['error' => 'Invalid JSON'], 400);
        }

        $variantId = $data['variantId'] ?? null;
        $quantity  = (int)($data['quantity'] ?? 0);
        $custom    = $data['custom'] ?? [];

        if (!$variantId || $quantity < 1) {
            Response::json(['error' => 'variantId and quantity are required'], 400);
        }

        // Build Shopify line attributes from custom data
        $attributes = [];

        $map = [
            'productId'       => 'Product ID',
            'productTitle'    => 'Product',
            'color'           => 'Color',
            'size'            => 'Size',
            'artworkFileName' => 'Artwork File',
            'artworkStoredFileName'=> 'Artwork Stored Name',
            'artworkUrl'      => 'Artwork URL',
            'notes'           => 'Notes',
        ];

        foreach ($map as $key => $label) {
            if (!empty($custom[$key])) {
                $attributes[] = [
                    'key'   => $label,
                    'value' => (string)$custom[$key],
                ];
            }
        }

        // Shopify cartCreate mutation
        $mutation = <<<'GRAPHQL'
        mutation CartCreate($input: CartInput!) {
          cartCreate(input: $input) {
            cart {
              id
              checkoutUrl
              totalQuantity
            }
            userErrors {
              field
              message
            }
          }
        }
        GRAPHQL;

        $variables = [
            'input' => [
                'lines' => [
                    [
                        'quantity'    => $quantity,
                        'merchandiseId' => $variantId,
                        'attributes'  => $attributes,
                    ],
                ],
            ],
        ];

        try {
            $result = $this->shopify->query($mutation, $variables);
        } catch (\Throwable $e) {
            Response::json(['error' => 'Shopify request failed', 'details' => $e->getMessage()], 500);
        }

        $cartCreate = $result['cartCreate'] ?? null;
        if (!$cartCreate) {
            Response::json(['error' => 'Unexpected Shopify response', 'raw' => $result], 500);
        }

        $userErrors = $cartCreate['userErrors'] ?? [];
        if (!empty($userErrors)) {
            Response::json(['error' => 'Shopify user errors', 'userErrors' => $userErrors], 400);
        }

        $cart = $cartCreate['cart'] ?? null;
        if (!$cart || empty($cart['checkoutUrl'])) {
            Response::json(['error' => 'Cart not created', 'raw' => $cartCreate], 500);
        }

        Response::json([
            'cartId'      => $cart['id'],
            'checkoutUrl' => $cart['checkoutUrl'],
            'totalQty'    => $cart['totalQuantity'],
        ]);
    }
}
