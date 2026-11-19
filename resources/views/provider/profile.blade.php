
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Service Provider Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ $provider ? 'Edit Your Professional Profile' : 'Create Your Professional Profile' }}
                </h3>

                <p class="text-gray-600 mb-6">
                    Provide your details so customers can find your services.
                </p>

                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="mb-4 text-sm text-gray-600">Your listing details, including phone and email, will be visible to visitors.</p>
                <form method="POST" action="{{ route('provider.profile.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block font-medium mb-2">Full Name</label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $provider?->name ?? Auth::user()->name) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block font-medium mb-2">Phone Number</label>
                        <input type="text" id="phone" name="phone"
                            value="{{ old('phone', $provider?->phone) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block font-medium mb-2">Email Address</label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $provider?->email ?? Auth::user()->email) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block font-medium mb-2">Service Category</label>
                        <select id="category_id" name="category_id"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                            required>
                            <option value="">Select your service</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id', $provider?->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block font-medium mb-2">Location / Address</label>
                        <input type="text" id="address" name="address"
                            value="{{ old('address', $provider?->address) }}"
                            placeholder="Example: Itahari, Sunsari"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="experience_years" class="block font-medium mb-2">
                            Years of Experience
                        </label>
                        <input type="number" id="experience_years" name="experience_years"
                            value="{{ old('experience_years', $provider?->experience_years ?? 0) }}"
                            min="0" max="60"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                            required>
                    </div>

                    <div class="mb-6">
                        <label for="bio" class="block font-medium mb-2">
                            About Your Services
                        </label>
                        <textarea id="bio" name="bio" rows="4"
                            placeholder="Describe your skills and services..."
                            class="w-full border-gray-300 rounded-md shadow-sm">{{ old('bio', $provider?->bio) }}</textarea>
                    </div>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-md">
                        Save Profile
                    </button>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
