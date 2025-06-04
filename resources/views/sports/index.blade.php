<x-layout>
    <x-slot name="title">Sports</x-slot>


    @if (Auth::user()->role !== "admin")
    {{-- add sport form --}}
    <template id="add-sport">
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-8 gap-y-4">
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Add New Sport</x-heading>
            </div>

            <!-- Rows -->
            <div class="grid gap-y-6">
                @csrf
                <x-input-box lable="Sport Title" name="sport_title" id="sport_title" placeholder="Sport Title"
                    icon="ball-american-football" />
                <x-input-box lable="Sport Fees" type="number" name="sport_fees" id="sport_fees"
                    placeholder="{{formatCurrency(2000)}}" icon="moneybag" />
                <x-input-box lable="Sport Image" type="file" name="photo" id="photo" icon="photo" />
            </div>
            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Add Sport</span></x-button-primary>
            </div>
        </form>
    </template>
    @endif


    <main class="bg-white rounded-xl border border-slate-200 ">
        <!-- Page Details & Filters -->
        <div class="flex flex-col lg:flex-row gap-8 py-8 px-8 items-center justify-between">
            <div class="flex flex-col gap-1">
                <x-bread-crumb>
                    <a href="{{url("/sports")}}">Sport Fee</a>
                </x-bread-crumb>
                <x-heading>{{ request('search') ? 'Search Result for: ' . request('search') : 'All Sports' }}
                </x-heading>
            </div>

            <div class="flex items-center gap-4 flex-wrap justify-center">

                <x-search-box :url="route('sports.index')" placeholder="Search by Name.." />

                <x-button-filter url="sports.index">
                    @if (Auth::user()->role === 'admin')
                    {{-- Filter By Academies --}}
                    <x-filter-row lable="Filter By Academy">
                        <x-select name="academy_id" id="academy_id" :options="$academies" />
                    </x-filter-row>
                    @endif

                    {{-- Sort By Price --}}
                    <x-filter-row lable="Sort By Fees">
                        <x-select name="sport_fees" id="sport_fees"
                            :options="['' => 'All Sports','low_to_high' => 'Expense Low to High','high_to_low' => 'Expense High to Low']" />
                    </x-filter-row>
                </x-button-filter>

                @if (Auth::user()->role !== "admin")
                <a href="#add-sport">
                    <x-button-primary icon="square-rounded-plus">Add Sport
                    </x-button-primary>
                </a>
                @endif
                <x-export-btn url="{{route('sports.export')}}" />

            </div>
        </div>

        <!-- Table -->
        <div class=" grid overflow-auto whitespace-nowrap">
            <!-- Table Heading -->
            <x-table-heading grid='sport-grid'>
                <p>#ID</p>
                <p>Details</p>
                <p>Academy</p>
                <p>Joining Fee</p>
                <p>Created At</p>
                <p>&nbsp;</p>
            </x-table-heading>

            <!-- Table Rows Start -->
            @forelse ($sports as $sport)
            <x-sport-row :sport="$sport" />
            @empty
            <x-no-content>Sport</x-no-content>
            @endforelse
            <!-- Table Row End -->
        </div>
    </main>

    {{-- Pagination --}}
    {{ $sports->links() }}
</x-layout>