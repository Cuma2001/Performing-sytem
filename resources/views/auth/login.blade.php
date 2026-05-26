<x-guest-layout>
    <!-- Loader -->
    <div class="loader-wrapper" id="loaderWrapper" style="display: none;">
        <div class="loading">
            <div class="ring"></div>
            <div class="ring"></div>
            <div class="ring"></div>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <form id="loginForm" action="{{ route('loginStore') }}" method="POST" class="space-y-4">
        @csrf

        <h2 class="text-2xl font-bold mb-6 text-center text-red-600 dark:text-red-400">Login</h2>

        <div class="input-group-custom relative mb-4">
            <i class="fa fa-envelope absolute left-3   top-3 text-[FFCD57]"></i>
            <input type="email" name="email" placeholder="Enter Email Address" 
                   class="w-full pl-10 pr-4 py-2 border-2 border-[060097] rounded-lg focus:outline-none focus:border-blue-500 transition"
                   required>
        </div>

        <div class="input-group-custom relative mb-6">
            <i class="fa fa-lock absolute left-3 top-3 text-[FFCD57]"></i>
            <input type="password" name="password" placeholder="Enter Password"
                   class="w-full pl-10 pr-4 py-2 border-2 border-[FFCD57] rounded-lg focus:outline-none focus:border-[FFCD57] transition"
                   required>
        </div>

        <button type="submit" class="btn-login w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-2 px-4 rounded-lg transition shadow-md hover:shadow-lg" id="btnSubmit">
            Login
        </button>

        <div class="extra-links text-center mt-4 space-x-2 text-sm">
            <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Forgot Password?</a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('registerUser') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Create Account</a>
        </div>
    </form>

    <script>
        $(document).ready(function(){
            $('#loginForm').submit(function(){
                $('#loaderWrapper').show();
                $('#btnSubmit').html('<i class="fa fa-spinner fa-spin"></i> Logging in...');
            });
        });
    </script>
</x-guest-layout>