@props(['amount'])

{{ 'KSh '.number_format($amount / 100, 0) }}
