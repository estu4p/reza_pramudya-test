<?php

namespace App\Http\Controllers;

use App\Http\Requests\Layer\StoreLayerRequest;
use App\Http\Requests\Layer\UpdateLayerRequest;
use App\Models\Layer;
use App\Models\Layup;
use App\Services\LayerService;

class LayerController extends Controller
{
    public function __construct(
        protected LayerService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Layup $layup)
    {
        return response()->json(
            $this->service->getByLayup($layup)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLayerRequest $request, Layup $layup)
    {

        return response()->json(
            $this->service->create($layup, $request->validated()),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Layup $layup, Layer $layer)
    {
        return response()->json(
            $this->service->show($layup, $layer)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLayerRequest $request, Layup $layup, Layer $layer)
    {

        return response()->json(
            $this->service->update($layup, $layer, $request->validated())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layup $layup, Layer $layer)
    {
        $this->service->delete($layup, $layer);

        return response()->json(['message' => 'Layer deleted']);
    }
}
