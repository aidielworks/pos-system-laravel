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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('order_no')->unique();
            $table->unsignedInteger('discount_id')->nullable();
            $table->decimal('subtotal_amount')->default(0);
            $table->decimal('discount_amount')->default(0);
            $table->decimal('total_amount')->default(0);
            $table->decimal('paid_amount')->default(0);
            $table->integer('payment_method')->nullable();
            $table->integer('status')->default(0);
            $table->unsignedInteger('table_id')->nullable();
            $table->unsignedInteger('created_by');
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
        Schema::dropIfExists('orders');
    }
};
