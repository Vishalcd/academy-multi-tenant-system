<x-layout>
    <x-slot name="title">Attendance</x-slot>

    @if (!$alreadyTaken && Auth::user()->role === 'employee')
    {{-- take attendance form --}}
    <template id="take-attendance">
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-8 gap-y-4">
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Take Attendance of {{date('d, M, Y')}}</x-heading>
            </div>

            <!-- Rows -->
            <div
                class=" grid grid-cols-1 grid-rows-[min-h] md:grid-cols-2 gap-4 max-h-[80dvh] md:max-h-[60dvh] overflow-y-scroll scroll-smooth scroll">
                @csrf
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
    @endif

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

                @if (!$alreadyTaken && Auth::user()->role === 'employee')
                <a href="#take-attendance">
                    <x-button-primary icon="square-rounded-plus">Take Attendance
                    </x-button-primary>
                </a>
                @endif

                @if (Auth::user()->role === 'manager')
                <x-search-box :url="route('attendances.index')" placeholder="Search by Name.." />
                @endif

                <x-button-filter url="attendances.index">
                    {{-- Filter By Job Title & Salry Status --}}
                    @if (Auth::user()->role ==='manager')
                    <x-filter-row lable="Filter by Date">
                        <x-input-box type="date" name="date" id="date"
                            value="{{ request('date', now()->toDateString()) }}" placeholder="Select Date" />
                    </x-filter-row>
                    @endif

                    @if (Auth::user()->role ==='employee')
                    <x-filter-row lable="Filter by Date">
                        <x-input-box type="date" name="date" id="date"
                            value="{{ request('date', now()->toDateString()) }}" placeholder="Select Date" />
                    </x-filter-row>
                    @endif

                    @if (Auth::user()->role === 'manager')
                    <x-filter-row lable="Filter by Sport">
                        <x-select name="sport_id" id="sport_id" :options="getAllSports()" />
                    </x-filter-row>
                    @endif
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