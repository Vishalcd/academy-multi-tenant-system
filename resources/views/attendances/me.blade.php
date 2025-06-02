<x-layout>
    <x-slot name="title">Attendance</x-slot>

    @php
    $monthOptions = collect(range(1, 12))->mapWithKeys(function ($m) {
    return [$m => \Carbon\Carbon::create()->month($m)->format('F')];
    })->toArray();

    @endphp

    <main class="bg-white rounded-xl border border-slate-200 ">
        <!-- Page Details & Filters -->
        <div class="border-b border-slate-200 flex flex-col lg:flex-row gap-8 py-8 px-8 items-center justify-between">
            <div class="flex flex-col gap-1">
                <x-heading>{{ request('search')
                    ? 'Search Result for: ' . request('search')
                    : \DateTime::createFromFormat('!m', request('month', date('n')))->format('F')
                    . ' ' . request('year', date('Y')) . ' Attendance' }}
                </x-heading>
                <span class="font-mono font-bold text-base leading-5 tracking-tight text-slate-500">
                    Today Date: {{date('Y-m-d')}}</span>
            </div>

            <div class="flex items-center gap-4 flex-wrap justify-center">

                <x-button-filter url="students.showAttaendance">
                    <x-filter-row lable="Filter by Date">

                        <x-select name="month" id="month" :options="$monthOptions"
                            :selected="request('month', now()->month)" />

                    </x-filter-row>

                    <x-filter-row lable="Filter by Date">
                        <x-select name="year" id="year" :options="generateYearArray(2)"
                            :selected="request('year', now()->year)" />
                    </x-filter-row>
                </x-button-filter>

            </div>
        </div>

        <!-- Table -->
        <div class="px-8 py-8">
            <div class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 md:gap-5  min-w-full ">
                <!-- Table Heading -->
                @forelse ($attendances as $attendance)
                <x-student-attendance-row :attendance="$attendance" />
                @empty
                <div class="col-start-1 -col-end-1">
                    <x-no-content>Attendance</x-no-content>
                </div>
                @endforelse

                <!-- Table Rows Start -->
            </div>
        </div>

    </main>

    {{-- Pagination --}}
    {{-- {{ $academys->links() }} --}}
</x-layout>