<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpDokanVendorBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_dokan_vendor_balance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('trn_id');
            $table->string('trn_type', 30);
            $table->text('perticulars');
            $table->decimal('debit', 19, 4);
            $table->decimal('credit', 19, 4);
            $table->string('status', 30)->nullable();
            $table->timestamp('trn_date')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->timestamp('balance_date')->default('01:01:01');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_dokan_vendor_balance');
    }
}
