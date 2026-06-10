<?php

namespace Modules\Coupon\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Project\Models\Project;
use App\Traits\BelongsToProject;
use Illuminate\Support\Str;

// use Modules\Coupon\Database\Factories\CouponFactory;

class Coupon extends Model
{
    use HasFactory, SoftDeletes, BelongsToProject;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table =
    'coupons';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'project_id',

        'name',

        'code',

        'amount',

        'type',

        'per_person',

        'min_order_value',

        'usage_limit',

        'used_count',

        'valid_from',

        'valid_until',

        'description',

        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'amount' =>
        'decimal:2',

        'min_order_value' =>
        'decimal:2',

        'per_person' =>
        'boolean',

        'status' =>
        'boolean',

        'valid_from' =>
        'datetime',

        'valid_until' =>
        'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function project()
    {
        return $this->belongsTo(
            Project::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getFormattedAmountAttribute()
    {
        return $this->type === 'fixed'

            ? '€' . number_format(
                $this->amount,
                2
            )

            : $this->amount . '%';
    }

    /*
    |--------------------------------------------------------------------------
    | Active Scope
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            1
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Valid Scope
    |--------------------------------------------------------------------------
    */

    public function scopeValid($query)
    {
        return $query

            ->where(
                'status',
                1
            )

            ->where(function ($q) {

                $q->whereNull(
                    'valid_from'
                )

                    ->orWhere(
                        'valid_from',
                        '<=',
                        now()
                    );
            })

            ->where(function ($q) {

                $q->whereNull(
                    'valid_until'
                )

                    ->orWhere(
                        'valid_until',
                        '>=',
                        now()
                    );
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Check Expired
    |--------------------------------------------------------------------------
    */

    public function isExpired(): bool
    {
        if (!$this->valid_until) {

            return false;
        }

        return now()->greaterThan(
            $this->valid_until
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Check Usage Limit
    |--------------------------------------------------------------------------
    */

    public function isUsageLimitReached(): bool
    {
        if (!$this->usage_limit) {

            return false;
        }

        return $this->used_count >=
            $this->usage_limit;
    }

    /*
    |--------------------------------------------------------------------------
    | Check Valid Coupon
    |--------------------------------------------------------------------------
    */

    public function isValidCoupon(): bool
    {
        return

            !$this->isExpired()

            &&

            !$this->isUsageLimitReached()

            &&

            $this->status;
    }

    public static function generateCouponCode(
        ?string $projectName = null,
        ?string $couponName = null,
        ?string $type = null,
        ?float $amount = null
    ) {
        do {

            $parts = [];

            if ($projectName) {
                $parts[] = strtoupper(substr(
                    preg_replace('/[^A-Za-z0-9]/', '', $projectName),
                    0,
                    3
                ));
            }

            if ($couponName) {
                $parts[] = strtoupper(substr(
                    preg_replace('/[^A-Za-z0-9]/', '', $couponName),
                    0,
                    3
                ));
            }

            if ($type) {
                $parts[] = strtolower($type) === 'percentage'
                    ? 'PER'
                    : 'AMT';
            }

            if ($amount) {
                $parts[] = (int)$amount;
            }

            $parts[] = strtoupper(Str::random(4));

            $code = implode('-', $parts);

            if (empty($projectName) && empty($couponName)) {
                $code = 'CPQ-' . strtoupper(Str::random(8));
            }
        } while (
            Coupon::where('code', $code)->exists()
        );

        return $code;
    }
}
