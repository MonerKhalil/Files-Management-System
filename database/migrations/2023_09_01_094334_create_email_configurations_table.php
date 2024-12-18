<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_configurations', function (Blueprint $table) {
            $table->id();
            $table->string("MAIL_MAILER");
            $table->string("MAIL_HOST");
            $table->integer("MAIL_PORT");
            $table->string("MAIL_USERNAME");
            $table->string("MAIL_PASSWORD");
            $table->string("MAIL_FROM_ADDRESS");
            $table->string("MAIL_FROM_NAME");
            $table->tinyText("MAIL_ENCRYPTION");
            $table->boolean("default")->default(false);
            $table->boolean("denied_from_delete")->default(false);
            $table->foreignId("created_by")->nullable()->constrained("users")->cascadeOnDelete();
            $table->foreignId("updated_by")->nullable()->constrained("users")->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_configurations');
    }
};
