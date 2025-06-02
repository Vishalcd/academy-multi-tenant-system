<x-layout>
    <x-slot name="title">Academys</x-slot>

    {{-- add academy form --}}
    <template id="add-academy">
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-8 gap-y-4">
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Add New Academy</x-heading>
            </div>

            <!-- Rows -->
            <div class="grid gap-y-6">
                @csrf
                <x-input-box lable="Academy Title" name="name" id="name" placeholder="Academy Name"
                    icon="building-stadium" />
                <x-input-box lable="Academy Email" type="email" name="email" id="email" placeholder="academy@mail.com"
                    icon="mail" />
                <x-input-box lable="Academy Address" name="address" id="address"
                    placeholder="672 Dickens Plaza Lamonttown" icon="map-pin" />
                <x-input-box lable="Academy Logo" type="file" name="photo" id="photo" icon="photo" />
                <x-input-box lable="Academy Favicon" type="file" name="favicon" id="favicon" icon="favicon" />
            </div>
            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Add Academy</span></x-button-primary>
            </div>
        </form>
    </template>

    <main class="bg-white rounded-xl border border-slate-200 ">
        <!-- Page Details & Filters -->
        <div class="flex flex-col lg:flex-row gap-8 py-8 px-8 items-center justify-between">
            <div class="flex flex-col gap-1">
                <x-bread-crumb>
                    <a href="{{url("/academies")}}">Academies</a>
                </x-bread-crumb>
                <x-heading>{{ request('search') ? 'Search Result for: ' . request('search') : 'All Academies' }}
                </x-heading>
            </div>

            <div class="flex items-center gap-4 flex-wrap justify-center">
                <a href="#add-academy">
                    <x-button-primary icon="square-rounded-plus">Add Academy
                    </x-button-primary>
                </a>
                <x-search-box :url="route('academies.index')" placeholder="Search by Name.." />


            </div>
        </div>

        <!-- Table -->
        <div class=" grid overflow-auto whitespace-nowrap">
            <!-- Table Heading -->
            <x-table-heading grid='academy-grid'>
                <p>#ID</p>
                <p>Details</p>
                <p>Address</p>
                <p>Favicon</p>
                <p>Created At</p>
                <p>&nbsp;</p>
            </x-table-heading>

            <!-- Table Rows Start -->
            @forelse ($academies as $academy)
            <x-academy-row :academy="$academy" />
            @empty
            <x-no-content>Academy</x-no-content>
            @endforelse
            <!-- Table Row End -->
        </div>
    </main>

    {{-- Pagination --}}
    {{-- {{ $academys->links() }} --}}
</x-layout>