<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BannerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Banner::query()->active()->orderBy('sort_order');

        if ($request->filled('position')) {
            $query->where('position', $request->string('position'));
        }

        return BannerResource::collection($query->get());
    }
}
