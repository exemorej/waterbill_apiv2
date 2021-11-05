<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->integer('consumer_id');
            $table->text('status');
            $table->date('period_start');
            $table->date('period_end');
            $table->date('due_date');
            // $table->text('image')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('paid', 10, 2);
            $table->integer('reader_id');
            $table->text('reader_name');
            $table->date('reading_date');
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
        Schema::dropIfExists('bills');
    }
}
