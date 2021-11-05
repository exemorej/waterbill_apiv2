<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'contact_num',
        'email',
        'meter_num'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
