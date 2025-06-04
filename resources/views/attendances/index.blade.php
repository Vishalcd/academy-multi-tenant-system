<x-layout>
    <x-slot name="title">Attendance</x-slot>



    @if (Auth::user()->role === 'employee')
    @foreach($studentsByBatch as $batch => $students)

    {{-- take attendance form --}}
    <template id="take-attendance-{{ $batch }}">
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-8 gap-y-4">
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Take Attendance of (Batch - {{ucfirst($batch)}})</x-heading>
            </div>

            <!-- Rows -->
            <div
                class=" grid grid-cols-1 grid-rows-[min-h] md:grid-cols-2 gap-4 max-h-[80dvh] md:max-h-[60dvh] overflow-y-scroll scroll-smooth scroll">
                @csrf
                <input type="hidden" name="batch" value="{{ $batch }}">
                <!-- Table Rows Start -->
                @forelse ($students as $student)
                <x-attendance-row :student="$student" />
                @empty
                <x-no-content>Today Attendance</x-no-content>
                @endforelse
                <!-- Table Row End -->
            </div>
            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Take Attendance</span></x-button-primary>
            </div>
        </form>
    </template>

    @endforeach
    @endif

    <template id="attendance-option">
        <div class=" grid grid-cols-1 space-y-6 px-4">
            <a href="#take-attendance-a">
                <x-button-primary icon="calendar-check">Take Attendance (Batch A)
                </x-button-primary>
            </a>
            <a href="#take-attendance-b">
                <x-button-primary icon="calendar-check">Take Attendance (Batch B)
                </x-button-primary>
            </a>
            <a href="#take-attendance-c">
                <x-button-primary icon="calendar-check">Take Attendance (Batch C)
                </x-button-primary>
            </a>
        </div>
    </template>



    <main class="bg-white rounded-xl border border-slate-200 ">
        <!-- Page Details & Filters -->
        <div class="flex flex-col lg:flex-row gap-8 py-8 px-8 items-center justify-between">
            <div class="flex flex-col gap-1">
                <x-heading>{{ request('search') ? 'Search Result for: ' . request('search') : 'Attendance' }}
                </x-heading>
                <span class=" font-mono text-base text-slate-500 tracking-tight leading-5 font-bold">{{ request('date',
                    now()->toDateString()) }}</span>
            </div>

            <div class="flex items-center gap-4 flex-wrap justify-center">

                @if (Auth::user()->role === 'employee')
                <a href="#attendance-option">
                    <x-button-primary icon="square-rounded-plus">Take Attendance
                    </x-button-primary>
                </a>
                @endif

                @if (Auth::user()->role === 'manager')
                <x-search-box :url="route('attendances.index')" placeholder="Search by Name.." />
                @endif

                <x-button-filter url="attendances.index">
                    {{-- Filter By Job Title & Salry Status --}}
                    <x-filter-row lable="Filter by Date">
                        <x-input-box type="date" name="date" id="date"
                            value="{{ request('date', now()->toDateString()) }}" placeholder="Select Date" />
                    </x-filter-row>

                    @if (Auth::user()->role === 'manager')
                    <x-filter-row lable="Filter by Sport">
                        <x-select name="sport_id" id="sport_id" :options="['' => 'All Sports'] + getAllSports()" />
                    </x-filter-row>
                    @endif

                    <x-filter-row lable="Filter by Batch">
                        <x-select name="batch" id="batch"
                            :options="['' => 'All Batches', 'a' => 'Batch-A','b' => 'Batch-B','c' => 'Batch-C']" />
                    </x-filter-row>
                </x-button-filter>


            </div>
        </div>

        <!-- Table -->
        <div class=" overflow-auto w-full">
            <!-- Table Heading -->
            <x-table-heading grid="attendance-grid">
                <p>#ID</p>
                <p>Details</p>
                <p>Phone</p>
                <p>Status</p>
                <p>Date</p>
                <p>Action</p>
            </x-table-heading>


            <!-- Table Rows Start -->
            @forelse ($attendances as $attendance)
            <x-attendance-complete-row :attendance="$attendance" />
            @empty
            <x-no-content>Today Attendance</x-no-content>
            @endforelse
        </div>

    </main>

    {{-- Pagination --}}
    {{ $attendances->withQueryString()->links() }}
</x-layout>