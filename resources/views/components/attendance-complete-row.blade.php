@props(['attendance' ])

<x-table-row grid='attendance-grid'>
    <x-number-container>#{{$attendance->id}}</x-number-container>
    <x-user img="{{$attendance->student->user->photo}}" alt_text="{{$attendance->student->user->name}}"
        description_text="{{$attendance->student->user->email}}">
        {{$attendance->student->user->name}} - <span
            class=" font-mono text-slate-600">{{$attendance->student->sport->sport_title}}</span>
    </x-user>
    <x-make-call>{{$attendance->student->user->phone}}</x-make-call>
    <x-attendance-status :status="$attendance->status">{{ucfirst($attendance->status)}}</x-attendance-status>
    <x-number-container>{{$attendance->student->user->created_at->format('d M, Y')}}</x-number-container>
    <x-button-link url="#edit-attendance-{{$attendance->id}}">Edit</x-button-link>

    {{-- take attendance form --}}
    <template id="edit-attendance-{{$attendance->id}}">
        <form action="{{route('attendances.update', $attendance->id)}}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 gap-x-8 gap-y-4">
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Update Attendance</x-heading>
            </div>

            <!-- Rows -->
            <div class=" grid">
                @csrf
                @method('PUT')
                <!-- Table Rows Start -->
                <div
                    class="grid grid-cols-[auto_1fr] items-center border border-slate-200 p-4 rounded-lg h-min bg-white justify-between gap-4">
                    {{-- Student Info --}}
                    <x-user img="{{ $attendance->student->user->photo }}"
                        alt_text="{{ $attendance->student->user->name }}"
                        description_text="{{ $attendance->student->user->phone }}">
                        {{ $attendance->student->user->name }}
                    </x-user>

                    {{-- Attendance Toggle --}}
                    <div class="flex items-center gap-4 justify-end">
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="present" class="sr-only peer" required>
                            <div
                                class="cursor-pointer w-20 px-2 py-1 rounded-md border border-green-700 text-green-700 font-semibold text-sm text-center peer-checked:bg-green-700 peer-checked:text-white transition">
                                Present
                            </div>
                        </label>

                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="absent" class="sr-only peer" required>
                            <div
                                class="cursor-pointer w-20 px-2 py-1 rounded-md border border-red-700 text-red-700 font-semibold text-sm text-center peer-checked:bg-red-700 peer-checked:text-white transition">
                                Absent
                            </div>
                        </label>
                    </div>
                </div>
                <!-- Table Row End -->
            </div>
            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Update Attendance</span></x-button-primary>
            </div>
        </form>
    </template>
</x-table-row>