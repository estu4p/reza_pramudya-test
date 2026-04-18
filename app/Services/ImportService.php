<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\Layup;
use App\Models\Layer;
use App\Enums\ImportStrategy;
use Illuminate\Support\Facades\DB;

class ImportService
{
    public function import(Supplier $supplier, array $data, string $strategy)
    {
        $strategy = ImportStrategy::from($strategy);

        return DB::transaction(function () use ($supplier, $data, $strategy) {

            $conflicts = [];
            $created = 0;
            $updated = 0;

            foreach ($data['layups'] as $layupData) {

                // 1. Cari layup berdasarkan nama
                $layup = Layup::where('supplier_id', $supplier->id)
                    ->where('name', $layupData['name'])
                    ->first();

                // DUPLICATE STRATEGY (langsung bikin baru)
                if ($layup && $strategy === ImportStrategy::DUPLICATE) {
                    $layup = Layup::create([
                        'supplier_id' => $supplier->id,
                        'name' => $layupData['name'] . ' (imported)'
                    ]);
                    $created++;
                }

                // kalau belum ada
                if (!$layup) {
                    $layup = Layup::create([
                        'supplier_id' => $supplier->id,
                        'name' => $layupData['name']
                    ]);
                    $created++;
                }

                foreach ($layupData['layers'] as $layerData) {

                    // Cari layer berdasarkan order
                    $existingLayer = Layer::where('layup_id', $layup->id)
                        ->where('layer_order', $layerData['layer_order'])
                        ->first();

                    // kalau belum ada
                    if (!$existingLayer) {
                        Layer::create([
                            'layup_id' => $layup->id,
                            ...$layerData
                        ]);
                        $created++;
                        continue;
                    }

                    // DETECT CONFLICT
                    $isConflict =
                        $existingLayer->thickness != $layerData['thickness'] ||
                        $existingLayer->width != $layerData['width'] ||
                        $existingLayer->angle != $layerData['angle'];

                    if (!$isConflict) {
                        continue;
                    }

                    // simpan conflict
                    $conflict = [
                        'layup' => $layup->name,
                        'layer_order' => $layerData['layer_order'],
                        'existing' => [
                            'thickness' => $existingLayer->thickness,
                            'width' => $existingLayer->width,
                            'angle' => $existingLayer->angle,
                        ],
                        'incoming' => $layerData
                    ];

                    // HANDLE STRATEGY
                    switch ($strategy) {

                        case ImportStrategy::OVERWRITE:
                            $existingLayer->update($layerData);
                            $updated++;
                            break;

                        case ImportStrategy::SKIP:
                            // do nothing
                            break;

                        case ImportStrategy::REJECT:
                            $conflicts[] = $conflict;
                            break;

                        case ImportStrategy::DUPLICATE:
                            // skip layer conflict karena layup sudah diduplicate
                            break;
                    }

                    if ($strategy === ImportStrategy::REJECT) {
                        $conflicts[] = $conflict;
                    }
                }
            }

            // REJECT: batalkan semua
            if ($strategy === ImportStrategy::REJECT && count($conflicts)) {
                return [
                    'status' => 'failed',
                    'message' => 'Conflict detected',
                    'conflicts' => $conflicts
                ];
            }

            return [
                'status' => 'success',
                'created' => $created,
                'updated' => $updated,
                'conflicts' => $conflicts
            ];
        });
    }
}
