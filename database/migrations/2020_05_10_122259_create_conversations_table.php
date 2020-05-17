<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investigation_id');
            $table->set('messagetype', ['notification', 'message', 'phone'])->default('message');
            $table->boolean('from_guest')->default(false);
            $table->boolean('processed')->default(true);
            $table->foreignId('user_id')->nullable();
            $table->text('message');
            $table->timestamps();

            $table->index('investigation_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
