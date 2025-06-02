<x-layout>
    <x-slot name="title">Login</x-slot>

    <!-- Main -->
    <div class="flex min-h-full flex-col justify-center px-6 py-12 h-dvh  items-center bg-white rounded-lg lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-auto w-15" src="/img/logo.png" alt="Your Company logo">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign in to your account</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form action="{{route('password.update')}}" method="POST">
                @csrf
                <div class="grid gap-4">
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ request('email') }}">

                    <x-input-box :row="false" lable="New Password" name="password" id="password" placeholder="********"
                        icon="password" />

                    <x-input-box :row="false" lable="Confirm Password" name="password_confirmation" type="password"
                        id="password_confirmation" placeholder="********" icon="password" />
                </div>

                <a class=" text-blue-500 text-sm my-3 inline-block" href="{{route('login')}}">&larr; Back To
                    login</a>

                <div class="grid mt-2">
                    <x-button-primary>Log in</x-button-primary>
                </div>
            </form>
        </div>
    </div>
</x-layout>