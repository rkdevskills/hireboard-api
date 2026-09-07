<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CandidateProfile extends Model
{
    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'location',
        'resume_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
