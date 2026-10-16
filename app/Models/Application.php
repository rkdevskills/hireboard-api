<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'job_id',
        'candidate_id',
        'cover_letter',
        'resume_path',
        'status',
    ];

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class, 'job_id');
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }
}
