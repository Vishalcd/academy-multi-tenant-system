<x-layout>
    <x-slot name="title">{{$employee->user->name}} Profile</x-slot>

    {{-- update me form --}}
    <template id="update-me">
        <form action="{{route('user.update', $employee->user->id)}}" enctype="multipart/form-data" method="POST"
            class="grid grid-cols-1 gap-x-8 gap-y-4">
            @csrf
            @method('PUT')
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Update Me</x-heading>
            </div>

            <!-- Row Left -->
            <div class="grid gap-y-6">
                <x-input-box lable="Enter Full Name" :value="old('name', $employee->user->name)" name="name" id="name"
                    placeholder="FullName" icon="user" />
                <x-input-box lable="Phone Number" :value="old('phone', $employee->user->phone)" name="phone" id="phone"
                    placeholder="xxxxx-xxxxx" icon="phone" />
                <x-input-box lable="Employee Address" :value="old('address', $employee->user->address)" name="address"
                    id="address" placeholder="672 Dickens Plaza Lamonttown" icon="map-pin" />
                <x-input-box lable="Upload Image" type="file" name="photo" id="photo" icon="photo-up" />
            </div>

            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Update</span></x-button-primary>
            </div>
        </form>
    </template>

    {{-- update password form --}}
    <template id="update-password">
        <form action="{{ route('user.updatePassword', $employee->user->id) }}" enctype="multipart/form-data"
            method="POST" class="grid grid-cols-1 gap-x-8 gap-y-4">
            @csrf
            @method('PUT')

            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Update Password</x-heading>
            </div>

            <!-- Row Left -->
            <div class="grid gap-y-6">
                <x-input-box lable="Old Password" type="password" name="oldPassword" id="oldPassword"
                    placeholder="********" icon="password" />
                <x-input-box lable="New Password" type="password" name="newPassword" id="newPassword"
                    placeholder="********" icon="password" />
                <x-input-box lable="Confirm Password" type="password" name="newPassword_confirmation"
                    id="newPassword_confirmation" placeholder="********" icon="password" />
            </div>

            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Update</span></x-button-primary>
            </div>
        </form>
    </template>

    <main>
        <div
            class="flex bg-white py-8 px-8 flex-col items-center justify-between rounded-xl border border-slate-200 mb-6 md:mb-12">
            <div class="w-full flex items-center justify-between pb-6 mb-6 border-b border-slate-200">
                <x-heading>Welcome, {{explode(' ',$employee->user->name)[0]}}</x-heading>
                <div class="flex items-center justify-end w-full gap-2">
                    <x-button-small url="#update-me"><i class="ti ti-edit"></i> <span
                            class="leading-2 text-sm">Update</span>
                    </x-button-small>

                    <x-button-small url="#update-password"><i class="ti ti-password-user"></i> <span
                            class="leading-2 text-sm">Update
                            Password</span>
                    </x-button-small>
                </div>
            </div>

            <div
                class="flex w-full items-center justify-between py-4 gap-10 flex-col sm:flex-row md:flex-row lg:flex-col xl:flex-row">
                <x-user :huge="true" img="{{$employee->user->photo}}" alt_text="{{$employee->user->name}}"
                    description_text="{{$employee->user->email}}">
                    {{$employee->user->name}} - <span
                        class=" font-mono text-blue-600">{{ucfirst($employee->sport->sport_title)}}
                        {{ucfirst($employee->job_title)}}</span>
                </x-user>

                <div class="grid grid-cols-2 gap-12 sm:gap-6 lg:gap-8 lg:grid-cols-3">
                    <x-detail-box icon="phone" title="Mobile no.">
                        <x-make-call>{{$employee->user->phone}}</x-make-call>
                    </x-detail-box>

                    <x-detail-box icon="moneybag" title="Total Salary">
                        <x-number-container>{{formatCurrency($employee->salary)}}</x-number-container>
                    </x-detail-box>


                    <x-detail-box icon="building-stadium" title="Academy">
                        <x-number-container>{{$employee->user->academy->name}}</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="chart-pie" title="Status">
                        <x-pill :settle="$employee->salary_settled">{{$employee->salary_settled ? 'Settled' :
                            'Not-Settled'}}
                        </x-pill>
                    </x-detail-box>

                    <div class="w-60">
                        <x-detail-box icon="map-pin" title="Address">
                            <x-number-container>
                                <address class=" text-sm leading-4">{{$employee->user->address}}</address>
                            </x-number-container>
                        </x-detail-box>
                    </div>

                    <x-detail-box icon="calendar" title="Join Us">
                        <x-number-container>{{$employee->created_at->format('d M, Y')}}</x-number-container>
                    </x-detail-box>
                </div>
            </div>
        </div>

        <!-- Employee Transctions Table -->
        <div class="bg-white w-full rounded-xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-5 sm:md:px-10 flex-row gap-4">
                <div class="flex items-center gap-6">
                    <x-heading>Salary</x-heading>
                </div>
            </div>

            <div class="overflow-auto w-full">
                <!-- Table Heading -->
                <x-table-heading grid="transactions-grid">
                    <p>#ID</p>
                    <p>Deposit Amount</p>
                    <p>Pending Amount</p>
                    <p>Type</p>
                    <p>Date</p>
                    <p>Time</p>
                </x-table-heading>


                @forelse ($transactions as $transaction)
                <x-employee-transaction :transaction="$transaction" />
                @empty
                <x-no-content>Transaction</x-no-content>
                @endforelse
            </div>
        </div>
    </main>
</x-layout>