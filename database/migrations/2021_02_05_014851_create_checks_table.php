<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecksTable extends Migration
{
    public function up()
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id');
            $table->string('issuer')->nullable();
            $table->string('domain')->nullable();
            $table->string('algorithm')->nullable();
            $table->string('organisation')->nullable();
            $table->text('additional_domains')->nullable();
            $table->text('sha256_fingerprint')->nullable();
            $table->boolean('is_valid')->nullable();
            $table->boolean('is_domain_valid')->nullable();
            $table->string('domain_status')->nullable();
            $table->timestamps();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('certificate_expires_at')->nullable();
            $table->timestamp('domain_expires_at')->nullable();
        });
    }
}
