<?php

namespace App\Services;

class PricingCalculator
{
    /**
     * @return array{tax: int, shipping: int}
     */
    public function calculate(int $subtotal, string $country): array
    {
        return [
            'tax' => $this->tax($subtotal, $country),
            'shipping' => $this->shipping($subtotal, $country),
        ];
    }

    public function tax(int $subtotal, string $country): int
    {
        if (strtoupper($country) !== config('pricing.tax.country')) {
            return 0;
        }

        return (int) round($subtotal * config('pricing.tax.rate'));
    }

    public function shipping(int $subtotal, string $country): int
    {
        if ($subtotal >= config('pricing.shipping.free_threshold')) {
            return 0;
        }

        return strtoupper($country) === config('pricing.shipping.domestic_country')
            ? config('pricing.shipping.domestic_fee')
            : config('pricing.shipping.international_fee');
    }
}
