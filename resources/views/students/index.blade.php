<x-layout>
    <x-slot name="title">Students</x-slot>

    @if (Auth::user()->role !== "admin")
    {{-- add student form --}}
    <template id="add-student">
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            @csrf
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Add New Student</x-heading>
            </div>

            <!-- Row Left -->
            <div class="grid gap-y-6">
                <x-input-box lable="Enter Full Name" name="name" id="name" placeholder="FullName" icon="user" />
                <x-input-box lable="Email Address" name="email" id="email" placeholder="your@mail.com" icon="mail" />
                <x-input-box lable="Phone Number" name="phone" id="phone" placeholder="xxxxx-xxxxx" icon="phone" />
                <x-input-box lable="Student Address" name="address" id="address"
                    placeholder="672 Dickens Plaza Lamonttown" icon="map-pin" />
            </div>

            <!-- Row Right -->
            <div class="grid gap-y-6">
                <x-input-box lable="Enter Total Fees" name="total_fees" id="total_fees"
                    placeholder="{{formatCurrency(30000)}}" icon="moneybag" />

                <x-input-box lable="Admission Date" type="date" name="created_at" id="created_at"
                    icon="calendar-event" />

                <x-input-box lable="Academy" name="Academy" id="Academy" value="{{activeAcademy()?->name}}"
                    disabled="disabled" icon="building-stadium" />

                <div class="flex items-center gap-2">
                    <label class="font-medium text-sm flex items-center gap-1.5 min-w-40 text-slate-600" for="sport_id">
                        <span class="text-lg"><i class="ti ti-ball-american-football"></i></span>Select Sport
                    </label>
                    <x-select name="sport_id" id="sport_id" :options="$sports" />
                </div>
            </div>

            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Add Student</span></x-button-primary>
            </div>
        </form>
    </template>
    @endif

    <main class="bg-white rounded-xl border border-slate-200 ">
        <!-- Page Details & Filters -->
        <div class="flex flex-col lg:flex-row gap-8 py-8 px-8 items-center justify-between">
            <div class="flex flex-col gap-1">
                <x-bread-crumb>
                    <a href="{{url("/students")}}">Student Fee</a>
                </x-bread-crumb>
                <x-heading>{{ request('search') ? 'Search Result for: ' . request('search') : 'All Students' }}
                </x-heading>
            </div>

            <div class="flex items-center gap-4 flex-wrap justify-center">
                @if (Auth::user()->role !== "admin")
                <a href="#add-student">
                    <x-button-primary icon="square-rounded-plus">Add Student
                    </x-button-primary>
                </a>
                @endif
                <x-button-filter url="students.index">
                    {{-- Filter By Sports --}}
                    @if (Auth::user()->role === 'manager')
                    <x-filter-row lable="Sports">
                        <x-select name="sport_id" id="sport_id" :options="['' => 'All Sports'] + $sports" />
                    </x-filter-row>
                    @endif

                    @if (Auth::user()->role === 'admin')
                    {{-- Filter By Academies --}}
                    <x-filter-row lable="Filter By Academy">
                        <x-select name="academy_id" id="academy_id" :options="$academies" />
                    </x-filter-row>
                    @endif

                    {{-- Sort By sattled fees--}}
                    <x-filter-row lable="Sort By">
                        <x-select name="fees_settle" id="sort"
                            :options="['' => 'All Students', 'true' => 'Fee Settled', 'false' => 'Fee Due']" />
                    </x-filter-row>
                </x-button-filter>
                <x-search-box :url="route('students.index')" placeholder="Search by Name.." />


            </div>
        </div>

        <!-- Table -->
        <div class=" grid overflow-auto whitespace-nowrap">
            <!-- Table Heading -->
            <x-table-heading grid='student-grid'>
                <p>#ID</p>
                <p>Details</p>
                <p>Phone</p>
                <p>Status</p>
                <p>Date</p>
                <p>&nbsp;</p>
            </x-table-heading>

            <!-- Table Rows Start -->
            @forelse ($students as $student)
            <x-student-row :student="$student" />
            @empty
            <x-no-content>Student</x-no-content>
            @endforelse
            <!-- Table Row End -->
        </div>
    </main>

    {{-- Pagination --}}
    {{ $students->links() }}
</x-layout>