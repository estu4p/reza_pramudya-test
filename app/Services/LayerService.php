<?php

namespace App\Services;

use App\Models\Layup;
use App\Models\Layer;
use App\Repositories\Contracts\LayerRepositoryInterface;

class LayerService
{
    public function __construct(
        protected LayerRepositoryInterface $repo
    ) {}

    public function getByLayup(Layup $layup)
    {
        return $this->repo->getByLayup($layup);
    }

    public function create(Layup $layup, array $data)
    {
        return $this->repo->create($layup, $data);
    }

    public function show(Layup $layup, Layer $layer)
    {
        $this->ensureOwnership($layup, $layer);

        return $layer;
    }

    public function update(Layup $layup, Layer $layer, array $data)
    {
        $this->ensureOwnership($layup, $layer);
        return $this->repo->update($layer, $data);
    }

    public function delete(Layup $layup, Layer $layer)
    {
        $this->ensureOwnership($layup, $layer);
        return $this->repo->delete($layer);
    }

    private function ensureOwnership(Layup $layup, Layer $layer)
    {
        if ($layer->layup_id !== $layup->id) {
            abort(404, 'Layer not found in this layup');
        }
    }
}
