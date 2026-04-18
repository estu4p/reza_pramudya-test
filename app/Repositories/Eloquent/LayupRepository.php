<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\LayupRepositoryInterface;

class LayupRepository implements LayupRepositoryInterface
{
    public function getBySupplier($supplier)
    {
        return $supplier->layups()->with('layers')->get();
    }

    public function create($supplier, array $data)
    {
        return $supplier->layups()->create($data);
    }

    public function show($layup)
    {
        return $layup->load('layers');
    }


    public function update($layup, array $data)
    {
        $layup->update($data);
        return $layup;
    }

    public function delete($layup)
    {
        return $layup->delete();
    }
}
