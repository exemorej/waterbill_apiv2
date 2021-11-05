<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_id',
        'status',
        'period_start',
        'period_end',
        'due_date',
        'image',
        'amount',
        'paid',
        'reader_id',
        'reader_name',
        'reading_date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }
}
