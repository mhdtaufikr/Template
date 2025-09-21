<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_details', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('loc_header_id'); // Foreign key reference to loc_headers.id
            $table->foreign('loc_header_id')->references('id')->on('loc_headers')->onDelete('cascade');
            $table->string('name'); // Varchar column for name
            $table->timestamps(); // Created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loc_details');
    }
}
