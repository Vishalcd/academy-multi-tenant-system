<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Sport;
use App\Models\Student;
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
        $today = now()->toDateString();
        $filterSportId = $request->input('sport_id');
        $search = $request->input('search');
        $filterBatch = $request->input('batch');


        $attendances = collect();
        $students = collect();
        $studentsByBatch = collect();
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
                ->when($filterBatch, fn ($query) =>
                    $query->whereHas('student', fn ($q) =>
                        $q->where('batch', $filterBatch)
                    )
                )
                ->paginate(20);

            $students = Student::with('user')
                ->whereHas('user', fn ($q) => $q->where('academy_id', $academy_id))
                ->where('sport_id', $sport_id)
                ->get()
                ->sortBy(fn ($student) => $student->user->name)
                ->values();

            // Group attendances by student.batch
            $studentsByBatch = $students->groupBy('batch')->filter(function ($batchStudents, $batch) use ($user, $today) {
                $studentIds = $batchStudents->pluck('id');

                $alreadyTaken = Attendance::whereDate('date', $today)
                    ->whereIn('student_id', $studentIds)
                    ->where('recorded_by', $user->id)
                    ->exists();

                return ! $alreadyTaken; // keep batch only if not already taken
            });


            $sports = Sport::where('academy_id', $academy_id)->get(); // You can keep this to show sports for context
        }

        if ($user->role === 'manager') {
            $attendances = Attendance::with(['student.user', 'student.sport'])
                ->whereHas('student.user', fn ($q) => $q->where('academy_id', $academy_id))
                ->whereDate('date', $filterDate)
                ->when($filterSportId, fn ($query) =>
                    $query->whereHas('student', fn ($q) => $q->where('sport_id', $filterSportId))
                )
                ->when($filterBatch, fn ($query) =>
                    $query->whereHas('student', fn ($q) => $q->where('batch', $filterBatch))
                )
                ->when($search, fn ($query) =>
                    $query->whereHas('student.user', fn ($q) =>
                        $q->where('name', 'like', '%'.$search.'%')
                    )
                )
                ->paginate(20);

            $sports = Sport::where('academy_id', $academy_id)->get(); // Needed for the dropdown
        }

        return view('attendances.index', compact(
            'attendances',
            'studentsByBatch',
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

        $validatedData = $request->validate([
            'batch' => 'required|string',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent',
        ]);

        $batch = $validatedData['batch'];
        $attendanceEntries = $validatedData['attendances'];

        // Get all student IDs in this batch and sport
        $studentIds = Student::where('batch', $batch)
            ->where('sport_id', $sport_id)
            ->pluck('id');

        // Check if any student in this batch already has attendance today
        $alreadyTaken = Attendance::whereDate('date', $today)
            ->whereIn('student_id', $studentIds)
            ->where('recorded_by', $user->id)
            ->exists();

        if ($alreadyTaken) {
            return redirect()->route('attendances.index')
                ->with('error', "Attendance for Batch {$batch} has already been recorded.");
        }

        // Save attendance for each student
        foreach ($attendanceEntries as $entry) {
            $studentId = $entry['student_id'];
            $status = $entry['status'];

            if (! $studentIds->contains($studentId)) {
                continue; // Skip student if not in this batch and sport
            }

            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $today],
                ['status' => $status, 'recorded_by' => $user->id]
            );
        }

        return redirect()->route('attendances.index')->with('success', "Attendance recorded for Batch {$batch}.");
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
