<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    // @Method GET
    // @Route /
    public function index(Request $request)
    {

        $year = $request->get('year') ?? now()->year;
        $year = (int) $year;

        // Determine academy filter
        $user = Auth::user();
        $academyId = null;

        if ($user->role === 'admin') {
            $academyId = $request->get('academy_id'); // admin can choose
        } else {
            $academyId = $user->academy_id; // manager/coach fixed to one
        }

        // Base query for academy filter
        $baseQuery = Transaction::whereYear('created_at', $year);
        if ($academyId) {
            $baseQuery->where('academy_id', $academyId);
        }

        // Totals
        $totalFees = (clone $baseQuery)->where('transaction_for', 'student_fee')->sum('transaction_amount');
        $totalSalary = (clone $baseQuery)->where('transaction_for', 'employee_salary')->sum('transaction_amount');
        $totalExpense = (clone $baseQuery)->where('transaction_for', 'expense')->sum('transaction_amount');
        $revenue = $totalFees - ($totalSalary + $totalExpense);

        // Last 8 Transactions
        $latestTransactions = (clone $baseQuery)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // Doughnut Chart Data
        $doughnutData = [
            'Fee Deposit' => $totalFees,
            'Salary' => $totalSalary,
            'Expense' => $totalExpense,
        ];

        // Graph Data by Month
        $types = [
            'student_fee' => 'student_fee',
            'salary' => 'employee_salary',
            'expense' => 'expense',
        ];

        $graphData = [];

        foreach ($types as $key => $type) {
            $monthly = array_fill(1, 12, 0); // Jan to Dec

            $monthlyQuery = Transaction::selectRaw('EXTRACT(MONTH FROM created_at) AS month, SUM(transaction_amount) AS total')
                ->where('transaction_for', $type)
                ->whereYear('created_at', $year);

            if ($academyId) {
                $monthlyQuery->where('academy_id', $academyId);
            }

            $results = $monthlyQuery->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
                ->pluck('total', 'month');

            foreach ($results as $month => $total) {
                $monthly[(int) $month] = (float) $total;
            }

            $graphData[$key] = array_values($monthly);
        }

        return view('pages.index', [
            'totalFees' => $totalFees,
            'totalSalary' => $totalSalary,
            'totalExpense' => $totalExpense,
            'revenue' => $revenue,
            'latestTransactions' => $latestTransactions,
            'doughnutData' => $doughnutData,
            'graphData' => $graphData,
            'selectedYear' => $year,
            'selectedAcademyId' => $academyId,
            'academies' => $user->role === 'admin' ? getAllAcademies() : null, // for dropdown
        ]);


    }
}
