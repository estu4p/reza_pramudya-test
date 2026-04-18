<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\ImportService;
use App\Enums\ImportStrategy;
use App\Http\Requests\Import\ImportRequest;
use Illuminate\Http\JsonResponse;

class ImportController extends Controller
{
    public function __construct(
        protected ImportService $importService
    ) {}

    public function import(
        ImportRequest $request,
        Supplier $supplier
    ): JsonResponse {
        // 🎯 ambil strategy dari query param
        $strategy = $request->query('strategy', 'overwrite');

        // validasi strategy biar aman
        if (!in_array($strategy, array_column(ImportStrategy::cases(), 'value'))) {
            return response()->json([
                'message' => 'Invalid strategy',
                'allowed' => array_column(ImportStrategy::cases(), 'value')
            ], 422);
        }

        // 🚀 jalankan import
        $result = $this->importService->import(
            $supplier,
            $request->validated(),
            $strategy
        );

        // ⚠️ handle reject (conflict)
        if ($result['status'] === 'failed') {
            return response()->json($result, 409); // conflict HTTP code
        }

        return response()->json($result);
    }
}
