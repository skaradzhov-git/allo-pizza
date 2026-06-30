<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LunchMenuResource;
use App\Models\LunchMenu;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LunchMenuController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $menus = LunchMenu::query()
            ->where('is_active', true)
            ->with(['items' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return LunchMenuResource::collection($menus);
    }
}
