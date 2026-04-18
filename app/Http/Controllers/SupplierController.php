<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierService;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $service
    ) {}


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->service->getAll());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        return response()->json(
            $this->service->create($request->validated()),
            201
        );
    }

    public function show(Supplier $supplier)
    {
        return $this->service->show($supplier);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        return response()->json(
            $this->service->update($supplier, $request->validated())
        );
    }

    public function export(Supplier $supplier)
    {
        return response()->json(
            $this->service->export($supplier)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $this->service->delete($supplier);
        return response()->json(['message' => 'Supplier deleted']);
    }
}
