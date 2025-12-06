<?php
// src/Controllers/DesignsController.php

namespace App\Controllers;

use App\Core\Response;
use App\Database\Connection;
use PDOException;

class DesignsController
{
    public function store(): void
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            Response::json(['error' => 'Invalid JSON body'], 400);
        }

        // Basic required fields
        $required = [
            'productId',
            'variantId',
            'quantity',
            'artworkFile',
            'artworkUrl',
            'cartId',
            'checkoutUrl',
        ];

        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                Response::json(['error' => "Missing field: {$field}"], 400);
            }
        }

        $productId   = $data['productId'];
        $variantId   = $data['variantId'];
        $color       = $data['color'] ?? null;
        $size        = $data['size'] ?? null;
        $quantity    = (int) ($data['quantity'] ?? 1);
        $artworkFile = $data['artworkFile'];
        $artworkUrl  = $data['artworkUrl'];
        $cartId      = $data['cartId'];
        $checkoutUrl = $data['checkoutUrl'];
        $status      = $data['status'] ?? 'pending';

        try {
            $pdo = Connection::get();

            $stmt = $pdo->prepare(
                "INSERT INTO designs
                 (product_id, variant_id, color, size, quantity,
                  artwork_file, artwork_url, cart_id, checkout_url, status)
                 VALUES
                 (:product_id, :variant_id, :color, :size, :quantity,
                  :artwork_file, :artwork_url, :cart_id, :checkout_url, :status)"
            );

            $stmt->execute([
                ':product_id'   => $productId,
                ':variant_id'   => $variantId,
                ':color'        => $color,
                ':size'         => $size,
                ':quantity'     => $quantity,
                ':artwork_file' => $artworkFile,
                ':artwork_url'  => $artworkUrl,
                ':cart_id'      => $cartId,
                ':checkout_url' => $checkoutUrl,
                ':status'       => $status,
            ]);

            $id = (int) $pdo->lastInsertId();

            Response::json([
                'success' => true,
                'id'      => $id,
            ]);
        } catch (PDOException $e) {
            // Log $e->getMessage() in real app
            Response::json(['error' => 'Failed to save design'], 500);
        }
    }

public function index(): void
{
    $status  = $_GET['status'] ?? null;
    $search  = $_GET['search'] ?? null;
    $sort    = $_GET['sort'] ?? 'created_desc';
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = max(1, min(100, (int)($_GET['perPage'] ?? 20)));
    $offset  = ($page - 1) * $perPage;

    try {
        $pdo = Connection::get();

        // Where clause (status + search)
        $where  = [];
        $params = [];

        if ($status && in_array($status, ['pending', 'printing', 'completed'], true)) {
            $where[] = "status = :status";
            $params[':status'] = $status;
        }

        if ($search) {
            $where[] = "(product_id LIKE :search OR variant_id LIKE :search OR color LIKE :search OR size LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

        // Sorting
        $orderBy = $sort === 'created_asc'
            ? 'created_at ASC'
            : 'created_at DESC';

        // Count total rows
        $countSql = "SELECT COUNT(*) FROM designs $whereSql";
        $countStmt = $pdo->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();

        // Fetch page of results
        $sql = "
            SELECT id, product_id, variant_id, color, size, quantity,
                   artwork_file, artwork_url, cart_id, checkout_url,
                   status, created_at
            FROM designs
            $whereSql
            ORDER BY $orderBy
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll();

        // Map rows → JSON format the frontend expects
        $data = array_map(fn($row) => [
            'id'          => (int)$row['id'],
            'productId'   => $row['product_id'],
            'variantId'   => $row['variant_id'],
            'color'       => $row['color'],
            'size'        => $row['size'],
            'quantity'    => (int)$row['quantity'],
            'artworkFile' => $row['artwork_file'],
            'artworkUrl'  => $row['artwork_url'],
            'cartId'      => $row['cart_id'],
            'checkoutUrl' => $row['checkout_url'],
            'status'      => $row['status'],
            'createdAt'   => $row['created_at'],
            'updatedAt'   => null, // we don't have this column in DB
        ], $rows);

        Response::json([
            'data' => $data,
            'pageInfo' => [
                'hasNextPage'     => ($offset + $perPage) < $total,
                'hasPreviousPage' => $page > 1,
                'currentPage'     => $page,
                'perPage'         => $perPage,
                'total'           => $total,
            ],
        ]);
    } catch (\PDOException $e) {
        // Dev mode: show error. Later you can revert to generic message.
        Response::json(['error' => $e->getMessage()], 500);
    }
}


}
