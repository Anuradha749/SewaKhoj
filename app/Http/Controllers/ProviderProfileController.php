<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProviderProfileController extends Controller
{
    // Display provider profile form
    public function create()
    {
        if (Auth::user()->role !== 'provider') {
            abort(403, 'Only service providers can access this page.');
        }

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        $provider = Auth::user()->serviceProvider()->first();

        return view('provider.profile', compact('categories', 'provider'));
    }

    // Save or update provider profile
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'provider') {
            abort(403, 'Only service providers can manage this profile.');
        }

        $provider = $user->serviceProvider()->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^\+?[0-9][0-9 ()-]{5,19}$/',
                Rule::unique('service_providers', 'phone')
                    ->ignore($provider?->id),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:60'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('is_active', true)],
        ]);

        $user->serviceProvider()->updateOrCreate(
            ['user_id' => $user->id],
            array_merge($validated, [
                'is_verified' => false,
                'is_active' => $provider?->is_active ?? true,
            ])
        );

        return redirect()->route('provider.profile')
            ->with('success', 'Your provider profile has been saved successfully!');
    }
}
