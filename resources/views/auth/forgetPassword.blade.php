<x-layout>
    <x-slot name="title">Forget Password</x-slot>

    <!-- Main -->
    <div class="flex min-h-full flex-col justify-center px-6 py-12 h-dvh  items-center bg-white rounded-lg lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-auto w-15" src="/img/logo.png" alt="Your Company logo">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Forget Your Password ?</h2>
            <p class="text-center text-sm text-slate-500 mt-3">Enter your email address and we'll send you a link to
                reset
                your password.
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="grid gap-4">
                    <x-input-box :row="false" lable="Email Address" name="email" id="email" placeholder="your@mail.com"
                        icon="mail" />
                </div>

                <a class=" text-blue-500 text-sm my-3 inline-block" href="{{route('login')}}">&larr; Back To
                    Login</a>

                <div class="grid mt-3">
                    <x-button-primary>Send Reset Link</x-button-primary>
                </div>
            </form>
        </div>
    </div>
</x-layout>