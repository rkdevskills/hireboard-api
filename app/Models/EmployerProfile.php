<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class EmployerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_description',
        'website',
        'location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
