<?php

namespace Domain\Kanban\Services;

use Domain\Kanban\Models\Packing;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CheckStatusBundleService
{
    public function getSewingNoShift($line_no)
    {
        if (Str::contains($line_no, '-A')) {

            return Str::replace('-A', '', $line_no);
        }
        return Str::replace('-B', '', $line_no);
    }
    public function status($bundle_no, $line_no): string
    {
        $packing = Packing::where('Bundle_No', $bundle_no)->get();
        if ($packing->count() > 0) {
            $bun_sewing = $this->getSewingNoShift($line_no);
            $pac_sewing = $this->getSewingNoShift($packing->first()->Line_No);
            if ($pac_sewing !== $bun_sewing) {
                return 'Invalid Line';
            }
            return 'Completed';
        }
        return 'Incomplete';
    }

    public function locationPac($bundle_no): string
    {
        $packing = Packing::where('Bundle_No', $bundle_no)->get();
        if ($packing->count() > 0) {
            return $packing->first()->Line_No;
        }
        return '-';
    }
}
