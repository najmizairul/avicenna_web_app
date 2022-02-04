<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ["collect_at", "collected_at" ,"last_visited_at", 'created_at', 'updated_at'];

    protected $casts = [
        'collect_at' => 'datetime:d M Y, h:iA',
        'collected_at' => 'datetime:d M Y, h:iA',
        'last_visited_at' => 'datetime:d M Y, h:iA',
        'created_at' => 'datetime:d M Y, h:iA',
        'updated_at' => 'datetime:d M Y, h:iA',
    ];

    public function visit(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PatientVisit::class);
    }

    public function drugs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DrugPrescription::class)->with('drug');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
