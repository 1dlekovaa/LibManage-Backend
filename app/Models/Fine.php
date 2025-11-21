<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'amount',
        'paid',
    ];

    protected $casts = [
        'paid' => 'boolean',
    ];

    /**
     * Relations
     */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
