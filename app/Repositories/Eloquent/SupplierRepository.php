<?php

namespace App\Repositories\Eloquent;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all()
    {
        return Supplier::with('layups.layers')->get();
    }

    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function update($supplier, array $data)
    {
        $supplier->update($data);
        return $supplier;
    }

    public function delete($supplier)
    {
        return $supplier->delete();
    }
}
