<?php

namespace App\Services;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;

class SupplierService
{
    public function __construct(
        protected SupplierRepositoryInterface $repo
    ) {}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function show(Supplier $supplier)
    {
        $this->ensureOwnership($supplier);
        return $supplier->load('layups.layers');
    }

    public function update(Supplier $supplier, array $data)
    {
        $this->ensureOwnership($supplier);
        return $this->repo->update($supplier, $data);
    }

    public function delete(Supplier $supplier)
    {
        $this->ensureOwnership($supplier);
        return $this->repo->delete($supplier);
    }

    public function export($supplier)
    {
        return $supplier->load('layups.layers');
    }

    private function ensureOwnership(Supplier $supplier)
    {
        if (!$supplier) {
            abort(404, 'Supplier not found');
        }
    }
}
