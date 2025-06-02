@props(['attendance'])

<div class="flex items-center justify-between rounded-md border border-slate-200 px-6 py-4 relative">
    <x-number-container><i class="ti ti-calendar-event mr-1"></i>{{$attendance->date}}</x-number-container>
    <x-attendance-status :status="$attendance->status">{{ucfirst($attendance->status)}}</x-attendance-status>

    @if ($attendance->date === now()->toDateString())
    <span
        class="absolute top-0 left-1/2 text-sm  font-medium bg-blue-200 text-blue-500 -translate-y-1/2 -translate-x-1/2 px-4 leading-2  py-2 rounded-full text-center">Today</span>
    @endif
</div>