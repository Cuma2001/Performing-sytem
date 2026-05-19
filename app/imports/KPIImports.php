<?php

namespace App\Imports;

use App\Models\KPI;
use Maatwebsite\Excel\Concerns\ToModel;

class KPIImport implements ToModel
{
    public function model(array $row)
    {
        return new KPI([
            'kpi_name' => $row[0],
            'kpi_category' => $row[1],
            'kpi_type' => $row[2],
            'weighting' => $row[3],
            'target_value' => $row[4],
            'financial_period' => $row[5]
        ]);
    }
}