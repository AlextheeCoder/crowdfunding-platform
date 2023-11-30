<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ethereum_address',
        'title',
        'category',
        'description',
        'target',
        'deadline',
        'image',
        'offering_type',
        'asset_type',
        'price_per_share',
        'valuation',
        'min_investment',
    ];

    public function scopeFilter($query, array $filters) {
        if ($filters['tag'] ?? false) {
           
            $tagsString = implode('|', $filters['tag']);

            
            $query->where('category', 'REGEXP', $tagsString);
        }

        if($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%');
        }
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the pledges made for the campaign.
     */
    public function pledges()
    {
        return $this->hasMany(Pledge::class, 'campaign_id');
    }
    

    public function getDaysLeftAttribute()
    {
        $deadline = Carbon::parse($this->attributes['deadline']);
        $currentDate = Carbon::now();
        return $deadline->diffInDays($currentDate);
    }
    public function comments(){
    return $this->hasMany(Comment::class);
}

}
