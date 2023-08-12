<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pledge extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'campaign_id',
        'amount',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the campaign associated with the pledge.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
