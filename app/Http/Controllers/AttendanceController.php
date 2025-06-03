<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Sport;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // @Method GET
    // @Route /attendances
    public function index(Request $request)
    {
        $user = Auth::user();
        $academy_id = $user->academy_id;
        $filterDate = $request->input('date', now()->toDateString());
        $filterSportId = $request->input('sport_id');
        $search = $request->input('search');

        $attendances = collect();
        $students = collect();
        $alreadyTaken = false;

        if ($user->role === 'employee') {
            $sport_id = Employee::where('user_id', $user->id)->value('sport_id');

            $alreadyTaken = Attendance::whereDate('date', now()->toDateString())
                ->whereIn('student_id', Student::where('sport_id', $sport_id)->pluck('id'))
                ->where('recorded_by', $user->id)
                ->exists();

            $attendances = Attendance::with(['student.user', 'student.sport'])
                ->where('recorded_by', $user->id)
                ->whereDate('date', $filterDate)
                ->when($search, fn ($query) =>
                    $query->whereHas('student.user', fn ($q) =>
                        $q->where('name', 'like', '%'.$search.'%')
                    )
                )
                ->paginate(10); // Removed sport filter here

            $students = Student::with('user')
                ->whereHas('user', fn ($q) => $q->where('academy_id', $academy_id))
                ->where('sport_id', $sport_id)
                ->get()
                ->sortBy(fn ($student) => $student->user->name)
                ->values();

            $sports = Sport::where('academy_id', $academy_id)->get(); // You can keep this to show sports for context
        }

        if ($user->role === 'manager') {
            $attendances = Attendance::with(['student.user', 'student.sport'])
                ->whereHas('student.user', fn ($q) => $q->where('academy_id', $academy_id))
                ->whereDate('date', $filterDate)
                ->when($filterSportId, fn ($query) =>
                    $query->whereHas('student', fn ($q) => $q->where('sport_id', $filterSportId))
                )
                ->when($search, fn ($query) =>
                    $query->whereHas('student.user', fn ($q) =>
                        $q->where('name', 'like', '%'.$search.'%')
                    )
                )
                ->paginate(10);

            $sports = Sport::where('academy_id', $academy_id)->get(); // Needed for the dropdown
        }

        return view('attendances.index', compact(
            'attendances',
            'students',
            'alreadyTaken',
            'filterDate',
            'sports',
            'filterSportId',
            'search'
        ));

    }

    // @Method POST
    // @Route /attendances
    public function store(Request $request)
    {
        $today = now()->toDateString();
        $user = Auth::user();
        $sport_id = Employee::where('user_id', $user->id)->value('sport_id');

        // Check if today's attendance already exists
        $alreadyTaken = Attendance::whereDate('date', $today)
            ->whereIn('student_id', Student::where('sport_id', $sport_id)->pluck('id'))
            ->where('recorded_by', $user->id)
            ->exists();

        if ($alreadyTaken) {
            return redirect()->route('attendances.index')->with('error', 'Today\'s attendance has already been recorded.');
        }

        $validatedData = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent',
        ]);

        $validatedData['date'] = $today;

        foreach ($validatedData['attendances'] as $data) {
            Attendance::updateOrCreate(
                ['student_id' => $data['student_id'], 'date' => $validatedData['date']],
                ['status' => $data['status'], 'recorded_by' => Auth::id()]
            );
        }

        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully.');
    }

    // @Method PUT
    // @Route /attendances/{id}
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:present,absent',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update(['status' => $validatedData['status']]);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance updated successfully.');
    }
}
