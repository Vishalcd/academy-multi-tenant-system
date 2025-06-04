<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpensesExport implements FromCollection, WithHeadings
{
    protected $academyId;

    public function __construct($academyId = null)
    {
        $this->academyId = $academyId;
    }

    public function collection()
    {
        $query = Expense::query();

        if ($this->academyId) {
            $query->where('academy_id', $this->academyId);
        }

        return $query->get()->map(function ($expense) {
            return [
                'ID' => $expense->id,
                'Title' => $expense->title,
                'Description' => $expense->description,
                'Unit Price' => $expense->unit_price,
                'Quantity' => $expense->quantity,
                'Total Price' => $expense->total_price,
                'Payment Method' => $expense->payment_type,
                'Shop Name' => $expense->shop_details,
                'Payment Sattled' => $expense->payment_settled,
                'Date' => $expense->created_at->format('Y-m-d'), ,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Unit Price',
            'Quantity',
            'Total Price',
            'Payment Method',
            'Shop Name',
            'Payment Sattled',
            'Date',
        ];
    }
}
