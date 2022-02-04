<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Patient extends Authenticatable
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ["dob", "last_visited_at", 'created_at', 'updated_at'];

    protected $casts = [
        'dob' => 'datetime:d M Y',
        'last_visited_at' => 'datetime:d M Y, h:iA',
        'created_at' => 'datetime:d M Y, h:iA',
        'updated_at' => 'datetime:d M Y, h:iA',
    ];

    public function visits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
       return $this->hasMany(PatientVisit::class);
    }
}
