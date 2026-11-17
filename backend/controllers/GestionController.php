<?php

require_once __DIR__ . '/../services/ProductService.php';

class GestionController
{
    private ProductService $service;

    public function __construct()
    {
        $dao = new ProductDAO(__DIR__ . '/../data/products.json');
        $this->service = new ProductService($dao);
    }

    public function handleRequest(): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode($this->service->getAll());
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON body.']);
            return;
        }

        try {
            $item = $this->service->add(
                is_scalar($data['name'] ?? null) ? (string) $data['name'] : '',
                is_scalar($data['price'] ?? null) ? (string) $data['price'] : '',
                is_scalar($data['size'] ?? null) ? (string) $data['size'] : ''
            );
            echo json_encode(['success' => true, 'data' => $item]);
        } catch (InvalidArgumentException $error) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $error->getMessage()]);
        }
    }
}
