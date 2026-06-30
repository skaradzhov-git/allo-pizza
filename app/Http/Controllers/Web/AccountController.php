<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    public function index(Request $request): View
    {
        $customer = $request->user()->customer;
        $recentOrders = $customer?->orders()->latest()->limit(5)->get() ?? collect();
        $ordersCount = $customer?->orders()->count() ?? 0;
        $addressesCount = $customer?->addresses()->count() ?? 0;

        return view('account.index', [
            'customer' => $customer,
            'recentOrders' => $recentOrders,
            'ordersCount' => $ordersCount,
            'addressesCount' => $addressesCount,
            'lastOrder' => $recentOrders->first(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $emailChanged = $user->email !== $validated['email'];

        $user->forceFill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
        ])->save();

        $user->customer()->updateOrCreate([], [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return back()->with('status', 'Профилът е обновен.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return back()->with('status', 'Паролата е сменена успешно.');
    }

    public function sendPasswordResetLink(Request $request): RedirectResponse
    {
        $status = PasswordBroker::sendResetLink([
            'email' => $request->user()->email,
        ]);

        return $status === PasswordBroker::RESET_LINK_SENT
            ? back()->with('status', 'Изпратихме линк за възстановяване на паролата на имейла ви.')
            : back()->withErrors(['email' => 'Не успяхме да изпратим линк за възстановяване.']);
    }

    public function orders(Request $request): View
    {
        $orders = $request->user()->customer
            ?->orders()
            ->latest()
            ->paginate(10) ?? collect();

        return view('account.orders', compact('orders'));
    }

    public function orderShow(Request $request, Order $order): View
    {
        abort_unless($request->user()->customer?->id === $order->customer_id, 403);

        $order->load('items.options');

        return view('account.order-show', compact('order'));
    }

    public function reorder(Request $request, Order $order): RedirectResponse
    {
        abort_unless($request->user()->customer?->id === $order->customer_id, 403);

        $order->load('items.product');

        foreach ($order->items as $item) {
            $product = Product::query()->find($item->product_id);

            if (! $product) {
                continue;
            }

            $variant = ProductVariant::query()
                ->where('product_id', $product->id)
                ->where('name', explode(' ', $item->variant_name)[0] ?? '')
                ->first()
                ?? $product->variants()->first();

            if ($variant) {
                $this->cartService->addItem($product, $variant, $item->quantity, $item->note);
            }
        }

        return redirect()->route('cart')->with('status', 'Продуктите са добавени в количката.');
    }

    public function addresses(Request $request): View
    {
        $addresses = $request->user()->customer?->addresses()->orderByDesc('is_default')->get() ?? collect();

        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $customer = $request->user()->customer;
        abort_unless($customer, 403);

        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = $request->boolean('is_default') || $customer->addresses()->count() === 0;

        if ($isDefault) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $customer->addresses()->create([
            'label' => $validated['label'] ?? null,
            'address_line' => $validated['address_line'],
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'is_default' => $isDefault,
        ]);

        return back()->with('status', 'Адресът е добавен.');
    }

    public function updateAddress(Request $request, Address $address): RedirectResponse
    {
        $customer = $request->user()->customer;
        abort_unless($customer && $customer->id === $address->customer_id, 403);

        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            $customer->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update([
            'label' => $validated['label'] ?? null,
            'address_line' => $validated['address_line'],
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'is_default' => $isDefault,
        ]);

        return back()->with('status', 'Адресът е обновен.');
    }

    public function destroyAddress(Request $request, Address $address): RedirectResponse
    {
        abort_unless($request->user()->customer?->id === $address->customer_id, 403);

        $address->delete();

        return back()->with('status', 'Адресът е изтрит.');
    }
}
