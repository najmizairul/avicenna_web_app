<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVisit extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ["visited_at", 'created_at', 'updated_at'];

    protected $casts = [
        'visited_at' => 'datetime:d M Y, h:iA',
        'created_at' => 'datetime:d M Y, h:iA',
        'updated_at' => 'datetime:d M Y, h:iA',
    ];



    protected $table = "patient_visits";


    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function referrer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function prescription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function attendee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
