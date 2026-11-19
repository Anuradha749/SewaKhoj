<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceDirectoryController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $query = ServiceProvider::with('category')->where('is_active', true)
            ->whereHas('category', fn ($category) => $category->where('is_active', true));

        if ($search = trim($filters['search'] ?? '')) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$search}%"));
            });
        }
        if ($location = trim($filters['location'] ?? '')) {
            $query->where('address', 'like', "%{$location}%");
        }
        if (! empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        $providers = $query->latest()->orderByDesc('id')->paginate(12)->withQueryString();

        return view('home', compact('categories', 'providers', 'filters'));
    }
}
