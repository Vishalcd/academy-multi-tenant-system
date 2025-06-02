@props(['student'])

<div
    class="grid grid-cols-[auto_1fr] items-center border border-slate-200 p-4 rounded-lg h-min bg-white justify-between gap-4">
    {{-- Student Info --}}
    <x-user img="{{ $student->user->photo }}" alt_text="{{ $student->user->name }}"
        description_text="{{ $student->user->phone }}">
        {{ $student->user->name }}
    </x-user>

    {{-- Attendance Toggle --}}
    <div class="flex items-center gap-4 justify-end">
        <input type="hidden" name="attendances[{{ $student->id }}][student_id]" value="{{ $student->id }}">

        <label class="inline-flex items-center">
            <input type="radio" name="attendances[{{ $student->id }}][status]" value="present" class="sr-only peer"
                required>
            <div
                class="cursor-pointer w-20 px-2 py-1 rounded-md border border-green-700 text-green-700 font-semibold text-sm text-center peer-checked:bg-green-700 peer-checked:text-white transition">
                Present
            </div>
        </label>

        <label class="inline-flex items-center">
            <input type="radio" name="attendances[{{ $student->id }}][status]" value="absent" class="sr-only peer"
                required>
            <div
                class="cursor-pointer w-20 px-2 py-1 rounded-md border border-red-700 text-red-700 font-semibold text-sm text-center peer-checked:bg-red-700 peer-checked:text-white transition">
                Absent
            </div>
        </label>
    </div>
</div>