<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class JobListing extends Model
{
    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'location',
        'type',
        'salary_min',
        'salary_max',
        'status',
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('status', 'published');
    }

    public function scopeSearch(Builder $query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%");
        });
    }
    public function scopeLocation(Builder $query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function scopeOfType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSalary(Builder $query, $min = null, $max = null)
    {
        if ($min) $query->where('salary_min', '>=', $min);
        if ($max) $query->where('salary_max', '<=', $max);
        return $query;
    }
}
