<nav class="-mx-3 flex flex-1 justify-end">
    @auth
        <a href="{{ url('/dashboard') }}"
           class="rounded-md px-3 py-2 text-lime-800 ring-1 ring-transparent transition hover:text-lime-600 focus:outline-none focus-visible:ring-[#143114] dark:text-lime-800 dark:hover:text-lime-100 dark:focus-visible:ring-white">
            Dashboard
        </a>
    @else
        <a href="{{ route('login') }}"
           class="rounded-md px-3 py-2 text-lime-800 ring-1 ring-transparent transition hover:text-lime-600 focus:outline-none focus-visible:ring-[#143114] dark:text-lime-800 dark:hover:text-lime-100 dark:focus-visible:ring-white">
            Log in
        </a>

        @if (Route::has('register'))
            <a href="{{ route('register') }}"
               class="rounded-md px-3 py-2 text-lime-800 ring-1 ring-transparent transition hover:text-lime-600 focus:outline-none focus-visible:ring-[#143114] dark:text-lime-800 dark:hover:text-lime-100 dark:focus-visible:ring-white">
                Register
            </a>
        @endif
    @endauth
</nav>
