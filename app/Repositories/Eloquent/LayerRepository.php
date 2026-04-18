<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\LayerRepositoryInterface;

class LayerRepository implements LayerRepositoryInterface
{
    public function getByLayup($layup)
    {
        return $layup->layers;
    }

    public function create($layup, array $data)
    {
        return $layup->layers()->create($data);
    }

    public function update($layer, array $data)
    {
        $layer->update($data);
        return $layer;
    }

    public function delete($layer)
    {
        return $layer->delete();
    }
}
