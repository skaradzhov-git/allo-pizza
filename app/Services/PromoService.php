<?php

namespace App\Services;

use App\Models\PromoCode;
use Illuminate\Support\Facades\Session;

class PromoService
{
    protected const SESSION_KEY = 'promo_code';

    public function resolve(string $code): ?PromoCode
    {
        return PromoCode::query()
            ->whereRaw('LOWER(code) = ?', [mb_strtolower(trim($code))])
            ->first();
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public function apply(string $code, float $subtotal): array
    {
        $promo = $this->resolve($code);

        if (! $promo || ! $promo->isCurrentlyValid()) {
            return ['ok' => false, 'message' => 'Невалиден или изтекъл промо код.'];
        }

        if (! $promo->meetsMinimum($subtotal)) {
            return [
                'ok' => false,
                'message' => 'Промо кодът изисква минимална поръчка от '.money((float) $promo->minimum_order_amount).'.',
            ];
        }

        Session::put(self::SESSION_KEY, $promo->code);

        return ['ok' => true, 'message' => 'Промо кодът „'.$promo->code.'" е приложен.'];
    }

    public function appliedCode(): ?string
    {
        return Session::get(self::SESSION_KEY);
    }

    public function applied(): ?PromoCode
    {
        $code = $this->appliedCode();

        if (! $code) {
            return null;
        }

        $promo = $this->resolve($code);

        return $promo && $promo->isCurrentlyValid() ? $promo : null;
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function discount(float $subtotal): float
    {
        $promo = $this->applied();

        if (! $promo || ! $promo->meetsMinimum($subtotal)) {
            return 0.0;
        }

        return $promo->discountFor($subtotal);
    }
}
