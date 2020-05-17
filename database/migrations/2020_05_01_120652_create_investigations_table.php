<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('item');
            $table->longText('description');
            $table->string('condition')->nullable();
            $table->string('lost_location');
            $table->foreignId('location_id')->nullable();
            $table->foreignId('category_id');
            $table->string('status')->default('new');
            $table->string('initial_status');
            $table->date('lost_date');
            $table->string('owner_name')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_phone')->nullable();
            $table->longText('additional_info')->nullable();
            $table->string('delivered_too')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();

            $table->index('location_id');
            $table->index('category_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('initial_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investigations');
    }
}
