<?php

function money_to_cents(string $amount): int
{
    if (! preg_match('/^(\d{1,10})(?:\.(\d{1,2}))?$/', $amount, $parts)) {
        throw new DomainException('Invalid monetary amount.');
    }
    return (int) $parts[1] * 100 + (int) str_pad($parts[2] ?? '', 2, '0');
}

function cents_to_money(int $cents): string
{
    if ($cents < 0) { throw new DomainException('Invalid monetary amount.'); }
    return intdiv($cents, 100) . '.' . str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
}

function format_rupiah(string $amount): string
{
    if (! preg_match('/^(\d+)(?:\.(\d{1,2}))?$/', $amount, $parts)) {
        throw new DomainException('Invalid monetary amount.');
    }
    $whole = ltrim($parts[1], '0') ?: '0';
    $grouped = preg_replace('/\B(?=(\d{3})+(?!\d))/', '.', $whole);
    $fraction = str_pad($parts[2] ?? '', 2, '0');
    return 'Rp' . $grouped . ($fraction !== '00' ? ',' . $fraction : '');
}
