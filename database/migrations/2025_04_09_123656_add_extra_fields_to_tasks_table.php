<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('progress')->nullable();
            $table->text('notes')->nullable();
            $table->json('checklist')->nullable();
            $table->json('attachments')->nullable();
            $table->json('comments')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['progress', 'notes', 'checklist', 'attachments', 'comments']);
        });
    }

};
