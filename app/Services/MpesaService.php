<?php

namespace App\Services;

use App\Exceptions\MpesaRequestException;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MpesaService
{
    /**
     * Get a cached OAuth access token for the Daraja API.
     *
     * @throws MpesaRequestException
     */
    public function accessToken(): string
    {
        return Cache::remember('mpesa.access_token', now()->addMinutes(55), function () {
            $response = Http::withBasicAuth(
                config('mpesa.consumer_key'),
                config('mpesa.consumer_secret'),
            )->get(config('mpesa.base_url').'/oauth/v1/generate', [
                'grant_type' => 'client_credentials',
            ]);

            if (! $response->successful() || ! $response->json('access_token')) {
                throw new MpesaRequestException('Unable to authenticate with M-Pesa. Please try again shortly.');
            }

            return $response->json('access_token');
        });
    }

    /**
     * Initiate an STK push ("Lipa Na M-Pesa Online") prompt on the customer's
     * phone for the given order, and record the request identifiers on it.
     *
     * @return array{merchant_request_id: string, checkout_request_id: string, customer_message: string}
     *
     * @throws MpesaRequestException
     */
    public function stkPush(Order $order, string $phone): array
    {
        $normalizedPhone = $this->normalizePhone($phone);
        $timestamp = now()->format('YmdHis');
        $shortcode = config('mpesa.shortcode');
        $password = base64_encode($shortcode.config('mpesa.passkey').$timestamp);

        $response = Http::withToken($this->accessToken())
            ->post(config('mpesa.base_url').'/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => (int) round($order->total / 100),
                'PartyA' => $normalizedPhone,
                'PartyB' => $shortcode,
                'PhoneNumber' => $normalizedPhone,
                'CallBackURL' => config('mpesa.callback_url'),
                'AccountReference' => $order->order_number,
                'TransactionDesc' => "Payment for order {$order->order_number}",
            ]);

        if (! $response->successful() || $response->json('ResponseCode') !== '0') {
            throw new MpesaRequestException(
                $response->json('errorMessage') ?? $response->json('ResponseDescription') ?? 'M-Pesa did not accept the payment request. Please try again.'
            );
        }

        $order->update([
            'payment_method' => 'mpesa',
            'mpesa_checkout_request_id' => $response->json('CheckoutRequestID'),
            'mpesa_merchant_request_id' => $response->json('MerchantRequestID'),
            'mpesa_phone' => $normalizedPhone,
        ]);

        return [
            'merchant_request_id' => $response->json('MerchantRequestID'),
            'checkout_request_id' => $response->json('CheckoutRequestID'),
            'customer_message' => $response->json('CustomerMessage', 'Check your phone to complete payment.'),
        ];
    }

    /**
     * Actively query the result of a previously initiated STK push, for
     * clients polling from the browser instead of waiting on the callback.
     *
     * @return array{completed: bool, success: bool, description: string}
     */
    public function queryStatus(string $checkoutRequestId): array
    {
        $timestamp = now()->format('YmdHis');
        $shortcode = config('mpesa.shortcode');
        $password = base64_encode($shortcode.config('mpesa.passkey').$timestamp);

        $response = Http::withToken($this->accessToken())
            ->post(config('mpesa.base_url').'/mpesa/stkpushquery/v1/query', [
                'BusinessShortCode' => $shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId,
            ]);

        // Safaricom returns an error payload (no ResultCode) while the
        // transaction is still awaiting the customer's PIN entry.
        if (! $response->successful() || $response->json('ResultCode') === null) {
            return ['completed' => false, 'success' => false, 'description' => 'Awaiting confirmation.'];
        }

        $resultCode = (string) $response->json('ResultCode');

        return [
            'completed' => true,
            'success' => $resultCode === '0',
            'description' => $response->json('ResultDesc', ''),
        ];
    }

    /**
     * Normalize a Kenyan phone number to the 2547XXXXXXXX / 2541XXXXXXXX
     * format M-Pesa requires.
     */
    public function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        return match (true) {
            str_starts_with($digits, '254') => $digits,
            str_starts_with($digits, '0') => '254'.substr($digits, 1),
            default => '254'.$digits,
        };
    }
}
