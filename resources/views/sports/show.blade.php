<x-layout>
    <x-slot name="title">Sport {{$sport->sport_title}}</x-slot>

    {{-- edit sport form --}}
    <template id="edit-sport">
        <form method="POST" id="edit-form" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-8 gap-y-4">
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Edit Sport</x-heading>
            </div>

            <!-- Rows -->
            <div class="grid gap-y-6">
                @csrf
                @method('PUT')
                <x-input-box lable="Sport Title" name="sport_title" id="sport_title" value="{{$sport->sport_title}}"
                    placeholder="Sport Title" icon="ball-american-football" />
                <x-input-box lable="Sport Fees" type="number" name="sport_fees" value="{{$sport->sport_fees}}"
                    id="sport_fees" placeholder="{{formatCurrency(2000)}}" icon="moneybag" />
                <x-input-box lable="Sport Image" type="file" name="photo" id="photo" icon="photo" />
            </div>
            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Edit Sport</span></x-button-primary>
            </div>
        </form>
    </template>

    {{-- delete sport form --}}
    <template id="delete-sport">
        <x-delete-box resource="Sport" />
    </template>

    <main>
        <div
            class="flex bg-white py-8 px-8 flex-col items-center justify-between rounded-xl border border-slate-200 mb-6 md:mb-12">
            <div class="w-full flex items-center justify-between pb-6 mb-6 border-b border-slate-200">
                <div class="flex items-start md:items-center flex-col md:flex-row gap-2 md:gap-4">
                    <!-- Button Back -->
                    <x-button-back url="{{route('sports.index')}}" />

                    <!-- Breadcrump -->
                    <x-bread-crumb>
                        <a class="after:content-['/'] after:ml-1" href="{{route('sports.index')}}">All Sports</a>
                        <a href="{{route('sports.show', $sport->id)}}">{{$sport->id}}</a>
                    </x-bread-crumb>
                </div>

                <div class="flex items-center gap-2">
                    <x-button-small url="#edit-sport"><i class="ti ti-edit"></i> <span
                            class="leading-2 text-sm">Edit</span>
                    </x-button-small>

                    <x-button-small url="#delete-sport">
                        <i class="ti ti-trash"></i>
                    </x-button-small>
                </div>
            </div>

            <div
                class="w-full py-4 gap-6 md:gap-16 items-center justify-center h-full grid grid-cols-1 lg:grid-cols-[2fr_4fr]">
                <div class=" flex items-center justify-center">
                    <img class="aspect-video rounded-lg overflow-hidden bg-gray-300 object-cover  object-center"
                        src="/storage/{{$sport->photo}}" alt="{{$sport->sport_title}}"
                        onerror="this.onerror=null;this.src='/img/no_image.jpg';" />
                </div>

                <div class="grid grid-cols-2 justify-center gap-12 sm:gap-6 lg:gap-12 lg:grid-cols-4">
                    <x-detail-box icon="hash" title="ID">
                        <x-number-container>{{$sport->id}}</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="ball-american-football" title="Sport Title">
                        <x-number-container>{{$sport->sport_title}}</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="cash-banknote" title="Sport Fees">
                        <x-number-container>{{formatCurrency($sport->sport_fees)}}</x-number-container>
                    </x-detail-box>


                    <x-detail-box icon="users-group" title="Total Students">
                        <x-number-container>13
                        </x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="users" title="Total Coach">
                        <x-number-container>2 Coaches</x-number-container>
                    </x-detail-box>

                    <div class="lg:col-start-2 lg:col-end-4">
                        <x-detail-box icon="building-stadium" title="Accociated Academy">
                            <x-number-container>Maharaja Singh Academy</x-number-container>
                        </x-detail-box>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sport Coach Table -->
        <div class="bg-white w-full rounded-xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-5 sm:md:px-10 flex-row gap-4">
                <x-heading>{{$sport->sport_title}} Coach</x-heading>
            </div>

            <div class=" overflow-auto w-full">
                <!-- Table Heading -->
                <x-table-heading grid="coach-grid">
                    <p>#ID</p>
                    <p>Details</p>
                    <p>Phone</p>
                    <p>Sport</p>
                    <p>Address</p>
                    <p>Join Us</p>
                    <p>&nbsp;</p>
                </x-table-heading>


                <!-- Table Rows Start -->
                @forelse ($coaches as $coach)
                <x-coach-row :coach="$coach" />
                @empty
                <x-no-content>Coach</x-no-content>
                @endforelse
            </div>
        </div>

    </main>
</x-layout>