<?php

namespace App\Enums;

enum SmtpType: string
{
    case DEFAULT = 'default';
    case CUSTOMER = 'customer';
    case INVOICE = 'invoice';
    case SUPPLIER = 'supplier';

    // 👉 Dropdown ke liye labels
    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => 'Default',
            self::CUSTOMER => 'Customer',
            self::INVOICE => 'Invoice',
            self::SUPPLIER => 'Supplier',
        };
    }

    // 👉 All options array (Blade / API use)
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }

    // 👉 Only values
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
