<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'username', 'password', 'role', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin', 'coach', 'manager']);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(MembershipOrder::class);
    }

    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }

    public function activeMembershipPlan(): ?MembershipPlan
    {
        if ($this->isAdmin()) {
            return MembershipPlan::where('slug', 'immortal')->first();
        }

        $paidOrder = $this->orders()
            ->with('plan')
            ->whereIn('payment_status', ['paid', 'active', 'completed'])
            ->latest('id')
            ->first();

        return $paidOrder?->plan;
    }

    public function membershipTierSlug(): string
    {
        if ($this->isAdmin()) {
            return 'immortal';
        }

        return $this->activeMembershipPlan()?->slug ?? 'free';
    }

    public function hasBracketAccess(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($this->membershipTierSlug(), ['challenger', 'immortal'], true);
    }

    public function hasLearningAccess(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($this->membershipTierSlug(), ['learning', 'immortal'], true);
    }
}
