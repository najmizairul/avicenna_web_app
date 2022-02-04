<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ["expires_at", 'created_at', 'updated_at'];

    protected $casts = [
        'expires_at' => 'datetime:d M Y, h:iA',
        'created_at' => 'datetime:d M Y, h:iA',
        'updated_at' => 'datetime:d M Y, h:iA',
    ];

    public function prescription(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
       return $this->belongsToMany(Prescription::class);
    }
}
