<?php

namespace App\Exports;

use App\Models\Sport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SportsExport implements FromCollection, WithHeadings
{
    public function __construct()
    {
    }

    public function collection()
    {
        $academyId = activeAcademy()?->id;

        return Sport::where('academy_id', $academyId)
            ->select('id', 'sport_title', 'sport_fees', 'created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Sport Title',
            'Sport Fees',
            'Created At',
        ];
    }
}
