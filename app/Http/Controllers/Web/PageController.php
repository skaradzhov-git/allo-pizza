<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $seoImage = $page->featured_image
            ? Storage::url($page->featured_image)
            : null;

        $galleryImages = $slug === 'kontakti'
            ? collect(config('store-gallery.contacts', []))
                ->map(fn (string $path) => Storage::url($path))
                ->values()
                ->all()
            : [];

        return view('pages.page', compact('page', 'seoImage', 'galleryImages'));
    }
}
