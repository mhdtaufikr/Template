<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('audit_id');
            $table->unsignedBigInteger('asset_id');
            $table->string('condition');
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('audit_id')->references('id')->on('audits')->onDelete('cascade');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_details');
    }
}

