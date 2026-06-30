<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LunchMenu;
use App\Models\LunchMenuItem;
use App\Models\Page;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LunchMenuController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    public function index(): View
    {
        $page = Page::query()
            ->where('slug', 'obedno-menyu')
            ->where('is_active', true)
            ->first();

        $lunchMenus = LunchMenu::query()
            ->where('is_active', true)
            ->with(['items' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return view('pages.lunch', compact('page', 'lunchMenus'));
    }

    public function addItem(Request $request, LunchMenuItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $lunchMenu = $this->resolveMenuForItem($item);

        if (! $lunchMenu) {
            return back()->with('error', 'Този артикул не е част от активно обедно меню.');
        }

        if (! $lunchMenu->isCurrentlyActive()) {
            return back()->with('error', 'Обедното меню не е активно в момента.');
        }

        $this->cartService->addLunchItem($item, $validated['quantity'] ?? 1);

        return redirect()->route('cart')->with('status', 'Артикулът е добавен в количката.');
    }

    public function addSelected(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected' => ['required', 'array', 'min:1'],
            'selected.*' => ['integer', 'exists:lunch_menu_items,id'],
            'quantities' => ['nullable', 'array'],
            'quantities.*' => ['integer', 'min:1', 'max:20'],
        ]);

        $activeMenus = LunchMenu::query()
            ->where('is_active', true)
            ->with(['items' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $activeMenu = $activeMenus->first(fn (LunchMenu $menu) => $menu->isCurrentlyActive());

        if (! $activeMenu) {
            return back()->with('error', 'Обедното меню не е активно в момента.');
        }

        $allowedIds = $activeMenu->items->pluck('id')->all();
        $entries = [];

        foreach ($validated['selected'] as $itemId) {
            $item = LunchMenuItem::query()
                ->where('is_active', true)
                ->find($itemId);

            if (! $item || ! in_array($item->id, $allowedIds, true)) {
                continue;
            }

            $entries[] = [
                'item' => $item,
                'quantity' => $validated['quantities'][$itemId] ?? 1,
            ];
        }

        if (empty($entries)) {
            return back()->with('error', 'Не са избрани валидни артикули.');
        }

        $added = $this->cartService->addLunchItems($entries);

        return redirect()->route('cart')->with('status', $added === 1
            ? '1 артикул е добавен в количката.'
            : $added.' артикула са добавени в количката.');
    }

    protected function resolveMenuForItem(LunchMenuItem $item): ?LunchMenu
    {
        return LunchMenu::query()
            ->where('is_active', true)
            ->whereHas('items', fn ($query) => $query->where('lunch_menu_items.id', $item->id)->where('lunch_menu_items.is_active', true))
            ->orderBy('sort_order')
            ->get()
            ->first(fn (LunchMenu $menu) => $menu->isCurrentlyActive())
            ?? LunchMenu::query()
                ->where('is_active', true)
                ->whereHas('items', fn ($query) => $query->where('lunch_menu_items.id', $item->id)->where('lunch_menu_items.is_active', true))
                ->orderBy('sort_order')
                ->first();
    }
}
