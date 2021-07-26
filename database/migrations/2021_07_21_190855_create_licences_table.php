<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', \App\Models\License::Types);
            $table->boolean('status')->default(1);
            $table->unsignedSmallInteger('max_use');
            $table->string('key')->unique();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->date('expires_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licenses');
    }
}
