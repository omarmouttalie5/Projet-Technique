<?php

require_once __DIR__ . '/BaseService.php';

class ProductService extends BaseService
{
    public function add(string $name, string $price, string $size): array
    {
        $name = trim($name);
        $price = trim($price);
        $size = strtoupper(trim($size));

        if ($name === '') {
            throw new InvalidArgumentException('The name field is required.');
        }
        if ($price === '' || !is_numeric($price)) {
            throw new InvalidArgumentException('Price must be a valid number.');
        }
        if (!in_array($size, ['XS', 'S', 'M', 'L', 'XL'], true)) {
            throw new InvalidArgumentException('Size must be one of: XS, S, M, L, XL.');
        }

        return $this->dao->add(new Product(0, htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), $price, $size));
    }
}
