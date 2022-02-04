<?php

namespace Database\Seeders;

use App\Models\Drug;
use App\Models\DrugPrescription;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        array_map(function ($role) {
            User::factory(3)->create(['role' => $role]);
        }, config('settings.roles'));

        array_map(function ($drug) {
            Drug::factory()->create(['name' => $drug]);
        }, ['Paracetamol', 'Flagyl', 'Panadol', 'Morphine', 'Septrine', 'Novagen', 'Ibruprofen']);

        Patient::factory(10)->create()->each(function ($patient) {
            PatientVisit::factory()->create([
                'patient_id' => $patient->id,
            ])->each(function() {
                DrugPrescription::factory()->create();
            });
        });
    }
}
