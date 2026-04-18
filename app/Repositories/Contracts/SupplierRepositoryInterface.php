<?php

namespace App\Repositories\Contracts;

interface SupplierRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update($supplier, array $data);
    public function delete($supplier);
}
