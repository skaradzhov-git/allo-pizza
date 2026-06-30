<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $slug): View
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['products' => fn ($query) => $query->where('is_active', true)->with('variants')->orderBy('sort_order')])
            ->firstOrFail();

        return view('pages.category', compact('category'));
    }
}
