<div id="nav"
    class=" h-dvh fixed top-0  -right-full bottom-0 w-full md:border-l md:border-slate-200 md:shadow-lg p-8 md:w-3/6 lg:hidden z-50 bg-[rgba(255,255,255,0.85)] backdrop-blur-sm ">
    <button id="mobile-nav"
        class="absolute top-6 rounded-md text-3xl right-6 w-10 aspect-square flex items-center justify-center">
        <i class="ti ti-x"></i>
    </button>

    <div class="flex flex-col">
        <ul
            class=" flex flex-col gap-6 text-xl font-semibold tracking-tight text-slate-600  border-b border-slate-200 py-10">
            @if (Auth::user()->role === 'admin' || Auth::user()->role ==='manager')
            <x-mobile-nav-link icon="dashboard" isLink="{{request()->is('/')}}" url="{{route('home')}}">
                Overview
            </x-mobile-nav-link>

            <x-mobile-nav-link icon="users" isLink="{{Route::is('students.*')}}" url="{{route('students.index')}}">
                Students
            </x-mobile-nav-link>

            <x-mobile-nav-link icon="briefcase" isLink="{{Route::is('employees.*')}}"
                url="{{route('employees.index')}}">
                Employees
            </x-mobile-nav-link>

            <x-mobile-nav-link icon="calculator" isLink="{{Route::is('expenses.*')}}" url="{{route('expenses.index')}}">
                Expenses
            </x-mobile-nav-link>

            @if (Auth::user()->role === 'manager')
            <x-mobile-nav-link icon="user-check" isLink="{{Route::is('attendances.*')}}"
                url="{{route('attendances.index')}}">
                Attendances
            </x-mobile-nav-link>
            @endif

            <x-mobile-nav-link icon="ball-american-football" isLink="{{Route::is('sports.*')}}"
                url="{{route('sports.index')}}">
                Sports
            </x-mobile-nav-link>

            @if (Auth::user()->role === 'admin')
            <x-mobile-nav-link icon="building-stadium" isLink="{{Route::is('academies.*')}}"
                url="{{route('academies.index')}}">
                Academies
            </x-mobile-nav-link>
            @endif

            @else
            <x-mobile-nav-link icon="user-circle"
                isLink="{{url()->current() === route('students.showMe') || url()->current() === route('employees.showMe')}}"
                url="/{{Auth::user()->role === 'employee' ? 'employees' : 'students'}}/me">
                Profile
            </x-mobile-nav-link>

            <x-mobile-nav-link icon="user-check"
                isLink="{{Route::is('students.showAttaendance') || Route::is('attendances.*')}}"
                url="{{Auth::user()->role === 'employee' ? route('attendances.index') : route('students.showAttaendance')}}">
                Attendance
            </x-mobile-nav-link>
            @endif
        </ul>

        @if (auth()->user())

        <ul class=" flex flex-col gap-6 items-center justify-center tracking-tight flex-wrap text-slate-600 py-10">
            <x-user :huge="true" img="{{ auth()->user()->photo}}"
                alt_text="{{explode(' ', auth()->user()->name)[0]}} Picture"
                description_text="{{ucwords(auth()->user()->role)}}">
                {{ explode(' ', auth()->user()->name)[0]}}
            </x-user>
            <div class="flex items-center gap-2 font-medium">
                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                <a class="w-auto aspect-rectangle py-2 px-4 rounded-md border border-slate-200 flex items-center justify-center gap-2"
                    href="{{route('settings.index')}}">
                    <i class="ti ti-settings"></i>
                    <p>Settings</p>
                </a>
                @endif

                <form action="{{route('logout')}}" method="POST">
                    @csrf
                    <button
                        class="w-auto aspect-rectangle py-2 px-4 rounded-md border text-red-500 border-slate-200 flex items-center justify-center gap-2">
                        <i class="ti ti-logout"></i>
                        <p>Logout</p>
                    </button>
                </form>
            </div>
        </ul>
        @endif
    </div>
</div>