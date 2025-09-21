<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id(); // Auto-incremental primary key
            $table->integer('cost_ctr'); // Integer column for cost center
            $table->integer('coar'); // Integer column for coar
            $table->integer('cocd'); // Integer column for cocd
            $table->string('cctc'); // Varchar column for cctc
            $table->string('pic'); // Varchar column for pic
            $table->string('user_pic')->nullable(); // Varchar column for user_pic
            $table->string('remarks')->nullable(); // Nullable varchar column for remarks
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
        Schema::dropIfExists('cost_centers');
    }
}
