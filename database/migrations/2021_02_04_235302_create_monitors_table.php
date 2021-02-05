<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitorsTable extends Migration
{
    public function up()
    {
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->string('site')->unique();
            $table->boolean('is_valid')->default(true);
            $table->timestamps();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('certificate_expires_at')->nullable();
            $table->timestamp('domain_expires_at')->nullable();
        });
    }
}
