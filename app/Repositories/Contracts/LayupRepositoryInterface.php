<?php

namespace App\Repositories\Contracts;

interface LayupRepositoryInterface
{
    public function getBySupplier($supplier);
    public function create($supplier, array $data);
    public function update($layup, array $data);
    public function delete($layup);
}
