<?php

namespace App\Repositories\Contracts;

interface LayerRepositoryInterface
{
    public function getByLayup($layup);
    public function create($layup, array $data);
    public function update($layer, array $data);
    public function delete($layer);
}
