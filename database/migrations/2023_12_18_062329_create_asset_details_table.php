<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_details', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('asset_header_id'); // Foreign key reference to asset_headers.id
            $table->foreign('asset_header_id')->references('id')->on('asset_headers')->onDelete('cascade');
            $table->string('asset_no'); // Varchar column for asset number
            $table->string('sub_asset'); // Varchar column for sub-asset
            $table->string('desc'); // Varchar column for description
            $table->integer('qty'); // Integer column for quantity
            $table->string('uom'); // Varchar column for unit of measure
            $table->string('asset_type'); // Varchar column for asset type
            $table->date('date'); // Date column for date
            $table->decimal('cost',15,0); // Integer column for cost
            $table->string('po_no')->nullable();; // Varchar column for purchase order number
            $table->string('serial_no')->nullable();; // Varchar column for serial number
            $table->string('img')->nullable(); // Nullable Varchar column for image
            $table->string('status'); // Varchar column for status
            $table->string('remarks')->nullable(); // Nullable Varchar column for remarks
            $table->decimal('bv_endofyear',15,0); // Integer column for book value at end of the year
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
        Schema::dropIfExists('asset_details');
    }
}
