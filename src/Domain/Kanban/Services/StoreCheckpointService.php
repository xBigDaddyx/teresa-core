<?php

namespace Domain\Kanban\Services;

use Domain\Kanban\Models\Bundle;
use Domain\Kanban\Models\Packing;

class StoreCheckpointService
{
    public function store($checkpoint, $data, $plan_id)
    {
        $checkService = new CheckStatusBundleService();
        if ($checkpoint === 'BUN') {
            foreach ($data as $bundle) {
                Bundle::updateOrCreate(
                    ['Bundle_No' => $bundle['Bundle_No']],
                    [
                        'Country' => $bundle['Country'],
                        'Factory_Code' => $bundle['Factory_Code'],
                        'Contract_No' => $bundle['Contract_No'],
                        'MO_No' => $bundle['MO_No'],
                        'Style_No' => $bundle['Style_No'],
                        'Job_Order' => $bundle['Job_Order'],
                        'Color' => $bundle['Color'],
                        'Received_QTY' => $bundle['Received_QTY'],
                        'WIP_CheckPoint' => $bundle['WIP_CheckPoint'],
                        'Line_No' => $bundle['Line_No'],
                        'Create_Date' => $bundle['Create_Date'],
                        'plan_id' => $plan_id,
                        'sewing_id' => $checkService->getSewingNoShift($bundle['Line_No'])
                    ]
                );
            }
        } else if ($checkpoint === 'PAC') {
            foreach ($data as $packing) {
                Packing::updateOrCreate(
                    ['Bundle_No' => $packing['Bundle_No']],
                    [
                        'Country' => $packing['Country'],
                        'Factory_Code' => $packing['Factory_Code'],
                        'Contract_No' => $packing['Contract_No'],
                        'MO_No' => $packing['MO_No'],
                        'Style_No' => $packing['Style_No'],
                        'Job_Order' => $packing['Job_Order'],
                        'Color' => $packing['Color'],
                        'Received_QTY' => $packing['Received_QTY'],
                        'WIP_CheckPoint' => $packing['WIP_CheckPoint'],
                        'Line_No' => $packing['Line_No'],
                        'Create_Date' => $packing['Create_Date'],
                        'plan_id' => $plan_id,
                        'sewing_id' => $checkService->getSewingNoShift($packing['Line_No'])
                    ]
                );
            }
            return 1;
        } else {
            return 0;
        }
    }
}
