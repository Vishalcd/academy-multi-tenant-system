<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $academyId = activeAcademy()?->id;
        return Employee::with(['user', 'sport'])->whereHas('user', fn ($q) => $q->where('academy_id', $academyId))->get();
    }

    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->user->name ?? '',
            $employee->user->email ?? '',
            $employee->user->address ?? '',
            $employee->user->phone ?? '',
            $employee->sport->sport_title ?? '',
            $employee->job_title,
            $employee->created_at->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Name',
            'Email',
            'Address',
            'Phone',
            'Sport',
            'Job Title',
            'Registered On',

        ];
    }
}
