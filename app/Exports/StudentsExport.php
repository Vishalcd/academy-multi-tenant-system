<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $academyId = activeAcademy()?->id;
        return Student::with(['user', 'sport'])->whereHas('user', fn ($q) => $q->where('academy_id', $academyId))
            ->get();
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->user->name,
            $student->user->email,
            $student->user->address,
            $student->user->phone,
            $student->user->phone ?? '',
            $student->sport->sport_title ?? '',
            $student->created_at->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Name',
            'Email',
            'Address',
            'Phone',
            'Phone',
            'Sport',
            'Registered On',
        ];
    }
}
