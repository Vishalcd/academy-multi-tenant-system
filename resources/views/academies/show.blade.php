<x-layout>
    <x-slot name="title">{{$academy->name}}</x-slot>

    {{-- edit academy form --}}
    <template id="edit-academy">
        <form method="POST" id="edit-form" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-8 gap-y-4">
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Edit Academy</x-heading>
            </div>

            <!-- Rows -->
            <div class="grid gap-y-6">
                @csrf
                @method('PUT')
                <x-input-box lable="Academy Title" name="name" id="name" placeholder="Academy Name"
                    icon="building-stadium" value="{{$academy->name}}" />
                <x-input-box lable="Academy Email" type="email" name="email" id="email" placeholder="academy@mail.com"
                    icon="mail" value="{{$academy->email}}" />
                <x-input-box lable="Academy Address" name="address" id="address"
                    placeholder="672 Dickens Plaza Lamonttown" icon="map-pin" value="{{$academy->address}}" />
                <x-input-box lable="Academy Logo" type="file" name="photo" id="photo" icon="photo" />
                <x-input-box lable="Academy Favicon" type="file" name="favicon" id="favicon" icon="favicon" />
            </div>
            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Edit Academy</span></x-button-primary>
            </div>
        </form>
    </template>

    {{-- delete academy form --}}
    <template id="delete-academy">
        <x-delete-box resource="Academy" />
    </template>

    {{-- add manager form --}}
    <template id="add-manager">
        <form action="{{route('academies.storeManager', $academy->id)}}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 gap-y-4 gap-x-8">
            @csrf

            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Add New manager</x-heading>
            </div>

            @csrf
            <x-input-box lable="Enter Full Name" name="name" id="name" placeholder="FullName" icon="user" />
            <x-input-box lable="Email Address" name="email" id="email" placeholder="your@mail.com" icon="mail" />
            <x-input-box lable="Phone Number" name="phone" id="phone" placeholder="xxxxx-xxxxx" icon="phone" />
            <x-input-box lable="Address" name="address" id="address" placeholder="672 Dickens Plaza Lamonttown"
                icon="map-pin" />

            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Add Manager</span></x-button-primary>
            </div>
        </form>
    </template>


    <main>
        <div
            class="flex bg-white py-8 px-8 flex-col items-center justify-between rounded-xl border border-slate-200 mb-6 md:mb-12">
            <div class="w-full flex items-center justify-between pb-6 mb-6 border-b border-slate-200">
                <div class="flex items-start md:items-center flex-col md:flex-row gap-2 md:gap-4">
                    <!-- Button Back -->
                    <x-button-back url="{{route('academies.index')}}" />

                    <!-- Breadcrump -->
                    <x-bread-crumb>
                        <a class="after:content-['/'] after:ml-1" href="{{route('academies.index')}}">All Academys</a>
                        <a href="{{route('academies.show', $academy->id)}}">{{$academy->id}}</a>
                    </x-bread-crumb>
                </div>

                <div class="flex items-center gap-2">
                    <x-button-small url="#edit-academy"><i class="ti ti-edit"></i> <span
                            class="leading-2 text-sm">Edit</span>
                    </x-button-small>

                    <x-button-small url="#delete-academy">
                        <i class="ti ti-trash"></i>
                    </x-button-small>
                </div>
            </div>

            <div
                class="w-full py-4 gap-6 md:gap-16 items-center justify-center h-full grid grid-cols-1 lg:grid-cols-[2fr_4fr]">
                <div class=" flex items-center justify-center">
                    <img class=" aspect-square rounded-lg object-cover object-center" src="/storage/{{$academy->photo}}"
                        alt="{{$academy->name}}" onerror="this.onerror=null;this.src='/img/no_image.jpg';" />
                </div>

                <div class="grid grid-cols-2 justify-center gap-12 sm:gap-8  lg:gap-6 lg:grid-cols-3">
                    <x-detail-box icon="building-stadium" title="Academy Title">
                        <x-number-container>{{$academy->name}}</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="users" title="Total Coach">
                        <x-number-container>2 Coaches</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="cash-banknote" title="Academy Address">
                        <x-number-container>{{$academy->address}}</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="users" title="Total Students">
                        <x-number-container>50 Students</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="ball-american-football" title="Total Sport">
                        <x-number-container>05</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="mail" title="Academy Email">
                        <x-number-container><span class=" text-wrap w-3/5 break-all">{{$academy->email}}</span>
                        </x-number-container>
                    </x-detail-box>
                </div>
            </div>
        </div>

        <!-- Academy Manger Table -->
        <div class="bg-white w-full rounded-xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-5 sm:md:px-10 flex-row gap-4">
                <x-heading>Academy Manager</x-heading>
                <a href="#add-manager">
                    <x-button-primary>
                        <i class="ti ti-square-rounded-plus"></i> Add manager
                    </x-button-primary>
                </a>
            </div>

            <div class=" overflow-auto w-full">
                <!-- Table Heading -->
                <x-table-heading grid="manager-grid">
                    <p>#ID</p>
                    <p>Details</p>
                    <p>Phone</p>
                    <p>Address</p>
                    <p>Join Us</p>
                    <p>&nbsp;</p>
                </x-table-heading>


                <!-- Table Rows Start -->
                @forelse ($managers as $manager)
                <x-manager-row :manager="$manager" />
                @empty
                <x-no-content>Managers</x-no-content>
                @endforelse
            </div>

        </div>

    </main>
</x-layout>