<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_headers', function (Blueprint $table) {
            $table->id(); // Auto-incremental primary key
            $table->string('asset_no'); // Varchar column for asset number
            $table->string('desc'); // Varchar column for description
            $table->integer('qty'); // Integer column for quantity
            $table->string('uom'); // Varchar column for unit of measure
            $table->string('asset_type'); // Varchar column for asset type
            $table->date('acq_date'); // Date column for acquisition date
            $table->decimal('acq_cost', 15, 0); // Integer column for acquisition cost
            $table->string('po_no')->nullable(); // Varchar column for purchase order number
            $table->string('serial_no')->nullable(); // Varchar column for serial number
            $table->string('dept'); // Varchar column for department
            $table->string('plant'); // Varchar column for plant
            $table->string('loc'); // Varchar column for location
            $table->string('cost_center'); // Varchar column for cost center
            $table->string('flag')->nullable(); // Nullable Varchar column for flag
            $table->string('img')->nullable(); // Nullable Varchar column for image
            $table->string('status'); // Varchar column for status
            $table->string('remarks')->nullable(); // Nullable Varchar column for remarks
            $table->decimal('bv_endofyear', 15, 0); // Integer column for book value at end of the year
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
        Schema::dropIfExists('asset_headers');
    }
}
