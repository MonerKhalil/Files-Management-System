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
        Schema::create('languages', function (Blueprint $table) {
            //column any image ( type : text ) ( name : Like %png% )...
            //column any file ( type : text ) ( name : Like %pdf% )...
            $table->id();
            $table->string("code")->unique();
            $table->string("name");
            $table->string("icon")->nullable();
            $table->boolean("isRTL")->default(false);
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
};
