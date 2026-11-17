<?php

require_once __DIR__ . '/../dao/ProductDAO.php';

abstract class BaseService
{
    public function __construct(protected ProductDAO $dao) {}

    public function getAll(): array
    {
        return $this->dao->getAll();
    }
}
