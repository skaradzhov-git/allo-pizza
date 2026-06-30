<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->with(['products' => fn ($query) => $query->where('is_active', true)->with('variants')->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return view('pages.menu', compact('categories'));
    }
}
