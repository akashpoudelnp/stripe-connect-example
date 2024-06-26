<?php

namespace App\Models;

use App\Traits\HasStripeConnectAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasStripeConnectAccount;
    protected $fillable = [
        'name',
        'website',
        'stripe_account_id',
        'stripe_account_status',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'client_id');
    }
}
