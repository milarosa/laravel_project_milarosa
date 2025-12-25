<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * 1. Rename table
         */
        Schema::rename('orderproducts', 'order_products');

        /**
         * 2. Alter columns & foreign keys
         */
        Schema::table('order_products', function (Blueprint $table) {

            // order_id
            $table->unsignedBigInteger('order_id')->nullable(false)->change();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            // product_id
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            // jumlah
            $table->integer('jumlah')->nullable(false)->change();

            // harga_satuan
            $table->decimal('harga_satuan', 10, 2)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {

            $table->dropForeign(['order_id']);
            $table->dropForeign(['product_id']);

            $table->integer('order_id')->nullable()->change();
            $table->integer('product_id')->nullable()->change();
            $table->integer('jumlah')->nullable()->change();
            $table->decimal('harga_satuan', 10)->nullable()->change();
        });

        Schema::rename('order_products', 'orderproducts');
    }
};
