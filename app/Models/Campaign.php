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
            // Since we are using the 'like' operator, we will convert the array of tags to a string
            // using pipe '|' as the separator. For example: "Tag 1|Tag 2|Tag 3"
            $tagsString = implode('|', $filters['tag']);

            // Use 'REGEXP' to perform a regular expression search on the 'category' column
            // to match any of the selected tags.
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
}
