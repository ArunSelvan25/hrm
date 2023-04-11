<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HouseOwner;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
       'house_owner_id', 'title', 'description','image','status'
    ];

    public function houseOwner()
    {
        return $this->belongsTo(HouseOwner::class);
    }
}
