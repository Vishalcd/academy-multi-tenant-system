<x-layout>
    <x-slot name="title">{{$student->user->name}} Profile</x-slot>

    {{-- update me form --}}
    <template id="update-me">
        <form action="{{route('user.update', $student->user->id)}}" enctype="multipart/form-data" method="POST"
            class="grid grid-cols-1 gap-x-8 gap-y-4">
            @csrf
            @method('PUT')
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Update Me</x-heading>
            </div>

            <!-- Row Left -->
            <div class="grid gap-y-6">
                <x-input-box lable="Enter Full Name" :value="old('name', $student->user->name)" name="name" id="name"
                    placeholder="FullName" icon="user" />
                <x-input-box lable="Phone Number" :value="old('phone', $student->user->phone)" name="phone" id="phone"
                    placeholder="xxxxx-xxxxx" icon="phone" />
                <x-input-box lable="Student Address" :value="old('address', $student->user->address)" name="address"
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
        <form action="{{ route('user.updatePassword', $student->user->id) }}" enctype="multipart/form-data"
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
                <x-heading>Welcome, {{explode(' ',$student->user->name)[0]}}</x-heading>
                <div class="flex items-center justify-end w-full gap-2">
                    <x-button-small url="#update-me"><i class="ti ti-edit"></i> <span
                            class="leading-2 text-sm">Update</span>
                    </x-button-small>

                    <x-button-small url="#update-password"><i class="ti ti-password-user"></i> <span
                            class="leading-2 text-sm">Updat Password</span>
                    </x-button-small>
                </div>
            </div>

            <div
                class="flex w-full items-center justify-between py-4 gap-10 flex-col sm:flex-row md:flex-row lg:flex-col xl:flex-row">
                <x-user :huge="true" img="{{$student->user->photo}}" alt_text="{{$student->user->name}}"
                    description_text="{{$student->user->email}}">
                    {{$student->user->name}} - <span
                        class=" font-mono text-blue-500">{{$student->sport->sport_title}}</span>
                </x-user>

                <div class="grid grid-cols-2 gap-12 sm:gap-8 lg:gap-8 lg:grid-cols-3">
                    <x-detail-box icon="phone" title="Mobile no.">
                        <x-make-call>{{$student->user->phone}}</x-make-call>
                    </x-detail-box>

                    <x-detail-box icon="moneybag" title="Total Fees">
                        <x-number-container>{{formatCurrency($student->total_fees)}}</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="cash-banknote" title="Pending Fee">
                        <x-number-container>{{formatCurrency($student->fees_due)}}</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="chart-pie" title="Status">
                        <x-pill :settle="$student->fees_settle">{{$student->fees_settle ? 'Settled' : 'Not-Settled'}}
                        </x-pill>
                    </x-detail-box>

                    <x-detail-box icon="ball-baseball" title="Sport">
                        <x-number-container>{{$student->sport->sport_title}}</x-number-container>
                    </x-detail-box>

                    <x-detail-box icon="building-stadium" title="Academy">
                        <x-number-container>{{$student->user->academy->name}}</x-number-container>
                    </x-detail-box>
                </div>
            </div>
        </div>

        <!-- Student Transctions Table -->
        <div class="bg-white w-full rounded-xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-5 sm:md:px-10 flex-row gap-4">
                <x-heading>Submitted Fees</x-heading>
            </div>

            <div class=" overflow-auto w-full">
                <!-- Table Heading -->
                <x-table-heading grid="transactions-grid">
                    <p>#ID</p>
                    <p>Deposit Amount</p>
                    <p>Payment Method</p>
                    <p>Transction For</p>
                    <p>Date</p>
                    <p>Time</p>
                </x-table-heading>


                <!-- Table Rows Start -->
                @forelse ($transactions as $transaction)
                <x-student-transaction :transaction="$transaction" />
                @empty
                <x-no-content>Transaction</x-no-content>
                @endforelse
            </div>

        </div>
    </main>
</x-layout>