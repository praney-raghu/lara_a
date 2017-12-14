<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_audit', function (Blueprint $table) {
            $table->increments('login_audit_id',11);
            $table->string('username',256)->nullable();
            $table->dateTime('datetime')->nullable();
            $table->string('ip_address',16)->nullable();
            $table->string('user_agent',245)->nullable();
            $table->tinyInteger('status',false)->nullable()->length(1);
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
        Schema::dropIfExists('login_audit');
    }
}
