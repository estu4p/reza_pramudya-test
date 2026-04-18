<?php

namespace App\Http\Controllers;

use App\Http\Requests\Layup\StoreLayupRequest;
use App\Http\Requests\Layup\UpdateLayupRequest;
use App\Models\Layup;
use App\Models\Supplier;
use App\Services\LayupService;

class LayupController extends Controller
{
    public function __construct(
        protected LayupService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Supplier $supplier)
    {
        return response()->json(
            $this->service->getBySupplier($supplier)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLayupRequest $request, Supplier $supplier)
    {
        return response()->json(
            $this->service->create($supplier, $request->validated()),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier, Layup $layup)
    {
        return response()->json(
            $this->service->show($supplier, $layup)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLayupRequest $request, Supplier $supplier, Layup $layup)
    {

        return response()->json(
            $this->service->update($supplier, $layup, $request->validated())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier, Layup $layup)
    {
        $this->service->delete($supplier, $layup);

        return response()->json(['message' => 'Layup deleted']);
    }
}
