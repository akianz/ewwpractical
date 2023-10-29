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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("brand_id")->nullable();
            $table->foreign("brand_id")->references('id')->on('brands')->onDelete("cascade");
            $table->string("name",250)->nullable();
            $table->string("description",250)->nullable();
            $table->string("image",500)->nullable();
            $table->integer("stock")->default(0);
            $table->float("price",8, 2)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
};
