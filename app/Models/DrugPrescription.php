<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugPrescription extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "drug_prescription";

    protected $guarded = ['id'];

    protected $casts = [
        'starts_at' => 'datetime:d M Y',
        'created_at' => 'datetime:d M Y, h:iA',
        'updated_at' => 'datetime:d M Y, h:iA',
    ];

    public function drug(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }

    public function prescription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

}
