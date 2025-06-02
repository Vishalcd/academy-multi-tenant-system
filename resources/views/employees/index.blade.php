<x-layout>
    <x-slot name="title">Employees</x-slot>

    @if (Auth::user()->role !== "admin")
    {{-- add employee form --}}
    <template id="add-employee">
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            @csrf

            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Add New employee</x-heading>
            </div>

            <!-- Row Left -->
            <div class="grid gap-y-6">
                @csrf
                <x-input-box lable="Enter Full Name" name="name" id="name" placeholder="FullName" icon="user" />
                <x-input-box lable="Email Address" name="email" id="email" placeholder="your@mail.com" icon="mail" />
                <x-input-box lable="Phone Number" name="phone" id="phone" placeholder="xxxxx-xxxxx" icon="phone" />
                <x-input-box lable="Address" name="address" id="address" placeholder="672 Dickens Plaza Lamonttown"
                    icon="map-pin" />
            </div>

            <!-- Row Right -->
            <div class="grid gap-y-6">
                <x-input-box lable="Enter Salary" name="salary" id="salary" placeholder="{{formatCurrency(30000)}}"
                    icon="moneybag" />

                <!-- Input Row -->
                <x-input-box lable="Academy" name="Academy" id="Academy" value="{{activeAcademy()?->name}}"
                    disabled="disabled" icon="building-stadium" />

                <div class="flex items-center gap-2">
                    <label class="font-medium text-sm flex items-center gap-1.5 min-w-40 text-slate-600" for="sport_id">
                        <span class="text-lg"><i class="ti ti-ball-american-football"></i></span>Select Sport
                    </label>
                    <x-select name="sport_id" id="sport_id" :options="$sports" />
                </div>

                <x-input-box lable="Upload Image" type="file" name="photo" id="photo" icon="photo-up" />
            </div>

            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Add Employee</span></x-button-primary>
            </div>
        </form>
    </template>
    @endif

    <main class="bg-white rounded-xl border border-slate-200">
        <!-- Page Details & Filters -->
        <div class="flex flex-col lg:flex-row gap-8 py-8 px-8 items-center justify-between">
            <div class="flex flex-col gap-1">
                <x-bread-crumb>
                    <a href="{{url("/employees")}}">Employees</a>
                </x-bread-crumb>
                <x-heading>{{ request('search') ? 'Search Result for: ' . request('search') : 'Employees Salary' }}
                </x-heading>
            </div>

            <div class="flex items-center gap-4 flex-wrap justify-center">
                @if (Auth::user()->role !== "admin")
                <a href="#add-employee">
                    <x-button-primary icon="square-rounded-plus">Add Employee
                    </x-button-primary>
                </a>
                @endif
                <x-button-filter url="employees.index">
                    {{-- Filter By Job Title & Salry Status --}}
                    @if (Auth::user()->role ==='manager')
                    <x-filter-row lable="Filter by Sport">
                        <x-select name="sport_id" id="sport_id" :options="['' => 'All Sports', ...$sports]" />
                    </x-filter-row>
                    @endif

                    @if (Auth::user()->role === 'admin')
                    {{-- Filter By Academies --}}
                    <x-filter-row lable="Filter By Academy">
                        <x-select name="academy_id" id="academy_id" :options="$academies" />
                    </x-filter-row>
                    @endif

                    <x-filter-row lable="Filter by Salary">
                        <x-select name="salary_settled" id="salary_settled"
                            :options="[''=> 'Salary','false'=> 'Salary Due', 'true'=> 'Salary Settled']" />
                    </x-filter-row>

                </x-button-filter>
                <x-search-box :url="route('employees.index')" placeholder="Search by Name.." />
            </div>
        </div>

        <!-- Table -->
        <div class="grid overflow-auto whitespace-nowrap">
            <!-- Table Heading -->
            <x-table-heading grid="employee-grid">
                <p>#ID</p>
                <p>Details</p>
                <p>Phone</p>
                <p>Salary Status</p>
                <p>Last Paid Time & Date</p>
                <p>&nbsp;</p>
            </x-table-heading>

            <!-- Table Rows Start -->
            @forelse ($employees as $employee)
            <x-employee-row :employee="$employee" />
            @empty
            <x-no-content>Employee</x-no-content>
            @endforelse
            <!-- Table Row End -->
        </div>
    </main>

    {{-- Pagination --}}
    {{ $employees->links() }}
</x-layout>