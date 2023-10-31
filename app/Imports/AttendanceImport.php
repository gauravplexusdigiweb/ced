<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class AttendanceImport implements ToModel
{
    public function model(array $row)
    {
        //
    }
}
