<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find local plumbers, electricians, cleaners, tutors and more with SewaKhoj.">
    <title>SewaKhoj — Find local services</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:block focus:p-4">Skip to content</a>
    <header class="border-b border-slate-200 bg-white">
        <nav aria-label="Main navigation" class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-5 sm:px-8">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tight text-teal-700">SewaKhoj<span class="text-teal-400">.</span></a>
            <div class="flex flex-wrap items-center gap-5 text-sm font-semibold">
                <a href="{{ route('services.index') }}" class="hover:text-teal-700">Find services</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-white hover:bg-teal-800">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-teal-700">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-white hover:bg-teal-800">Join SewaKhoj</a>
                @endauth
            </div>
        </nav>
    </header>
    <main id="main">
        <section class="border-b border-teal-100 bg-teal-50 px-5 py-14 sm:py-20">
            <div class="mx-auto max-w-5xl text-center">
                <p class="mb-4 text-xs font-bold uppercase tracking-widest text-teal-700">Local people. Everyday help.</p>
                <h1 class="text-4xl font-extrabold tracking-tight text-teal-950 sm:text-5xl">The right service,<br class="sm:hidden"> close to home.</h1>
                <p class="mx-auto mt-5 max-w-xl text-lg text-slate-600">Discover local professionals for your home, your work, and your everyday needs.</p>
                <form action="{{ route('services.index') }}" method="GET" class="mt-9 grid gap-3 rounded-2xl bg-white p-5 text-left shadow-sm ring-1 ring-teal-100 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="search" class="mb-2 block text-sm font-semibold">Service or name</label>
                        <input id="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="e.g. plumber" class="w-full rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                    </div>
                    <div>
                        <label for="location" class="mb-2 block text-sm font-semibold">Location</label>
                        <input id="location" name="location" value="{{ $filters['location'] ?? '' }}" maxlength="100" placeholder="e.g. Itahari" class="w-full rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                    </div>
                    <div>
                        <label for="category" class="mb-2 block text-sm font-semibold">Category</label>
                        <select id="category" name="category" class="w-full rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">All services</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="self-end rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white hover:bg-teal-800 focus:ring-2 focus:ring-teal-600 focus:ring-offset-2">Find services</button>
                </form>
                @if ($errors->any())
                    <ul role="alert" class="mt-4 text-left text-sm text-red-700">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                @endif
            </div>
        </section>
        <div class="mx-auto max-w-7xl px-5 py-12 sm:px-8">
            <section id="categories" aria-labelledby="categories-heading">
                <h2 id="categories-heading" class="text-2xl font-bold">Explore services</h2>
                <p class="mt-2 text-slate-500">A little help for every part of your day.</p>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($categories as $category)
                        <a href="{{ route('services.index', ['category' => $category->id]) }}" class="rounded-xl border border-slate-200 bg-white p-5 transition hover:border-teal-500 hover:shadow-sm">
                            <h3 class="font-bold text-teal-800">{{ $category->name }} <span aria-hidden="true">→</span></h3>
                            <p class="mt-2 text-sm text-slate-500">{{ $category->description }}</p>
                        </a>
                    @empty
                        <p class="text-slate-500">Service categories will appear here when available.</p>
                    @endforelse
                </div>
            </section>
            <section id="providers" class="mt-14" aria-labelledby="providers-heading">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 id="providers-heading" class="text-2xl font-bold">Local service providers</h2>
                        <p class="mt-2 text-slate-500">{{ $providers->total() }} {{ Str::plural('professional', $providers->total()) }} found</p>
                    </div>
                    @if (collect($filters)->filter()->isNotEmpty())
                        <a href="{{ route('services.index') }}" class="text-sm font-semibold text-teal-700 underline">Clear filters</a>
                    @endif
                </div>
                <div class="mt-6 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($providers as $provider)
                        <article class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-teal-50 text-xl font-bold text-teal-800" aria-hidden="true">{{ Str::upper(Str::substr($provider->name, 0, 1)) }}</div>
                                @if ($provider->is_verified)
                                    <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-800">✓ Verified</span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">Not yet verified</span>
                                @endif
                            </div>
                            <h3 class="mt-4 break-words text-xl font-bold">{{ $provider->name }}</h3>
                            <p class="mt-1 font-medium text-teal-700">{{ $provider->category?->name ?? 'General services' }}</p>
                            <p class="mt-4 break-words text-sm text-slate-600">{{ $provider->address }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $provider->experience_years }} {{ Str::plural('year', $provider->experience_years) }} of experience</p>
                            <p class="mb-6 mt-4 break-words text-sm leading-relaxed text-slate-600">{{ Str::limit($provider->bio, 180) }}</p>
                            <div class="mt-auto flex flex-wrap gap-3 border-t border-slate-100 pt-4">
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $provider->phone) }}" aria-label="Call {{ $provider->name }}" class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">Call provider</a>
                                @if ($provider->email)
                                    <a href="mailto:{{ $provider->email }}" aria-label="Email {{ $provider->name }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Email</a>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center md:col-span-2 lg:col-span-3">
                            <h3 class="text-lg font-semibold">No matching providers yet</h3>
                            <p class="mt-2 text-slate-500">Try another service or location, or check back as our community grows.</p>
                            <a href="{{ route('services.index') }}" class="mt-4 inline-block font-semibold text-teal-700 underline">Browse all services</a>
                        </div>
                    @endforelse
                </div>
                <div class="mt-8">{{ $providers->links() }}</div>
            </section>
        </div>
    </main>
    <footer class="bg-teal-950 px-5 py-8 text-center text-sm text-teal-100">© {{ date('Y') }} SewaKhoj. Connecting communities through local services.</footer>
</body>
</html>
