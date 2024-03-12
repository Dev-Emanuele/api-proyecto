<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataValue extends Model
{
    protected $fillable = ['value', 'data_name_id'];

    public function dataName()
    {
        return $this->belongsTo(DataName::class);
    }
}
