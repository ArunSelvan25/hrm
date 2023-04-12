<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HouseOwner;
use App\Models\Tenant;

/**
 * Property model
 * Table properties
 */
class Property extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
       'house_owner_id', 'title', 'description','image','status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function houseOwner()
    {
        return $this->belongsTo(HouseOwner::class);
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }
}
