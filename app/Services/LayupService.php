<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\Layup;
use App\Repositories\Contracts\LayupRepositoryInterface;

class LayupService
{
    public function __construct(
        protected LayupRepositoryInterface $repo
    ) {}

    public function getBySupplier(Supplier $supplier)
    {
        return $this->repo->getBySupplier($supplier);
    }

    public function create(Supplier $supplier, array $data)
    {
        return $this->repo->create($supplier, $data);
    }

    public function show(Supplier $supplier, Layup $layup)
    {
        $this->ensureOwnership($supplier, $layup);

        return $layup->load('layers');
    }

    public function update(Supplier $supplier, Layup $layup, array $data)
    {
        $this->ensureOwnership($supplier, $layup);

        return $this->repo->update($layup, $data);
    }

    public function delete(Supplier $supplier, Layup $layup)
    {
        $this->ensureOwnership($supplier, $layup);

        return $this->repo->delete($layup);
    }

    private function ensureOwnership(Supplier $supplier, Layup $layup)
    {
        if ($layup->supplier_id !== $supplier->id) {
            abort(404, 'Layup not found in this supplier');
        }
    }
}
