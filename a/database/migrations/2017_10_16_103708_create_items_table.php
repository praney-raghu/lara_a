<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('item_id',11);
            $table->string('product_code',20);
            $table->string('oem_part_no',50)->nullable();
            $table->integer('brand',false,true)->length(10);
            $table->string('item',30);
            $table->integer('vehicle',false,true)->length(10);
            $table->string('model',10);
            $table->string('make',30);
            $table->timestamps();

            $table->foreign('brand')->references('brand_id')->on('brands')->onDelete('cascade');
            $table->foreign('vehicle')->references('vehicle_id')->on('vehicles')->onDelete('cascade');
            
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
