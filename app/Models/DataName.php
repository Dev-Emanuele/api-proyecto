<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataName extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function values()
    {
        return $this->hasMany(DataValue::class);
    }
}
