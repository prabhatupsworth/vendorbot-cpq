<?php

namespace Modules\Supplier\Enums;

enum SupplierStatusEnum: int
{
    case NO_STATUS = 0;

    case PREMIUM_AA = 1;

    case PREMIUM = 2;

    case FALLBACK = 3;

    case SHITLIST = 4;

    case REMOVED = 5;

    /*
    |--------------------------------------------------------------------------
    | Label
    |--------------------------------------------------------------------------
    */

    public function label(): string
    {
        return match ($this) {

            self::NO_STATUS =>
                '0 -  No status yet',

            self::PREMIUM_AA =>
                'A - Premium AA 👑',

            self::PREMIUM =>
                'B - Premium ⭐',

            self::FALLBACK =>
                'C - Fallback 🔄',

            self::SHITLIST =>
                'D - Shitlist 🚫',

            self::REMOVED =>
                'E - Entfernt 🗑️',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Badge Class
    |--------------------------------------------------------------------------
    */

    public function badge(): string
    {
        return match ($this) {

            self::NO_STATUS =>
                'secondary',

            self::PREMIUM_AA =>
                'warning',

            self::PREMIUM =>
                'success',

            self::FALLBACK =>
                'info',

            self::SHITLIST =>
                'danger',

            self::REMOVED =>
                'dark',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Dropdown Options
    |--------------------------------------------------------------------------
    */

    public static function options(): array
    {
        return array_map(

            fn ($case) => [

                'value' => $case->value,

                'label' => $case->label(),
            ],

            self::cases()
        );
    }
}
