<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpsalesTable extends Migration
{
    public function up()
    {
        Schema::create('upsales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('upsale_project_type')->nullable();
            $table->string('other_project_type')->nullable();
            $table->decimal('upsale_value', 15, 2)->default(0);
            $table->decimal('upsale_upfront', 15, 2)->default(0);
            $table->string('upsale_currency')->default('USD');
            $table->string('upsale_payment_method')->nullable();
            $table->date('upsale_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('upsales');
    }
}
