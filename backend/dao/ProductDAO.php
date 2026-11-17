<?php

require_once __DIR__ . '/../models/Product.php';

class ProductDAO
{
    public function __construct(private string $filePath) {}

    public function getAll(): array
    {
        $json = file_get_contents($this->filePath);
        $products = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($products)) {
            throw new RuntimeException('Product data must be a JSON array.');
        }

        return $products;
    }

    public function add(Product $product): array
    {
        $products = $this->getAll();
        $item = [
            'id' => $products ? max(array_column($products, 'id')) + 1 : 1,
            'name' => $product->name,
            'price' => $product->price,
            'size' => $product->size
        ];
        $products[] = $item;

        if (file_put_contents($this->filePath, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) === false) {
            throw new RuntimeException('Could not save product data.');
        }

        return $item;
    }
}
