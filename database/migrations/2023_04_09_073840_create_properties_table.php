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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('house_owner_id');
            $table->foreign('house_owner_id')->references('id')->on('house_owners')->onDelete('cascade');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('max_person')->default(0);
            $table->integer('advance')->default(0);
            $table->integer('rent')->default(0);
            $table->tinyInteger('type')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('properties');
    }
};
