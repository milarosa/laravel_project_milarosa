<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->decimal('total', 12, 2)->nullable(false)->change();
            $table->date('tanggal')->nullable(false)->change();
            $table->enum(
                'status_pembayaran',
                ['pending', 'diproses', 'lunas', 'gagal']
            )->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->integer('user_id')->nullable()->change();
            $table->decimal('total', 10)->nullable()->change();
            $table->date('tanggal')->nullable()->change();
            $table->enum(
                'status_pembayaran',
                ['pending', 'sukses', 'gagal']
            )->nullable()->change();
        });
    }
};
