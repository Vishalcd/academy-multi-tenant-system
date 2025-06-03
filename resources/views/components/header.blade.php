<header class="h-[80px] px-4 border-b border-slate-300 bg-white sticky top-0 z-40">
    <div class="container h-full">
        <div class="flex items-center h-full">
            <div class="logo ">
                <a href="{{url("/")}}"><img class="object-contain w-14"
                        src="/{{activeAcademy()?->photo ?? 'img/logo.png'}}"
                        alt="{{activeAcademy()?->name}} Logo" /></a>
            </div>

            <nav class="mr-auto h-full ml-20">
                <ul class="hidden items-center gap-4 font-semibold text-slate-600 h-full xl:flex">
                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'manager')

                    <x-nav-link icon="dashboard" url="/" :active="Route::is('home') ? true : false">Overview
                    </x-nav-link>
                    <x-nav-link icon="users" url="/students" :active="Route::is('students.*') ? true : false">Students
                    </x-nav-link>
                    <x-nav-link icon="briefcase" url="/employees" :active="Route::is('employees.*') ? true : false">
                        Employees
                    </x-nav-link>
                    @if (auth()->user()->role === 'manager')
                    <x-nav-link icon="user-check" :url="route('attendances.index')"
                        :active="Route::is('attendances.*')">
                        Attendance
                    </x-nav-link>
                    @endif
                    <x-nav-link icon="calculator" url="/expenses" :active="Route::is('expenses.*') ? true : false">
                        Expenses
                    </x-nav-link>
                    <x-nav-link icon="ball-american-football" url="/sports"
                        :active="Route::is('sports.*') ? true : false">
                        Sports
                    </x-nav-link>

                    @if (auth()->user()->role === 'admin')
                    <x-nav-link icon="building-stadium" url="/academies"
                        :active="Route::is('academies.*') ? true : false">
                        Academies
                    </x-nav-link>
                    @endif
                    @else
                    <x-nav-link icon="user-circle"
                        url="/{{Auth::user()->role === 'employee' ? 'employees' : 'students'}}/me"
                        :active="url()->current() === route('students.showMe') || url()->current() === route('employees.showMe')">
                        Profile
                    </x-nav-link>
                    <x-nav-link icon="user-check"
                        url="{{Auth::user()->role === 'employee' ? route('attendances.index') : route('students.showAttaendance')}}"
                        :active="Route::is('students.showAttaendance') || Route::is('attendances.*')">
                        Attendance
                    </x-nav-link>
                    @endif
                </ul>
            </nav>
            <div class="flex items-center gap-6">

                @if (auth()->user())
                <x-user img="{{ auth()->user()->photo}}" alt_text="{{explode(' ', auth()->user()->name)[0]}} Picture"
                    description_text="{{ucwords(auth()->user()->role)}}">
                    {{ explode(' ', auth()->user()->name)[0]}}
                </x-user>
                @endif

                <div class="flex items-center gap-3">
                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                    <button
                        class=" {{Route::is('settings.*') ? 'bg-blue-200 text-blue-500 border-blue-300' : 'text-slate-600 border-slate-200'}} rounded-md border ">
                        <a class="w-10 h-10 flex items-center justify-center" href="{{route('settings.index')}}">
                            <i class="ti ti-settings  text-xl"></i></a>
                    </button>
                    @endif
                    <form class="hidden lg:flex" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="rounded-md w-10 h-10 flex items-center justify-center text-red-500">
                            <i class="ti ti-logout"></i>
                        </button>
                    </form>

                    {{-- Mobile-Nav --}}
                    <button id="mobile-nav"
                        class=" items-center flex lg:hidden justify-center w-10 h-10 rounded-md border border-slate-200">
                        <i class="ti ti-menu-deep"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header -->