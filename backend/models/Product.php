<?php

class Product
{
    public function __construct(
        public int $id,
        public string $name,
        public string $price,
        public string $size
    ) {}
}
