<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('patient_id');
            $table->foreignId('patient_visit_id');
            $table->enum('status', ['unfilled', 'ready', 'active', 'filled']);
            $table->string('code')->nullable();
            $table->timestamp('collect_at')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->boolean('is_patient_nearby')->default(false);
            $table->boolean('is_patient_coming')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
}
