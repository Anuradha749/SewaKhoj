
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            SewaKhoj Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    Welcome, {{ Auth::user()->name }}!
                </h3>

                <p class="text-gray-600 mt-2">
                    Welcome to SewaKhoj, your local service finder.
                </p>

                <p class="mt-3">
                    <strong>Your role:</strong>
                    <span class="capitalize">
                        {{ Auth::user()->role === 'provider'
                            ? 'Service Provider'
                            : Auth::user()->role }}
                    </span>
                </p>
            </div>

            @if (Auth::user()->role === 'customer')

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-3">
                        Customer Dashboard
                    </h3>

                    <p class="text-gray-600 mb-4">
                        Find trusted local professionals for your needs.
                    </p>

                    <a href="{{ url('/') }}"
                       class="inline-block bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700">
                        Browse Services
                    </a>
                </div>

            @elseif (Auth::user()->role === 'provider')

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-3">
                        Service Provider Dashboard
                    </h3>

                    <p class="text-gray-600 mb-4">
                        Welcome! You can prepare your professional profile
                        to help customers find your services.
                    </p>

                    <p class="text-amber-700 bg-amber-50 p-3 rounded-md">
                        Create or update your listing so customers can find your services.
                    </p>
                    <a href="{{ route('provider.profile') }}"
   class="inline-block bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700">
    Manage My Profile
</a>
                </div>

            @else

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-xl font-bold">
                        Account Dashboard
                    </h3>

                    <p class="text-gray-600 mt-2">
                        Your account is active.
                    </p>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>
