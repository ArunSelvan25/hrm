<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Property;
use Spatie\Permission\Traits\HasRoles;

class HouseOwner extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $guard_name = "house-owner";
    protected $guard = 'house-owner';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'original_password'
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
