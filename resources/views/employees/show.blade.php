<x-layout>
    <x-slot name="title">Employee {{$employee->user->name}}</x-slot>
    {{-- edit employee form --}}
    <template id="edit-employee">
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            @csrf
            @method('PUT')

            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Edit employee</x-heading>
            </div>

            <!-- Row Left -->
            <div class="grid gap-y-6">
                @csrf
                <x-input-box :value="old('name', $employee->user->name)" lable="Enter Full Name" name="name" id="name"
                    placeholder="FullName" icon="user" />
                <x-input-box :value="old('email', $employee->user->email)" lable="Email Address" name="email" id="email"
                    placeholder="your@mail.com" icon="mail" />
                <x-input-box :value="old('phone', $employee->user->phone)" lable="Phone Number" name="phone" id="phone"
                    placeholder="xxxxx-xxxxx" icon="phone" />
                <x-input-box :value="old('address', $employee->user->address)" lable="Address" name="address"
                    id="address" placeholder="672 Dickens Plaza Lamonttown" icon="map-pin" />
            </div>

            <!-- Row Right -->
            <div class="grid gap-y-6">
                <x-input-box :value="old('salary', $employee->salary)" lable="Enter Salary" name="salary" id="salary"
                    placeholder="{{formatCurrency(30000)}}" icon="moneybag" />

                <x-input-box lable="Academy" name="Academy" id="Academy" value="{{activeAcademy()?->name}}"
                    disabled="disabled" icon="building-stadium" />

                <!-- Input Row -->
                <div class="flex items-center gap-2">
                    <label class="font-medium text-sm flex items-center gap-1.5 min-w-40 text-slate-600" for="sport_id">
                        <span class="text-lg"><i class="ti ti-ball-american-football"></i></span>Select Sport
                    </label>
                    <x-select :value="old('sport_id', $employee->sport->sport_id)" name="sport_id" id="sport_id"
                        :options="$sports" />
                </div>
                <x-input-box lable="Upload Image" type="file" name="photo" id="photo" icon="photo-up" />
            </div>

            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Edit Employee</span></x-button-primary>
            </div>
        </form>
    </template>

    {{-- delete employee form --}}
    <template id="delete-employee">
        <x-delete-box resource="Employee" />
    </template>

    {{-- submit Deposit salary form --}}
    <template id="deposit-salary">
        <form method="POST" action="/employees/{{$employee->id}}/deposit-salary" class="grid gap-x-8 gap-y-4">
            @csrf
            <div class="col-start-1 -col-end-1 mb-3">
                <x-heading>Deposit Salary</x-heading>
            </div>

            <!-- Row Left -->
            <div class="grid gap-y-6">
                <x-input-box lable="Enter Salary" name="transaction_amount" id="transaction_amount"
                    placeholder="₹10,000.00" icon="moneybag" />
                <div class="flex items-center gap-2">
                    <label class="font-medium text-sm flex items-center gap-1.5 min-w-40 text-slate-600"
                        for="transaction_method">
                        <span class="text-lg"><i class="ti ti-credit-card"></i></span>
                        Payment Method
                    </label>

                    <x-select name="transaction_method" id="transaction_method"
                        :options="['cash' => 'Cash', 'online' => 'Online' ]" />
                </div>
            </div>

            <!-- Actions -->
            <div class="col-start-1 -col-end-1 flex items-center gap-3 mt-4">
                <x-button-primary type="secondary"><span class="leading-2 font-semibold">Cancel</span>
                </x-button-primary>
                <x-button-primary><span class="leading-2 font-semibold">Deposit Salary</span></x-button-primary>
            </div>
        </form>
    </template>

    <main>
        <div
            class="flex bg-white py-8 px-8 flex-col items-center justify-between rounded-xl border border-slate-200 mb-6 md:mb-12">
            <div class="w-full flex items-center justify-between pb-6 mb-6 border-b border-slate-200">
                <div class="flex items-start md:items-center flex-col md:flex-row gap-2 md:gap-4">
                    <!-- Button Back -->
                    <x-button-back url="{{route('employees.index')}}" />

                    <!-- Breadcrump -->
                    <x-bread-crumb>
                        <a class="after:content-['/'] after:ml-1" href="{{route('employees.index')}}">Employees</a>
                        <a href="{{route('employees.show', $employee->id)}}">{{$employee->id}}</a>
                    </x-bread-crumb>
                </div>

                <div class="flex items-center gap-2">
                    <x-button-small url="#edit-employee"><i class="ti ti-user-edit"></i> <span
                            class="leading-2 text-sm">Edit</span>
                    </x-button-small>

                    <x-button-small url="#delete-employee">
                        <i class="ti ti-trash"></i>
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
                    <x-heading>Employee Fee</x-heading>
                </div>
                <a href="#deposit-salary">
                    <x-button-primary>
                        <i class="ti ti-square-rounded-plus"></i> Deposit Salary
                    </x-button-primary>
                </a>
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