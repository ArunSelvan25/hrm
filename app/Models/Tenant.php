<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Property, User};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 *
 */
class Tenant extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $guard_name = "tenant";
    protected $guard = 'tenant';

    /**
     * @var string[]
     */
    protected $fillable = [
        'property_id', 'name', 'email','phone','password','original_password','status','profile'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
